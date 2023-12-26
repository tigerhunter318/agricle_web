<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Applicant;
use App\Models\Recruitment;
use App\Http\Controllers\RecruitmentController;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProducerController extends Controller
{
    public $page_count = 5;

    public function farmers_view()
    {
        $producer = User::find(Auth::user()->id);
//        $farmers = $this->get_own_workers($producer['id']);

        $farmers = FavouriteController::get_favourites();

        foreach ($farmers as $farmer) {
            $farmer['review'] = WorkerController::calculate_review($farmer['id']);
        }

        $farmers = $this->paginate($farmers)->setPath(route('producer_farmers_view', ['producer_id' => $producer['id']]));

        return view('producer.farmers', compact('farmers'));
    }

    public function detail_view($producer_id)
    {
        $producer = User::find($producer_id);
        $producer['review'] = $this->calculate_review($producer['id']);

        $recruitments = Recruitment::where('producer_id', $producer['id'])
            ->whereIn('status', ['completed', 'canceled'])
            ->get()
            ->toArray();

        $result = [];
        foreach ($recruitments as $recruitment) {
            if(Applicant::where('_applicants.recruitment_id', $recruitment['id'])->where('recruitment_review', '<>', 0)->count() == 0) continue;
            $recruitment['review'] = RecruitmentController::calculate_review($recruitment['id']);
            $recruitment['workplace'] = format_address($recruitment['post_number'], $recruitment['prefectures'], $recruitment['city'], $recruitment['workplace']);
            $recruitment['applicants'] = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
                ->where('_applicants.recruitment_id', $recruitment['id'])
                ->get();
            array_push($result, $recruitment);
        }
        $recruitments = $this->paginate($result)->setPath(route('producer_detail_view', ['producer_id' => $producer['id']]));

        return view('producer.detail', compact('recruitments'), ['producer' => $producer]);
    }

    public function profile_view()
    {
        $producer = User::find(Auth::user()->id);
        $producer['review'] = $this->calculate_review($producer['id']);

        return view('producer.profile', ['producer' => $producer]);
    }

    public function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $perPage = $perPage == null ? $this->page_count : $perPage;
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function calculate_review($producer_id)
    {
        $recruitments = Recruitment::where('producer_id', $producer_id)
            ->whereIn('status', ['completed', 'canceled'])
            ->get();

        $reviews = [];
        foreach ($recruitments as $recruitment) {
            if(Applicant::where('_applicants.recruitment_id', $recruitment['id'])->where('recruitment_review', '<>', 0)->count() == 0) continue;
            $recruitment_review = RecruitmentController::calculate_review($recruitment['id']);
            array_push($reviews, $recruitment_review);
        }

        if(count($reviews) > 0)
            $average = array_sum($reviews) / count($reviews);
        else
            $average = 0;

        return $average;
    }

    public function get_own_workers($producer_id)
    {
        $recruitments = Recruitment::where('producer_id', $producer_id)->select('id')->get();

        $worker_id_array = Applicant::whereIn('recruitment_id', $recruitments)
            ->pluck('worker_id')->toArray();

        $worker_id_array = array_unique($worker_id_array);

        $workers = User::whereIn('id', $worker_id_array)->get();

        return $workers;
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $request->validate(
            [
                'family_name' => 'required',
                'management_mode' => 'required',
            ],
            [
                'family_name.required' => '名前フィールドは必須です。',
                'management_mode.required' => '管理フォームが必要です',
            ]
        );

        User::find(Auth::user()->id)->update([
            'family_name'               => $data['family_name'],
            'name'                      => isset($data['name']) ? $data['name'] : null,
            'nickname'                  => isset($data['nickname']) ? $data['nickname'] : null,
            'management_mode'           => isset($data['management_mode']) ? $data['management_mode'] : null,
            'contact_address'           => isset($data['contact_address']) ? $data['contact_address'] : null,
            'post_number'                   => isset($data['post_number']) ? $data['post_number'] : null,
            'prefectures'                   => isset($data['prefectures']) ? $data['prefectures'] : null,
            'city'                   => isset($data['city']) ? $data['city'] : null,
            'address'                   => isset($data['address']) ? $data['address'] : null,
            'agency_name'               => isset($data['agency_name']) ? $data['agency_name'] : null,
            'agency_phone'              => isset($data['agency_phone']) ? $data['agency_phone'] : null,
            'insurance'                 => $data['insurance'],
            'other_insurance'           => isset($data['other_insurance']) ? $data['other_insurance'] : null,
            'product_name'              => isset($data['product_name']) ? $data['product_name'] : null,
            'appeal_point'              => isset($data['appeal_point']) ? $data['appeal_point'] : null,
            'cell_phone'                => isset($data['cell_phone']) ? $data['cell_phone'] : null,
            'emergency_phone'           => isset($data['emergency_phone']) ? $data['emergency_phone'] : null,
            'emergency_relation'        => isset($data['emergency_relation']) ? $data['emergency_relation'] : null,
            'job'                       => isset($data['job']) ? $data['job'] : null,
            'bio'                       => isset($data['bio']) ? $data['bio'] : null,
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

}
