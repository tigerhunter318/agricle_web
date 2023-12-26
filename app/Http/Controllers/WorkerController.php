<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Recruitment;
use App\Models\RecruitmentFavourite;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public $page_count = 5;
    public function farms_view()
    {
        $worker = User::find(Auth::user()->id);
        $farms = $this->get_own_producers($worker['id']);
//        $recruitments = RecruitmentFavouriteController::get_favourites()->toArray();
//        $farms = count($recruitments) > 0 ? User::whereIn('id', array_column($recruitments, 'producer_id'))->get()->toArray() : [];

        $result = [];
        foreach ($farms as $farm) {
            $farm['review'] = ProducerController::calculate_review($farm['id']);
            array_push($result, $farm);
        }

        $farms = $this->paginate($result)->setPath(route('worker_farms_view', ['worker_id' => $worker['id']]));

        return view('worker.farms', compact('farms'))
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function detail_view()
    {
        $worker = User::find(Auth::user()->id);
        $worker['review'] = $this->calculate_review($worker['id']);

        $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
            ->join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('_recruitments.id as recruitment_id', 'users.id as user_id', 'users.*', '_recruitments.*', '_applicants.*')
            ->where('_recruitments.status', 'completed')
            ->where('_applicants.worker_id', $worker['id'])
            ->paginate($this->page_count);

        return view('worker.detail', compact('recruitments'), ['worker' => $worker])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function profile_view()
    {
        $worker = User::find(Auth::user()->id);
        $worker['review'] = $this->calculate_review($worker['id']);


        return view('worker.profile', ['worker' => $worker]);
    }

    public function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $perPage = $perPage == null ? $this->page_count : $perPage;
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function calculate_review($worker_id)
    {
        $reviews = Applicant::where('worker_id', $worker_id)
            ->where('status', 'finished')
            ->pluck('worker_review')->toArray();

        if(count($reviews) > 0) $average = array_sum($reviews) / count($reviews);
        else $average = 0;

        return $average;
    }

    public function get_own_producers($worker_id)
    {
        $recruitment_id_array = Applicant::where('worker_id', $worker_id)
            ->pluck('recruitment_id')->toArray();
        $recruitment_id_array = array_unique($recruitment_id_array);

        $producer_id_array = Recruitment::whereIn('id', $recruitment_id_array)->pluck('producer_id')->toArray();
        $producer_id_array = array_unique($producer_id_array);

        $producers = User::whereIn('id', $producer_id_array)->get();

        return $producers;
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $request->validate(
            [
                'family_name' => 'required',
                'gender' => 'required',
                'birthday' => 'required|date',
            ],
            [
                'family_name.required' => '名前フィールドは必須です。',
                'gender.required' => '性別フィールドは必須です。',
                'birthday.required' => '生年月日フィールドが必要です。',
            ]
        );

        User::find(Auth::user()->id)->update([
            'family_name'               => $data['family_name'],
            'name'                      => isset($data['name']) ? $data['name'] : null,
            'nickname'                  => isset($data['nickname']) ? $data['nickname'] : null,
            'gender'                    => $data['isMan'] == 'true' ? "man" : "woman",
            'birthday'                  => $data['birthday'],
            'contact_address'           => isset($data['contact_address']) ? $data['contact_address'] : null,
            'address'                   => isset($data['address']) ? $data['address'] : null,
            'cell_phone'                => isset($data['cell_phone']) ? $data['cell_phone'] : null,
            'emergency_phone'           => isset($data['emergency_phone']) ? $data['emergency_phone'] : null,
            'emergency_relation'        => isset($data['emergency_relation']) ? $data['emergency_relation'] : null,
            'job'                       => isset($data['job']) ? $data['job'] : null,
            'bio'                       => isset($data['bio']) ? $data['bio'] : null,
            'appeal_point'              => isset($data['appeal_point']) ? $data['appeal_point'] : null,
        ]);

        return response()->json(['success' => true]);
    }

    public function upload_avatar(Request $request)
    {
        $request->validate(
            [
                'profile_picture' => 'required|image|max:1000',
            ],
            [
                'required' => 'この項目は必須です。',
                'image' => 'このフィールドは画像である必要があります。',
                'max' => '画像は1000未満である必要があります。',
            ]);

        $status = [];

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            // Rename image
            $filename = time().'.'.$image->guessExtension();

            $image->move(public_path('/avatars'),$filename);

            User::find(Auth::user()->id)
                ->update(['avatar' => $filename]);

            $status['filename'] = $filename;
            $status['success'] = true;
        }

        return response($status,200);
    }

    public function favourite_recruitments_view()
    {
        $favourite_recruitments = RecruitmentFavourite::where('user_id', Auth::user()->id)
            ->pluck('recruitment_id')->toArray();

        $recruitments = Recruitment::whereIn('_recruitments.id', $favourite_recruitments)
            ->paginate($this->page_count);

        if(request()->input('page') > 1 && $recruitments->count() == 0) return redirect()->route('favourite_recruitments_view', ['page' => request()->input('page') - 1]);

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
            $recruitment['isApplied'] = Applicant::where('worker_id', Auth::user()->id)->where('recruitment_id', $recruitment['id'])->count() > 0;
        }

        return view('favourites.recruitments', compact('recruitments'))
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function favourite_recruitment_view($recruitment_id)
    {
        $recruitment = RecruitmentController::get_recruitment_info($recruitment_id);

        return view('favourites.recruitment', ['recruitment' => $recruitment]);
    }
}
