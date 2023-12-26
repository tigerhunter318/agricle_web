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

class RecruitmentController extends BaseController
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

    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->input('data'), true);

        $validator = Validator::make(
            $data,
            [
                'title' => 'required',

                'post_number' => 'required',
                'prefectures' => 'required',
                'city' => 'required',
                'workplace' => 'required',

                'reward_type' => 'required',
                'reward_cost' => 'required',

                'worker_amount' => 'required',

                'work_date_start' => 'required',

                'work_time_start' => 'required',
                'work_time_end' => 'required',

                'traffic_cost' => $request->input('traffic_type') == 'beside' ? 'required' : ''
            ],
            [
                'required' => 'この項目は必須です。',
            ]
        );
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }

        $folderPath = 'uploads/recruitments/';
        if($request->has('image')){
            $image = $request->file('image');
            $exte = $image->extension();
            $file = uniqid().".".$exte;
            $path = $image->move($folderPath, $file);
            $img = Image::make($path);
            $img->resize(160, 120, function ($constraint) {
                $constraint->aspectRatio();
            })->save($folderPath.'/sm_'.$file);
        }else{
            $file = '';
        }

        $user = auth('sanctum')->user();
        $data['producer_id'] = $user['id'];
        if($data['traffic_type'] == 'include') $data['traffic_cost'] = 0;
        //TODO
//        $data['clothes'] = $data['clothes_input'] == '' ? $data['clothes_select'] : $data['clothes_input'];
        // for the fields of boolean type
//        $data['toilet'] = isset($data['toilet']);
//        $data['park'] = isset($data['park']);
//        $data['insurance'] = isset($data['insurance']);
        // set image field like exactly [filename.extension]
        $data['image'] = $file;
        // set recruitment status
        $data['status'] = isset($data['status']) ? $data['status'] : 'draft';

        $recruitment = Recruitment::create($data);

        return $this->sendResponse($recruitment, 'Recruitment data');
    }

    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->input('data'), true);

        $validator = Validator::make(
            $data,
            [
                'id' => 'required',
                'title' => 'required',

                'post_number' => 'required',
                'prefectures' => 'required',
                'city' => 'required',
                'workplace' => 'required',

                'reward_type' => 'required',
                'reward_cost' => 'required',

                'worker_amount' => 'required',

                'work_date_start' => 'required',

                'work_time_start' => 'required',
                'work_time_end' => 'required',

                'traffic_cost' => $request->input('traffic_type') == 'beside' ? 'required' : ''
            ],
            [
                'required' => 'この項目は必須です。',
            ]
        );
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }

        $recruitment = Recruitment::find($data['id']);
        $folderPath = 'uploads/recruitments/';
        if($request->has('image')){
            // delete old image
            if(Recruitment::where('image', $recruitment->image)->count() == 1 && !$data['isNew']) {
                Storage::disk('public')->delete('uploads/recruitments/'.$recruitment->image);
                Storage::disk('public')->delete('uploads/recruitments/sm_'.$recruitment->image);
            }

            $image = $request->file('image');
            $exte = $image->extension();
            $file = uniqid().".".$exte;
            $path = $image->move($folderPath, $file);
            $img = Image::make($path);
            $img->resize(160, 120, function ($constraint) {
                $constraint->aspectRatio();
            })->save($folderPath.'/sm_'.$file);
        }else{
            $file = $recruitment['image'];
        }

        $user = auth('sanctum')->user();
        $data['producer_id'] = $user['id'];
        if($data['traffic_type'] == 'include') $data['traffic_cost'] = 0;
        //TODO
//        $data['clothes'] = $data['clothes_input'] == '' ? $data['clothes_select'] : $data['clothes_input'];
        // for the fields of boolean type
//        $data['toilet'] = isset($data['toilet']);
//        $data['park'] = isset($data['park']);
//        $data['insurance'] = isset($data['insurance']);
        // set image field like exactly [filename.extension]
        $data['image'] = $file;
        // set recruitment status
        $data['status'] = isset($data['status']) && !$data['isNew'] ? $data['status'] : 'draft';

        if($data['isNew']) Recruitment::create($data);
        else $recruitment->update($data);

        return $this->sendResponse($recruitment, 'Recruitment data');
    }

    public function get_by_producer(Request $request): JsonResponse
    {
        $userData =  auth('sanctum')->user();

        $recruitments = Recruitment::where('producer_id', $userData['id'])
            ->orderByDesc('_recruitments.updated_at')
            ->get();

        $result = [];
        foreach ($recruitments as $recruitment) {
            $workplace = $recruitment['workplace'];
            $recruitment = \App\Http\Controllers\RecruitmentController::get_recruitment_info($recruitment['id']);
            $recruitment['workplace'] = $workplace;
            array_push($result, $recruitment);
        }

        return $this->sendResponse($result, 'Recruitments data');
    }

    public function get_history($user_role, $user_id)
    {
        if($user_role == 'producer') {
            $get_recruitments = Recruitment::where('producer_id', $user_id)
                ->whereIn('status', ['completed', 'canceled'])
                ->get()
                ->toArray();

            $recruitments = [];
            foreach ($get_recruitments as $recruitment) {
                if(Applicant::where('_applicants.recruitment_id', $recruitment['id'])->where('recruitment_review', '<>', 0)->count() == 0) continue;
                $recruitment['review'] = \App\Http\Controllers\RecruitmentController::calculate_review($recruitment['id']);
                $recruitment['applicants'] = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
                    ->where('_applicants.recruitment_id', $recruitment['id'])
                    ->get();
                array_push($recruitments, $recruitment);
            }
        }
        else {
            $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
                ->join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('_recruitments.id as recruitment_id', 'users.id as user_id', 'users.*', '_recruitments.*', '_applicants.*')
                ->where('_recruitments.status', 'completed')
                ->where('_applicants.worker_id', $user_id)
                ->get();
            foreach ($recruitments as $recruitment) {
                $recruitment['producer'] = User::find($recruitment['producer_id']);
            }
        }

        return $recruitments;
    }

    public function set_recruitment_status(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $comment = $request->input('comment');

        $recruitment = Recruitment::find($id)
            ->update(['status' => $status]);

        // For working recruitment
        if($status == 'working') {
            Applicant::where('recruitment_id', $id)
                ->where('status', 'waiting')
                ->update([
                    'status' => 'rejected',
                    'employ_memo' => __('messages.applicants.reject_memo')
                ]);
            $applicants = Applicant::where('recruitment_id', $id)->get();

            // report all applicants that application is changed.
            foreach ($applicants as $applicant) {
                $news = NotificationController::create(
                    $applicant['worker_id'],
                    'application_detail_view/'.$applicant['id'],
                    __('messages.alert.you_are_rejected'),
                    route(
                        'application_detail_view',
                        ['applicant_id' => $applicant['id']]
                    )
                );
                $this->pusher->trigger('chat', 'news-'.$news['user_id'], $news);
            }
        }

        // For canceling or deleting recruitment
        if($status == 'canceled' || $status == 'deleted') {
            // set reason description comment on recruitment record's comment filed why it's canceled or deleted
            Recruitment::find($id)->update(['comment' => $comment]);

            if($status == 'deleted') $applicants = Applicant::where('recruitment_id', $id)
                ->whereIn('status', ['waiting'])
                ->get();
            else $applicants = Applicant::where('recruitment_id', $id)
                ->whereIn('status', ['approved', 'finished'])
                ->get();
            foreach ($applicants as $applicant) {
                $news = NotificationController::create(
                    $applicant['worker_id'],
                    'application_detail_view/'.$applicant['id'],
                    $status == 'canceled' ? __('messages.alert.work_is_stopped') : $status == 'deleted' ? __('messages.alert.recruitment_is_canceled') : __('messages.alert.applicant_changed'),
                    route(
                        'application_detail_view',
                        ['applicant_id' => $applicant['id']]
                    )
                );
                $this->pusher->trigger('chat', 'news-'.$news['user_id'], $news);
            }
        }

        // For completed recruitment
        if($status == "completed") {
            $farmers = Applicant::where('recruitment_id', $id)->where('status', 'approved')->get();
            foreach ($farmers as $farmer) {
                $news = NotificationController::create(
                    $farmer['worker_id'],
                    'matter_review_view/'.$farmer['id'],
                    __('messages.alert.work_is_done'),
                    route(
                        'matter_review_view',
                        ['matter_id' => $id]
                    )
                );
                $this->pusher->trigger('chat', 'news-'.$news['user_id'], $news);
            }
        }

        return $this->sendResponse(true, 'Recruitment set status');
    }

    public function delete(Request $request, $id): JsonResponse
    {
        $recruitment = Recruitment::find($id);

        if(Recruitment::where('image', $recruitment->image)->count() == 1) {
            Storage::disk('public')->delete('uploads/recruitments/'.$recruitment->image);
            Storage::disk('public')->delete('uploads/recruitments/sm_'.$recruitment->image);
        }

        $recruitment->delete();

        return $this->sendResponse($recruitment, 'Recruitment deleted');
    }

    public function add_postscript(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'postscript' => 'required',
        ], ['required' => 'この項目は必須です。']);

        $id = $request->input('id');
        $postscript = $request->input('postscript');

        $recruitment = Recruitment::find($id);
        $postscripts = unserialize($recruitment['postscript']);
        if(gettype($postscripts) === 'array') array_push($postscripts, ['id' => uniqid(), 'content' => $postscript, 'time' => date("Y-m-d H:i:s")]);
        else $postscripts = [['id' => uniqid(), 'content' => $postscript, 'time' => date("Y-m-d H:i:s")]];
        $recruitment->update(['postscript' => serialize($postscripts)]);
        $recruitment['postscript'] = $postscripts;

        return $this->sendResponse($recruitment, 'Add postscript');
    }

    public function get_applicants(Request $request, $recruitment_id)
    {
        $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
            ->select('users.id as user_id', 'users.*', '_applicants.*')
            ->where('_applicants.recruitment_id', $recruitment_id)
            ->orderByDesc('_applicants.updated_at')
            ->get();

        foreach ($applicants as $applicant) {
            $applicant['review'] = WorkerController::calculate_review($applicant['user_id']);
            $applicant['address'] = format_address($applicant['post_number'], $applicant['prefectures'], $applicant['city'], $applicant['address']);
            $applicant['is_favourite'] = FavouriteController::is_favourite($applicant['worker_id']);

            $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
                ->join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('_recruitments.id as recruitment_id', 'users.id as user_id', 'users.*', '_recruitments.*', '_applicants.*')
                ->where('_applicants.worker_id', $applicant['user_id'])
                ->where('_applicants.recruitment_id', '<>', $recruitment_id)
                ->get()
                ->toArray();
            $applicant['recruitments'] = $recruitments;
        }

        return $this->sendResponse($applicants, 'applicants data');
    }

    public function get_applicant(Request $request, $recruitment_id, $worker_id)
    {
        $applicant = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
            ->select('users.id as user_id', 'users.*', '_applicants.*')
            ->where('recruitment_id', $recruitment_id)->where('worker_id', $worker_id)
            ->first();

        $applicant['review'] = WorkerController::calculate_review($applicant['worker_id']);
        $applicant['is_favourite'] = FavouriteController::is_favourite($applicant['worker_id']);

        $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
            ->join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('_recruitments.id as recruitment_id', 'users.id as user_id', 'users.*', '_recruitments.*', '_applicants.*')
            ->where('_applicants.worker_id', $applicant['user_id'])
            ->where('_applicants.recruitment_id', '<>', $recruitment_id)
            ->get()
            ->toArray();
        $applicant['recruitments'] = $recruitments;

        return $this->sendResponse($applicant, 'applicant data');
    }

    public function set_applicant_status(Request $request): JsonResponse
    {
        $recruitment_id = $request->input('recruitmentId');
        $applicant_id = $request->input('applicantId');
        $status = $request->input('status');
        $employ_memo = $request->input('employ_memo');

        $recruitment = Recruitment::find($recruitment_id);

        $applicant = Applicant::find($applicant_id);
        $applicant->update(['status' => $status, 'employ_memo' => $employ_memo]);

        $news = NotificationController::create(
            $applicant['worker_id'],
            'application_detail_view/'.$applicant_id,
            __('messages.alert.applicant_changed'),
            route(
                'application_detail_view',
                ['applicant_id' => $applicant_id]
            )
        );
        $this->pusher->trigger('chat', 'news-'.$news['user_id'], $news);

        // If there is workers approved as much as worker amount, set recruitment status to working.
        $applicant_amount = Applicant::where('recruitment_id', $recruitment_id)->where('status', 'approved')->count();
        if($recruitment['worker_amount'] == $applicant_amount) {
            $recruitment->update(['status' => 'working']);
        }

        return $this->sendResponse($recruitment, 'Recruitment set status');
    }

    public function evaluate_worker(Request $request): JsonResponse
    {
        $data = $request->all();
        $applicant = Applicant::find($data['applicantId']);
        $applicant->update([
            'worker_evaluation' => $data['workerEvaluation'],
            'worker_review' => $data['workerReview']
        ]);

        return $this->sendResponse($applicant, 'evaluate worker');
    }
}
