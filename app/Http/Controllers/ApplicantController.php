<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Recruitment;
use App\Models\Review_template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Yajra\DataTables\Facades\DataTables;


class ApplicantController extends Controller
{
    public $page_count = 5;
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

    public function index()
    {
        $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
            ->join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('_recruitments.id as recruitment_id', '_applicants.id as applicant_id', 'users.id as user_id', 'users.*', '_applicants.status as applicant_status', '_recruitments.status as recruitment_status', '_recruitments.*', '_applicants.*')
            ->where('_applicants.worker_id', Auth::user()->id)

            ->where('_recruitments.status', '<>', 'draft')
            ->where('_recruitments.approved', 1)
            ->orderByDesc('_applicants.updated_at')
            ->paginate($this->page_count);

        foreach ($recruitments as $recruitment) {
            $recruitment_review = 0;
            $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
                ->select('users.id as user_id', 'users.*', '_applicants.*')
                ->where('_applicants.recruitment_id', $recruitment['id'])
                ->orderByDesc('_applicants.updated_at')
                ->get();
            foreach ($applicants as $applicant) {
                $recruitment_review += $applicant['recruitment_review'];
            }
            if(count($applicants) > 0) $recruitment_review /= count($applicants);
            $recruitment['review'] = $recruitment_review;
            $recruitment['workplace'] = format_address($recruitment['post_number'], $recruitment['prefectures'], $recruitment['city'], $recruitment['workplace']);
            $recruitment['is_favourite'] = RecruitmentFavouriteController::is_favourite($recruitment['recruitment_id']);
        }

        return view('applicants.index', ['recruitments' => $recruitments])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function application_detail_view($applicant_id)
    {
        $applicant = Applicant::find($applicant_id);
        $recruitment = RecruitmentController::get_recruitment_info($applicant['recruitment_id']);
        $recruitment['applicant'] = $applicant;
        $applicants_count = RecruitmentController::calculate_applicants($applicant['recruitment_id']);

        return view('applicants.detail', ['recruitment' => $recruitment, 'applicants_count' => $applicants_count]);
    }

    public function search_application(Request $request)
    {
        $data = $request->all();
        if(count($data) == 0) {
            $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
                ->join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('_recruitments.id as recruitment_id', '_applicants.id as applicant_id', 'users.id as user_id', 'users.*', '_applicants.status as applicant_status', '_recruitments.status as recruitment_status', '_recruitments.*', '_applicants.*')
                ->where('_applicants.worker_id', Auth::user()->id)
                ->where('_recruitments.status', '<>', 'draft')
                ->where('_recruitments.approved', 1)
                ->orderByDesc('_applicants.updated_at')
                ->paginate($this->page_count);
        }
        else {
            $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
                ->join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('_recruitments.id as recruitment_id', '_applicants.id as applicant_id', 'users.id as user_id', 'users.*', '_applicants.status as applicant_status', '_recruitments.status as recruitment_status', '_recruitments.*', '_applicants.*')
                ->where('_recruitments.approved', 1)
                ->where('_applicants.worker_id', Auth::user()->id)
                ->where(function($query) use($data){
                    if($data['status'] == 'all') $query->where('_recruitments.status', '<>', 'draft');
                    else {
                        if($data['status'] == 'canceled' || $data['status'] == 'deleted')
                            $query->where('_recruitments.status', $data['status']);
                        else
                            $query->where('_applicants.status', $data['status'])->whereNotIn('_recruitments.status', ['deleted', 'canceled']);
                    }
                })
                ->where(function($query) use($data){
                    $query->where('title', 'like', '%'.$data['keyword'].'%')
                        ->orWhere('description', 'like', '%'.$data['keyword'].'%')
                        ->orWhere('notice', 'like', '%'.$data['keyword'].'%');
                })
                ->where(function ($query) use($data){
                    $query
                        ->when(isset($data['cash']) && $data['cash'] == "true", function($query, $data){
                            $query->orWhere('pay_mode', 'cash');
                        })
                        ->when(isset($data['card']) && $data['card'] == "true", function($query, $data){
                            $query->orWhere('pay_mode', 'card');
                        });
                })
                ->where(function ($query) use($data){
                    $query
                        ->when(isset($data['lunch_mode']) && $data['lunch_mode'] == "true", function($query, $data){
                            $query->orWhere('lunch_mode', 1);
                        })
                        ->when(isset($data['traffic_cost']) && $data['traffic_cost'] == "true", function($query, $data){
                            $query->orWhere('traffic_cost', 'beside');
                        })
                        ->when(isset($data['toilet']) && $data['toilet'] == "true", function($query, $data){
                            $query->orWhere('toilet', 1);
                        })
                        ->when(isset($data['insurance']) && $data['insurance'] == "true", function($query, $data){
                            $query->orWhere('_recruitments.insurance', 1);
                        })
                        ->when(isset($data['park']) && $data['park'] == "true", function($query, $data){
                            $query->orWhere('park', 1);
                        });
                })
                ->where(function ($query) use($data){
                    $query
                        ->when(isset($data['day1']) && $data['day1'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) = 0');
                        })
                        ->when(isset($data['day2_3']) && $data['day2_3'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) IN (1,2)');
                        })
                        ->when(isset($data['in_week']) && $data['in_week'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) <= 7');
                        })
                        ->when(isset($data['week_month']) && $data['week_month'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) > 7 AND datediff(work_date_end, work_date_start) < 31');
                        })
                        ->when(isset($data['month1_3']) && $data['month1_3'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) In (1,2,3)');
                        })
                        ->when(isset($data['half_year']) && $data['half_year'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) = 6');
                        })
                        ->when(isset($data['year']) && $data['year'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) = 12');
                        })
                        ->when(isset($data['more_year']) && $data['more_year'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) > 12');
                        });
                })
                ->when(isset($data['custom']) && $data['custom'] == "true", function($query) use($data){
                    $query
                        ->where('work_date_start', $data['work_date_start'])
                        ->where('work_date_end', $data['work_date_end']);
                })
                ->orderByDesc('_applicants.updated_at')
                ->paginate($this->page_count);
        }

        foreach ($recruitments as $recruitment) {
            $recruitment_review = 0;
            $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
                ->select('users.id as user_id', 'users.*', '_applicants.*')
                ->where('_applicants.recruitment_id', $recruitment['id'])
                ->orderByDesc('_applicants.updated_at')
                ->get();
            foreach ($applicants as $applicant) {
                $recruitment_review += $applicant['recruitment_review'];
            }
            if(count($applicants) > 0) $recruitment_review /= count($applicants);
            $recruitment['review'] = $recruitment_review;
            $recruitment['workplace'] = format_address($recruitment['post_number'], $recruitment['prefectures'], $recruitment['city'], $recruitment['workplace']);
            $recruitment['is_favourite'] = RecruitmentFavouriteController::is_favourite($recruitment['recruitment_id']);
        }

        return view('applicants.list', ['recruitments' => $recruitments])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function review_view($matter_id)
    {
        $user = Auth::user();
        $recruitment = RecruitmentController::get_recruitment_info($matter_id);
        $applicant = Applicant::where('recruitment_id', $matter_id)
            ->where('worker_id', $user['id'])
            ->first();
        $templates = Review_template::where('user_id', $user['id'])->get();
        return view('applicants.review', ['recruitment' => $recruitment, 'applicant' => $applicant, 'templates' => $templates]);
    }

    public function result_view($applicant_id)
    {
        $applicant = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
            ->select('_applicants.id as applicant_id', '_applicants.*', 'users.*')
            ->find($applicant_id);
        $recruitment = RecruitmentController::get_recruitment_info($applicant['recruitment_id']);

        return view('applicants.result', ['recruitment' => $recruitment, 'applicant' => $applicant]);
    }

    public function finish(Request $request, $matter_id)
    {
        $request->validate([
//            'recruitment_evaluation' => 'required',
            'status' => 'required',
        ], ['required' => 'この項目は必須です。']);
        $recruitment_review = $request->input('recruitment_review');
        $recruitment_evaluation = $request->input('recruitment_evaluation');
        $status = $request->input('status');

        $recruitment = Recruitment::find($matter_id);

        $applicant = Applicant::where('worker_id', Auth::user()->id)
            ->where('recruitment_id', $matter_id);
        $applicant->update(['recruitment_review' => $recruitment_review, 'recruitment_evaluation' => $recruitment_evaluation, 'status' => $status]);

        $news = NotificationController::create(
            $recruitment['producer_id'],
            'recruitment_result_view/'.$matter_id,
            $status == 'abandoned' ? __('messages.alert.applicant_abandoned') : __('messages.alert.review_arrived'),
            route(
                'recruitment_detail_view',
                ['recruitment_id' => $matter_id]
            )
        );
        $this->pusher->trigger('chat', 'news-'.$news['user_id'], $news);

        return redirect()->route('applications_view');
    }

}
