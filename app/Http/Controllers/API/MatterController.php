<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkerController;
use App\Models\Applicant;
use App\Models\RecruitmentFavourite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Image;

use App\Models\User;
use App\Models\Recruitment;
use Pusher\Pusher;

class MatterController extends BaseController
{
    public $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            array('cluster' => env('PUSHER_APP_CLUSTER'))
        );
    }

    public function get(Request $request): JsonResponse
    {
        $userData = auth('sanctum')->user();
        $data = $request->all();
        $conditions = $data['conditions'];

        $applied_recruitments_id = Applicant::where('worker_id', $userData['id'])->pluck('recruitment_id')->toArray();
        $applied_recruitments_id = array_unique($applied_recruitments_id);

        if(count($conditions) == 0) {
            $recruitments = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('_recruitments.*', 'users.family_name as producer_name', 'users.email as email', 'users.avatar as avatar')
                ->where('_recruitments.status', 'collecting')
                ->whereNotIn('_recruitments.id', $applied_recruitments_id)
                ->orderByDesc('_recruitments.updated_at')
                ->skip($data['skip'])
                ->take($data['limit'])
                ->get();
        }
        else {
            $recruitments = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('_recruitments.*', 'users.family_name as producer_name', 'users.email as email', 'users.avatar as avatar')
                ->where('_recruitments.status', 'collecting')
                ->whereNotIn('_recruitments.id', $applied_recruitments_id)
                ->where(function ($query) use ($conditions) {
                    $query->where('title', 'like', '%' . $conditions['keyword'] . '%')
                        ->orWhere('_recruitments.description', 'like', '%' . $conditions['keyword'] . '%')
                        ->orWhere('_recruitments.notice', 'like', '%' . $conditions['keyword'] . '%')
                        ->orWhere('_recruitments.prefectures', 'like', '%' . $conditions['keyword'] . '%')
                        ->orWhere('_recruitments.city', 'like', '%' . $conditions['keyword'] . '%')
                        ->orWhere('_recruitments.workplace', 'like', '%' . $conditions['keyword'] . '%');
                })
                ->where(function ($query) use ($conditions) {
                    $query->when(isset($conditions['cash']) && $conditions['cash'] == "true", function ($query, $conditions) {
                        $query->orWhere('pay_mode', 'cash');
                    })
                        ->when(isset($conditions['card']) && $conditions['card'] == "true", function ($query, $conditions) {
                            $query->orWhere('pay_mode', 'card');
                        });
                })
                ->where(function ($query) use ($conditions) {
                    $query
                        ->when(isset($conditions['lunch_mode']) && $conditions['lunch_mode'] == "true", function ($query, $conditions) {
                            $query->orWhere('lunch_mode', 1);
                        })
                        ->when(isset($conditions['traffic_cost']) && $conditions['traffic_cost'] == "true", function ($query, $conditions) {
                            $query->orWhere('traffic_type', 'beside');
                        })
                        ->when(isset($conditions['toilet']) && $conditions['toilet'] == "true", function ($query, $conditions) {
                            $query->orWhere('toilet', 1);
                        })
                        ->when(isset($conditions['insurance']) && $conditions['insurance'] == "true", function ($query, $conditions) {
                            $query->orWhere('_recruitments.insurance', 1);
                        })
                        ->when(isset($conditions['park']) && $conditions['park'] == "true", function ($query, $conditions) {
                            $query->orWhere('park', 1);
                        });
                })
                ->where(function ($query) use ($conditions) {
                    $query
                        ->when(isset($conditions['day1']) && $conditions['day1'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) = ?', [0]);
                        })
                        ->when(isset($conditions['day2_3']) && $conditions['day2_3'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) = ?', [2, 3]);
                        })
                        ->when(isset($conditions['in_week']) && $conditions['in_week'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) <= 7');
                        })
                        ->when(isset($conditions['week_month']) && $conditions['week_month'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) > 7 AND datediff(work_date_end, work_date_start) < 31');
                        })
                        ->when(isset($conditions['month1_3']) && $conditions['month1_3'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) In (1,2,3)');
                        })
                        ->when(isset($conditions['half_year']) && $conditions['half_year'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) > 6');
                        })
                        ->when(isset($conditions['year']) && $conditions['year'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) = 12');
                        })
                        ->when(isset($conditions['more_year']) && $conditions['more_year'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) > 12');
                        });
                })
                ->orderByDesc('_recruitments.updated_at')
                ->skip($data['skip'])
                ->take($data['limit'])
                ->get();
        }
        foreach ($recruitments as $recruitment) {
            $recruitment['is_favourite'] = $this->is_favourite($recruitment['id']);
        }

        return $this->sendResponse($recruitments, 'recruitments data');

    }

    public function get_recent(Request $request): JsonResponse
    {
        $userData = auth('sanctum')->user();
        $applied_recruitments_id = Applicant::where('worker_id', $userData['id'])->pluck('recruitment_id')->toArray();
        $applied_recruitments_id = array_unique($applied_recruitments_id);

        $recentMatters = Recruitment::where('status', 'collecting')
            ->whereNotIn('id', $applied_recruitments_id)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return $this->sendResponse($recentMatters, 'recent matters data');
    }

    public function apply(Request $request): JsonResponse
    {
        $userData = auth('sanctum')->user();
        $matter_id = $request->input('matterId');
        $apply_memo = $request->input('applyMemo');

        $recruitment = Recruitment::find($matter_id);

        $applicant = Applicant::create([
            'recruitment_id' => $matter_id,
            'worker_id' => $userData['id'],
            'apply_memo' => $apply_memo
        ]);

        // create news for producer of recruitment
        $news = NotificationController::create(
            $recruitment['producer_id'],
            'recruitment_applicants_view/'.$matter_id,
            __('messages.alert.new_applicant_arrived'),
            route(
                'recruitment_applicants_view',
                ['recruitment_id' => $matter_id]
            )
        );

        $this->pusher->trigger('chat', 'news-'.$news['user_id'], $news);

        return $this->sendResponse($applicant, 'success apply');
    }

    public function get_favourite_matters(Request $request): JsonResponse
    {
        $userData = auth('sanctum')->user();
        $data = $request->all();

        $recruitments = RecruitmentFavourite::join('_recruitments', '_recruitments.id', '=', '_recruitment_favourites.recruitment_id')
            ->where('_recruitment_favourites.user_id', $userData['id'])
            ->orderByDesc('_recruitments.updated_at')
            ->skip($data['skip'])
            ->take($data['limit'])
            ->get();

        return $this->sendResponse($recruitments, 'recruitments data');
    }

    public function set_favourite(Request $request): JsonResponse
    {
        $matter_id = $request->input('matterId');
        $user_id = auth('sanctum')->user()->id;

        $is_favourite = $this->is_favourite($matter_id);

        if($is_favourite){
            RecruitmentFavourite::where('user_id', $user_id)
                ->where('recruitment_id', $matter_id)
                ->delete();
        } else {
            RecruitmentFavourite::create([
                'user_id' => $user_id,
                'recruitment_id' => $matter_id
            ]);
        }

        return $this->sendResponse(!$is_favourite, 'success toggle favourite');
    }

    public function is_favourite($matter_id)
    {
        return RecruitmentFavourite::where('user_id', auth('sanctum')->user()->id)
                ->where('recruitment_id', $matter_id)
                ->count() > 0;
    }
}
