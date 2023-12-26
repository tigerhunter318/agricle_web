<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ProducerController;
use App\Models\Applicant;
use App\Models\Favourite;
use App\Models\RecruitmentFavourite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Image;

use App\Models\User;

class UserController extends BaseController
{
    public function get_profile(Request $request): JsonResponse
    {
        $userData =  auth('sanctum')->user();
        $user = User::find($userData->id);

        return $this->sendResponse($user, 'user profile data');
    }

    public function get_producer_profile(Request $request, $producer_id): JsonResponse
    {
        $producer = User::find($producer_id);
        $producer['review'] = ProducerController::calculate_review($producer_id);
        $producer['recruitments'] = RecruitmentController::get_history($producer['role'], $producer['id']);

        return $this->sendResponse($producer, 'producer profile data');
    }

    public function get_worker_profile(Request $request, $worker_id): JsonResponse
    {
        $worker = User::find($worker_id);
        $worker['review'] = WorkerController::calculate_review($worker_id);
        $worker['recruitments'] = RecruitmentController::get_history($worker['role'], $worker['id']);

        return $this->sendResponse($worker, 'worker profile data');
    }

    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->input('data'), true);
        $authUser = auth('sanctum')->user();

        $validator = Validator::make(
            $data,
            [
                'family_name' => 'required',
                'name' => 'required',
                'nickname' => $authUser['role'] == 'worker' ? 'required' : '',
                'post_number' => 'required',
                'prefectures' => 'required',
                'city' => 'required',
                'address' => 'required',

                'management_mode' => $authUser['role'] == 'producer' ? 'required' : ''
            ],
            [
                'required' => 'この項目は必須です。',
            ]
        );
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }

        $user = User::find($authUser['id']);
        $folderPath = 'avatars/';
        if($request->has('image')){
            // delete old image
            if(User::where('avatar', $user->avatar)->count() == 1) {
                Storage::disk('public')->delete('avatars/'.$user->avatar);
            }

            $image = $request->file('image');
            $exte = $image->extension();
            $file = uniqid().".".$exte;
            $path = $image->move($folderPath, $file);
        }else{
            $file = $user['avatar'];
        }

        $data['avatar'] = $file;
        $data['birthday'] = date_format(date_create($data['birthday']), 'Y-m-d');

        $user->update($data);
        $user->refresh();

        return $this->sendResponse($user, 'User data');
    }

    public function get_favourite_user()
    {
        $favourites = Favourite::join('users', 'users.id', '=', '_favourites.favourite_id')
            ->where('user_id', Auth::user()->id)
            ->get();

        return $this->sendResponse($favourites, 'favourites');
    }

    public function get_favourite_recruitment()
    {
        $authUser = auth('sanctum')->user();

        $favourites = RecruitmentFavourite::join('_recruitments', '_recruitments.id', '=', '_recruitment_favourites.recruitment_id')
            ->join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('users.*')
            ->where('_recruitment_favourites.user_id', $authUser['id'])
            ->get();

        return $this->sendResponse($favourites, 'favourites');
    }

    public function set_favourite_user(Request $request)
    {
        $authUser = auth('sanctum')->user();
        $favourite_id = $request->input('favourite_id');
        if(Favourite::where('user_id', $authUser['id'])->where('favourite_id', $favourite_id)->count()>0) {
            Favourite::where('user_id', $authUser['id'])
                ->where('favourite_id', $favourite_id)
                ->delete();
        }
        else {
            Favourite::create([
                'user_id' => $authUser->id,
                'favourite_id' => $favourite_id
            ]);
        }

        return $this->sendResponse(1, 'set favourite');
    }
}
