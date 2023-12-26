<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use App\Models\Applicant;
use App\Models\User;
use App\Models\Review_template;

use Pusher\Pusher;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Image;

class RecruitmentController extends Controller
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
        $recruitments = Recruitment::latest()->paginate($this->page_count);

        return view('recruitments.index', compact('recruitments'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function status_view($status = 'draft')
    {
        $user = Auth::user();
        $recruitments = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('users.id as user_id', '_recruitments.id as recruitment_id', 'users.*', '_recruitments.*')
            ->where('_recruitments.status', $status)
            ->where('_recruitments.producer_id', $user['id'])
            ->orderByDesc('_recruitments.updated_at')
            ->paginate($this->page_count);

        foreach ($recruitments as $recruitment) {
            $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
                ->select('users.id as user_id', 'users.*', '_applicants.*')
                ->where('_applicants.recruitment_id', $recruitment['id'])
                ->orderByDesc('_applicants.updated_at')
                ->get();
            $recruitment['applicants'] = $applicants;
            $recruitment_review = 0;
            foreach ($applicants as $applicant) {
                $recruitment_review += $applicant['recruitment_review'];
            }
            if(count($applicants) > 0) $recruitment_review /= count($applicants);
            $recruitment['approved_amount'] = Applicant::where('recruitment_id', $recruitment['recruitment_id'])->where('status', 'approved')->count();
            $recruitment['recruitment_review'] = $recruitment_review;
            $recruitment['workplace'] = format_address($recruitment['post_number'], $recruitment['prefectures'], $recruitment['city'], $recruitment['workplace']);
        }

        return view('recruitments.list.'.$status, compact('recruitments'))
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    // #####################################################
    // ############ Recruitment detail views ################
    // #####################################################
    public function applicants_view($recruitment_id)
    {
        $recruitment = $this->get_recruitment_info($recruitment_id);

        $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
            ->select('users.id as user_id', 'users.*', '_applicants.*')
            ->where('_applicants.recruitment_id', $recruitment_id)
            ->orderByDesc('_applicants.updated_at')
            ->paginate($this->page_count);

        foreach ($applicants as $applicant) {
            $applicant['review'] = WorkerController::calculate_review($applicant['worker_id']);
        }

        return view('recruitments.detail.applicants', compact('applicants'),  ['recruitment' => $recruitment])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function recruitment_detail_view($recruitment_id)
    {
        $recruitment = $this->get_recruitment_info($recruitment_id);

        $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
            ->select('users.id as user_id', 'users.*', '_applicants.*')
            ->where('_applicants.recruitment_id', $recruitment_id)
            ->orderByDesc('_applicants.updated_at')
            ->paginate($this->page_count);

        foreach ($applicants as $applicant) {
            $applicant['review'] = WorkerController::calculate_review($applicant['worker_id']);
        }

        return view('recruitments.detail.detail', compact('applicants'),  ['recruitment' => $recruitment])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function applicant_view($recruitment_id, $worker_id)
    {
        $recruitment = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('_recruitments.*', 'users.*', 'users.id as user_id', '_recruitments.id as recruitment_id')
            ->where('_recruitments.id', $recruitment_id)
            ->first();
        $applicants = Applicant::where('recruitment_id', $recruitment_id)->get();

        $applicant = Applicant::where('recruitment_id', $recruitment_id)->where('worker_id', $worker_id)->first();

        $worker = User::find($worker_id);
        $worker['review'] = WorkerController::calculate_review($worker_id);
        $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
            ->join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('_recruitments.id as recruitment_id', '_recruitments.status as recruitment_status', '_applicants.id as applicant_id', '_applicants.status as applicant_status', 'users.id as user_id', 'users.*', '_recruitments.*', '_applicants.*')
            ->where('_applicants.worker_id', $worker_id)
            ->where('_applicants.recruitment_id', '<>', $recruitment_id)
            ->orderByDesc('_applicants.updated_at')
            ->paginate($this->page_count);
        $applicants_count = $this->calculate_applicants($recruitment_id);

        return view('recruitments.detail.applicant', compact('recruitments'),  ['recruitment' => $recruitment, 'applicants' => $applicants, 'applicants_count' => $applicants_count, 'worker' => $worker, 'applicant' => $applicant])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function addon_view($recruitment_id)
    {
        $recruitment = $this->get_recruitment_info($recruitment_id);

        $applicants_count = $this->calculate_applicants($recruitment_id);

        return view('recruitments.detail.addon', ['recruitment' => $recruitment, 'applicants_count' => $applicants_count]);
    }

    public function review_view($recruitment_id)
    {
        $user = Auth::user();
        $recruitment = Recruitment::find($recruitment_id);
        $templates = Review_template::where('user_id', $user['id'])->get();
        $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
            ->select('users.id as user_id', 'users.*', '_applicants.*')
            ->where('_applicants.recruitment_id', $recruitment_id)
            ->whereIn('status', ['approved', 'finished', 'fired'])
            ->orderByDesc('_applicants.updated_at')
            ->get();
        // TODO paginate
        $recruitment_review = 0;
        foreach ($applicants as $applicant) {
            $recruitment_review += $applicant['recruitment_review'];
        }
        if(count($applicants) > 0) $recruitment_review /= count($applicants);
        $recruitment['recruitment_review'] = $recruitment_review;

        return view('recruitments.detail.review', ['recruitment' => $recruitment, 'applicants' => $applicants, 'templates' => $templates]);
    }

    public function result_view($recruitment_id)
    {
        $recruitment = $this->get_recruitment_info($recruitment_id);

        $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
            ->select('users.id as user_id', 'users.*', '_applicants.*')
            ->where('_applicants.recruitment_id', $recruitment_id)
            ->orderByDesc('_applicants.updated_at')
            ->paginate($this->page_count);
        foreach ($applicants as $applicant) {
            $applicant['is_favourite'] = FavouriteController::is_favourite($applicant['user_id']);
        }
        $applicants_count = $this->calculate_applicants($recruitment_id);

        return view('recruitments.detail.result', compact($applicants), ['recruitment' => $recruitment, 'applicants' => $applicants, 'applicants_count' => $applicants_count])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function add_postscript(Request $request, $recruitment_id)
    {
        $request->validate([
            'postscript' => 'required',
        ], ['required' => 'この項目は必須です。']);
        $postscript = $request->input('postscript');

        $recruitment = Recruitment::find($recruitment_id);
        $postscripts = unserialize($recruitment['postscript']);
        if(gettype($postscripts) === 'array') array_push($postscripts, ['id' => uniqid(), 'content' => $postscript, 'time' => date("Y-m-d H:i:s")]);
        else $postscripts = [['id' => uniqid(), 'content' => $postscript, 'time' => date("Y-m-d H:i:s")]];
        $recruitment->update(['postscript' => serialize($postscripts)]);

        return redirect()->route('recruitment_addon_view', ['recruitment_id' => $recruitment_id]);
    }

    public function create()
    {
        return view('recruitments.form.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required',

                'post_number' => 'required',
                'prefectures' => 'required',
                'city' => 'required',
                'workplace' => 'required',

                'reward_type' => 'required',
                'reward_cost' => 'required',

                'worker_amount' => 'required',

                'work_date_start' => 'required|date|after_or_equal:'.date("Y-m-d"),
                'work_date_end' => 'required|date|after_or_equal:work_date_start',

                'work_time_start' => 'required|date_format:H:i',
                'work_time_end' => 'required|date_format:H:i',

                'traffic_cost' => $request->input('traffic_type') == 'beside' ? 'required' : ''
            ],
            [
                'required' => 'この項目は必須です。',
                'work_date_start.after_or_equal' => '作業開始日は翌日から可能です。',
                'work_date_end.after_or_equal' => '作業終了日は、作業開始日より後でなければなりません。',
            ]
        );

        $folderPath = 'uploads/recruitments/';

        if($request->input('image')) {
            $image_parts = explode(";base64,", $request->input('image'));
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $resized_image = Image::make($image_base64)->resize(160, 120)->stream('jpg', 100);
            $file_name = uniqid();
            $file = $file_name . '.' . $image_type;
            $file_api = 'sm_'.$file_name.'.'.$image_type;

            Storage::disk('public')->put($folderPath . $file, $image_base64);
            Storage::disk('public')->put($folderPath . $file_api, $resized_image);
        }
        else {
            $file = '';
        }

        $data = $request->all();
        $user = Auth::user();
        $data['producer_id'] = $user['id'];
        if($data['traffic_type'] == 'include') $data['traffic_cost'] = 0;
        // for the fields of boolean type
        $data['toilet'] = isset($data['toilet']);
        $data['park'] = isset($data['park']);
        $data['insurance'] = isset($data['insurance']);
        // set image field like exactly [filename.extension]
        $data['image'] = $file;
        // set recruitment status
        $data['status'] = isset($data['status']) ? $data['status'] : 'draft';

        Recruitment::create($data);

        return redirect()->route('recruitment_status_view', ['status' => $data['status']]);
    }

    public function set_recruitment_status(Request $request, $id, $status)
    {
        Recruitment::find($id)->update(['status' => $status]);

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
                    __('messages.alert.applicant_changed'),
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
            if(isset($request['comment'])) Recruitment::find($id)->update(['comment' => $request['comment']]);

            if($status == 'deleted') $applicants = Applicant::where('recruitment_id', $id)
                ->get();
            else $applicants = Applicant::where('recruitment_id', $id)
                ->whereIn('status', ['approved', 'finished'])
                ->get();
            foreach ($applicants as $applicant) {
                $news = NotificationController::create(
                    $applicant['worker_id'],
                    'application_detail_view/'.$applicant['id'],
                    $status == 'canceled' ? __('messages.alert.work_is_stopped') : __('messages.alert.recruitment_is_canceled'),
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

        // for navigating to collecting list
        if($status == 'deleted') $status = 'collecting';

        return redirect()->route('recruitment_status_view', ['status' => $status]);
    }

    public function set_applicant_status(Request $request, $recruitment_id, $worker_id)
    {
        $request->validate([
            'status' => 'required',
        ], ['required' => 'この項目は必須です。']);

        $recruitment = Recruitment::find($recruitment_id);

        $applicant = Applicant::where('recruitment_id', $recruitment_id)
            ->where('worker_id', $worker_id)
            ->first();
        $applicant->update(['status' => $request->input('status'), 'employ_memo' => $request->input('employ_memo')]);

        $news = NotificationController::create(
            $worker_id,
            'application_detail_view/'.$applicant['id'],
            __('messages.alert.applicant_changed'),
            route(
                'application_detail_view',
                ['applicant_id' => $applicant['id']]
            )
        );
        $this->pusher->trigger('chat', 'news-'.$news['user_id'], $news);

        // If there is workers approved as much as worker amount, set recruitment status to working.
        $applicant_amount = Applicant::where('recruitment_id', $recruitment_id)->where('status', 'approved')->count();
        if($recruitment['worker_amount'] == $applicant_amount) {
            $recruitment->update(['status' => 'working']);
            return redirect()->route('recruitment_status_view', ['status' => 'working']);
        }

        return redirect()->route('recruitment_applicants_view', ['recruitment_id' => $recruitment_id]);
    }

    public function set_review(Request $request, $recruitment_id)
    {
        $request->validate([
            'applicant_id' => 'required',
            'status' => 'required',
            'worker_evaluation' => 'required',
        ], ['required' => 'この項目は必須です。']);

        $applicant_id = $request->input('applicant_id');
        $status = $request->input('status');
        $worker_review = $request->input('worker_review') ? $request->input('worker_review') : 0;
        $worker_evaluation = $request->input('worker_evaluation');

        $applicant = Applicant::find($applicant_id);
        $applicant->update(['worker_review' => $worker_review, 'worker_evaluation' => $worker_evaluation, 'status' => $status]);

        $news = NotificationController::create(
            $applicant['worker_id'],
            'result_detail_view/'.$applicant_id,
            __('messages.alert.review_arrived'),
            route(
                'result_detail_view',
                ['applicant_id' => $applicant_id]
            )
        );
        $this->pusher->trigger('chat', 'news-'.$news['user_id'], $news);

        return redirect()->route('recruitment_review_view', ['recruitment_id' => $recruitment_id]);
    }

    public function show(Recruitment $recruitment)
    {
        return view('recruitments.show', compact('recruitment'));
    }

    public function edit(Recruitment $recruitment)
    {
        return view('recruitments.form.edit', compact('recruitment'));
    }

    public function clone($recruitment_id)
    {
        $recruitment = Recruitment::find($recruitment_id);
        return view('recruitments.form.clone', ['recruitment' => $recruitment]);
    }

    public function update(Request $request, Recruitment $recruitment)
    {
        $request->validate([
            'title' => 'required',
//                'image' => 'required',

            'post_number' => 'required',
            'prefectures' => 'required',
            'city' => 'required',
            'workplace' => 'required',

            'reward_type' => 'required',
            'reward_cost' => 'required',

            'work_date_start' => 'required|date|after_or_equal:'.date("Y-m-d"),
            'work_date_end' => 'required|date|after_or_equal:work_date_start',

            'work_time_start' => 'required',
            'work_time_end' => 'required',
        ],
            [
                'required' => 'この項目は必須です。',
                'work_date_start.after_or_equal' => '作業開始日は翌日から可能です。',
                'work_date_end.after_or_equal' => '作業終了日は、作業開始日より後でなければなりません。',
            ]
        );

        $data = $request->all();
        $user = Auth::user();
        $data['producer_id'] = $user['id'];

        // for the fields of boolean type
        $data['toilet'] = isset($data['toilet']);
        $data['park'] = isset($data['park']);
        $data['insurance'] = isset($data['insurance']);

        // check if image is changed
        if($data['image'] && $data['image'] != $recruitment['image']) {
            // delete old image
            if(Recruitment::where('image', $recruitment->image)->count() == 1 && !isset($data['clone_status'])) {
                Storage::disk('public')->delete('uploads/recruitments/'.$recruitment->image);
                Storage::disk('public')->delete('uploads/recruitments/sm_'.$recruitment->image);
            }

            $folderPath = 'uploads/recruitments/';

            $image_parts = explode(";base64,", $request->input('image'));
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $resized_image = Image::make($image_base64)->resize(160, 120)->stream('jpg', 100);
            $file_name = uniqid();
            $file = $file_name . '.' . $image_type;
            $file_api = 'sm_'.$file_name.'.'.$image_type;

            Storage::disk('public')->put($folderPath . $file, $image_base64);
            Storage::disk('public')->put($folderPath . $file_api, $resized_image);

            $data['image'] = $file;
        }
        else {
            // set as old image name
            $data['image'] = $recruitment['image'];
        }

        $status = $recruitment['status'];
        if(isset($data['clone_status'])) {
            $data['status'] = $data['clone_status'];
            Recruitment::create($data);
            $status = $data['clone_status'];
        }
        else $recruitment->update($data);

        return redirect()->route('recruitment_status_view', ['status' => $status]);
    }

    public function destroy(Recruitment $recruitment)
    {
        if(Recruitment::where('image', $recruitment->image)->count() == 1) {
            Storage::disk('public')->delete('uploads/recruitments/'.$recruitment->image);
            Storage::disk('public')->delete('uploads/recruitments/sm_'.$recruitment->image);
        }
        $recruitment->delete();

        return redirect()->route('recruitment_status_view', ['status' => 'draft']);
    }

    public function calculate_review($recruitment_id)
    {
        $reviews = Applicant::where('recruitment_id', $recruitment_id)
            ->whereIn('status', ['finished', 'approved'])
            ->whereNotNull('recruitment_review')
            ->whereNotNull('recruitment_evaluation')
            ->pluck('recruitment_review')
            ->toArray();

        if(count($reviews) > 0)
            $average = array_sum($reviews) / count($reviews);
        else
            $average = 0;

        return $average;
    }

    public function calculate_applicants($recruitment_id)
    {
        $applicants_status = Applicant::where('recruitment_id', $recruitment_id)->pluck('status')->toArray();
        $count = array_count_values($applicants_status);
        $count['total'] = count($applicants_status);
        return $count;
    }

    public function matters_view()
    {
        return view('matters.index');
    }

    public function matter_detail_view($matter_id)
    {
        $matter = $this->get_recruitment_info($matter_id);

        return view('matters.detail', ['matter' => $matter]);
    }

    public function apply_matter(Request $request, $matter_id)
    {
        $user = Auth::user();

        Applicant::create([
            'recruitment_id' => $matter_id,
            'worker_id' => $user['id'],
            'apply_memo' => $request->input('apply_memo')
        ]);

        $recruitment = Recruitment::find($matter_id);
        $applicant_amount = Applicant::where('recruitment_id', $recruitment['id'])->count();

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

        return redirect()->route('matters_view');
    }

    public function search_matter(Request $request)
    {
        $data = $request->all();

        $applied_recruitments_id = Applicant::where('worker_id', Auth::user()->id)->pluck('recruitment_id')->toArray();
        $applied_recruitments_id = array_unique($applied_recruitments_id);

        if(count($data) == 0) {
            $recruitments = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('users.id as user_id', 'users.insurance as user_insurance', 'users.*', '_recruitments.*')
                ->where('_recruitments.status', 'collecting')
                ->where('_recruitments.approved', 1)
                ->whereNotIn('_recruitments.id', $applied_recruitments_id)
                ->orderByDesc('_recruitments.updated_at')
                ->paginate($this->page_count);
        }
        else {
            $recruitments = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('users.id as user_id', 'users.*', '_recruitments.*')
                ->where('_recruitments.status', 'collecting')
                ->whereNotIn('_recruitments.id', $applied_recruitments_id)
                ->where('_recruitments.approved', 1)
                ->where(function($query) use($data){
                    $query->where('title', 'like', '%'.$data['keyword'].'%')
                        ->orWhere('_recruitments.description', 'like', '%'.$data['keyword'].'%')
                        ->orWhere('_recruitments.notice', 'like', '%'.$data['keyword'].'%')
                        ->orWhere('_recruitments.prefectures', 'like', '%'.$data['keyword'].'%')
                        ->orWhere('_recruitments.city', 'like', '%'.$data['keyword'].'%')
                        ->orWhere('_recruitments.workplace', 'like', '%'.$data['keyword'].'%');
                })
                ->where(function ($query) use($data){
                    $query->when(isset($data['cash']) && $data['cash'] == "true", function($query, $data){
                        $query->orWhere('pay_mode', 'cash');
                    })
                        ->when(isset($data['card']) && $data['card'] == "true", function($query, $data){
                            $query->orWhere('pay_mode', 'card');
                        });
                })
                ->where(function ($query) use($data){
                    $query->when(isset($data['lunch_mode']) && $data['lunch_mode'] == "true", function($query, $data){
                        $query->orWhere('lunch_mode', 1);
                    })
                        ->when(isset($data['traffic_cost']) && $data['traffic_cost'] == "true", function($query, $data){
                            $query->orWhere('traffic_type', 'beside');
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
                    $query->when(isset($data['day1']) && $data['day1'] == "true", function ($query) {
                        $query->orWhereRaw('datediff(work_date_end, work_date_start) = ?', [0]);
                    })
                        ->when(isset($data['day2_3']) && $data['day2_3'] == "true", function ($query) {
                            $query->orWhereRaw('datediff(work_date_end, work_date_start) = ?', [2,3]);
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
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) > 6');
                        })
                        ->when(isset($data['year']) && $data['year'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) = 12');
                        })
                        ->when(isset($data['more_year']) && $data['more_year'] == "true", function ($query) {
                            $query->orWhereRaw('PERIOD_DIFF(date_format(work_date_end, "%Y%m"), date_format(work_date_start, "%Y%m")) > 12');
                        });
                })
                ->when(isset($data['custom']) && $data['custom'] == "true", function($query) use($data){
                    $query->where(function ($query) use($data) {
                        $query->orWhere('work_date_start', $data['work_date_start'])
                            ->orWhere('work_date_end', $data['work_date_end']);
                    });
                })
                ->orderByDesc('_recruitments.updated_at')
                ->paginate($this->page_count);
        }

        foreach ($recruitments as $recruitment) {
            $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
                ->select('users.id as user_id', 'users.*', '_applicants.*')
                ->where('_applicants.recruitment_id', $recruitment['id'])
                ->orderByDesc('_applicants.updated_at')
                ->get();
            $recruitment['applicants'] = $applicants;
            $recruitment_review = 0;
            foreach ($applicants as $applicant) {
                $recruitment_review += $applicant['recruitment_review'];
            }
            if(count($applicants) > 0) $recruitment_review /= count($applicants);
            $recruitment['recruitment_review'] = $recruitment_review;
            $recruitment['workplace'] = format_address($recruitment['post_number'], $recruitment['prefectures'], $recruitment['city'], $recruitment['workplace']);
            $recruitment['is_favourite'] = RecruitmentFavouriteController::is_favourite($recruitment['id']);
        }

        return view('matters.list', compact("recruitments"))
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function get_recruitment_info($recruitment_id)
    {
        $recruitment = Recruitment::find($recruitment_id);
        if(!$recruitment) return redirect()->route('dashboard');

        $recruitment['workplace'] = format_address($recruitment['post_number'], $recruitment['prefectures'], $recruitment['city'], $recruitment['workplace']);
        $recruitment['approved_amount'] = Applicant::where('recruitment_id', $recruitment['id'])->where('status', 'approved')->count();
        $recruitment['is_favourite'] = RecruitmentFavouriteController::is_favourite($recruitment['id']);
        $recruitment['producer'] = User::find($recruitment['producer_id']);
        $recruitment['producer']['review'] = ProducerController::calculate_review($recruitment['producer_id']);

        // post script
        $postscripts = unserialize($recruitment['postscript']);
        if(gettype($postscripts) !== 'array') $recruitment['postscript'] = [];
        else $recruitment['postscript'] = $postscripts;
        // applicants
        $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
            ->select('users.id as user_id', '_applicants.id as applicant_id', 'users.*', '_applicants.*')
            ->where('_applicants.recruitment_id', $recruitment['id'])
            ->get();
        foreach ($applicants as $applicant) {
            $applicant['is_favourite'] = FavouriteController::is_favourite($applicant['user_id']);
        }
        $recruitment['applicants'] = $applicants;

        return $recruitment;
    }

    public function remind_request(Request $request)
    {
        $data = $request->all();
        $type = isset($data['type']) ? $request->input('type') : 'today';
        $this->remind($type, $this->pusher);
    }

    // check and notify if there is the matters to finish and evaluate
    public function remind($type, $pusher)
    {
        $recruitments = Recruitment::where('work_date_end', $type=='old'?'<':'=', date('Y-m-d'))
            ->where('producer_id', Auth::user()->id)
            ->where('status', 'working')
            ->get();

        foreach ($recruitments as $recruitment) {
            if(NotificationController::is_double(
                $recruitment['producer_id'],
                'recruitment_detail_view/'.$recruitment['id'],
                __('messages.alert.do_complete'),
                route(
                    'recruitment_detail_view',
                    ['recruitment_id' => $recruitment['id']]
                )
            )) continue;
            $news = NotificationController::create(
                $recruitment['producer_id'],
                'recruitment_detail_view/'.$recruitment['id'],
                __('messages.alert.do_complete'),
                route(
                    'recruitment_detail_view',
                    ['recruitment_id' => $recruitment['id']]
                )
            );
            $pusher->trigger('chat', 'news-'.$news['user_id'], $news);
        }
    }
}
