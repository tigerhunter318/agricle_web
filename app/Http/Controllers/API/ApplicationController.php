<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkerController;
use App\Models\Applicant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Image;

use App\Models\User;
use App\Models\Recruitment;
use Pusher\Pusher;

class ApplicationController extends BaseController
{
    public $pusher;
    public function __construct()
    {
        $this->middleware('auth');

        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            array('cluster' => env('PUSHER_APP_CLUSTER'))
        );
    }

    public function get_by_status(Request $request): JsonResponse
    {
        $userData = auth('sanctum')->user();
        $data = $request->all();

        $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
            ->join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select(
                '_applicants.id as applicant_id',
                '_recruitments.id as recruitment_id',
                '_applicants.status as applicant_status',
                '_recruitments.status as recruitment_status',
                '_recruitments.*',
                '_applicants.*',
                'users.*'
            )
            ->where('_applicants.worker_id', $userData->id)
            ->where(function($query) use($data){
                if($data['status'] == 'waiting') $query->where('_applicants.status', 'waiting');
                if($data['status'] == 'approved') $query->where('_applicants.status', 'approved');
                if($data['status'] == 'rejected') $query->where('_applicants.status', 'rejected');
                if($data['status'] == 'abandoned') $query->where('_applicants.status', 'abandoned');
                if($data['status'] == 'finished') $query->where('_applicants.status', 'finished');
                if($data['status'] == 'canceled') $query->where('_recruitments.status', 'canceled');
                if($data['status'] == 'deleted') $query->where('_recruitments.status', 'deleted');
            })
            ->orderByDesc('_applicants.updated_at')
            ->skip($data['skip'])
            ->take($data['limit'])
            ->get();
        foreach ($recruitments as $recruitment) {
            $recruitment['is_favourite'] = MatterController::is_favourite($recruitment['recruitment_id']);
        }

        return $this->sendResponse($recruitments, 'recruitments data');
    }

    public function get_by_id(Request $request, $applicant_id): JsonResponse
    {
        $recruitment = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
            ->join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select(
                '_applicants.id as applicant_id',
                '_recruitments.id as recruitment_id',
                '_applicants.status as applicant_status',
                '_recruitments.status as recruitment_status',
                '_recruitments.*',
                '_applicants.*',
                'users.*'
            )
            ->where('_applicants.id', $applicant_id)
            ->first();
        $recruitment['is_favourite'] = MatterController::is_favourite($recruitment['recruitment_id']);

        return $this->sendResponse($recruitment, 'recruitment data by id');
    }

    public function getAll(Request $request): JsonResponse
    {
        $userData = auth('sanctum')->user();

        $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
            ->join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select(
                '_applicants.id as applicant_id',
                '_recruitments.id as recruitment_id',
                '_applicants.status as applicant_status',
                '_recruitments.status as recruitment_status',
                '_recruitments.*',
                '_applicants.*',
                'users.*'
            )
            ->where('_applicants.worker_id', $userData->id)
            ->where('_recruitments.status', '<>', 'draft')
            ->orderByDesc('_applicants.updated_at')
            ->get();

        return $this->sendResponse($recruitments, 'recruitments data');
    }

    public function finish(Request $request, $matter_id): JsonResponse
    {
        $request->validate([
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

        return $this->sendResponse($applicant, 'finish application');
    }
}
