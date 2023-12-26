<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;

class AuthController extends BaseController
{
    use AuthenticatesUsers;

    public function user(Request $request): JsonResponse
    {
        $userData =  auth('sanctum')->user();

        return $this->sendResponse($userData, 'User data');
    }

    public function signIn(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'メールフィールドは必須です。',
                'email.email' => 'メールは有効なメールアドレスである必要があります。',
                'password.required' => 'パスワードフィールドは必須です。',
            ]
        );

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $authUser = auth('sanctum')->user();
            $success['token'] =  $authUser->createToken('auth_token', [$authUser->getAttribute('role').'Role'])->plainTextToken;
            $success['name'] =  $authUser->getAttribute('name');
            $success['role'] =  $authUser->getAttribute('role');
            $success['email'] =  $authUser->getAttribute('email');

            return $this->sendResponse($success, 'User signed in');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function signUp(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'email'                 => 'required|email|max:255|unique:users',
                'password'              => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
                'family_name' => 'required',
                'name' => 'required',
                'nickname' => 'required',
                'post_number' => 'required',
                'prefectures' => 'required',
                'city' => 'required',
                'address' => 'required',
                'role' => 'required',
                'management_mode' => $input['role'] == 'producer' ? 'required' : ''
            ],
            [
                'email.required' => 'メールフィールドは必須です。',
                'email.unique' => 'メールはすでに取られています。',
                'email.email' => 'メールは有効なメールアドレスである必要があります。',
                'email.max' => '電子メールは255文字を超えてはなりません。',

                'password.required' => 'パスワードフィールドは必須です。',
                'password.min' => 'パスワードは6文字以上である必要があります。',
                'password.max' => 'パスワードは30文字以内にする必要があります。',
                'password.confirmed' => 'パスワードの確認が一致しません。',

                'password_confirmation.required' => 'パスワード確認フィールドは必須です。',
                'password_confirmation.same' => 'パスワードの確認とパスワードは一致している必要があります。',

                'family_name.required' => '名前フィールドは必須です。',
                'post_number.required' => '郵便番号が空です。',
                'prefectures.required' => '都道府県が空です。',
                'city.required' => '市区郡が空です。',
                'address.required' => '住所が空です。',
                'gender.required' => '性別フィールドは必須です。',
                'birthday.required' => '生年月日フィールドが必要です。',
                'management_mode.required' => '管理フォームが必要です',
                'required' => 'この項目は必須です。',
            ]
        );

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();
        $input['birthday'] = date_format(date_create($input['birthday']), 'Y-m-d');
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('auth_token', [$user->getAttribute('role')])->plainTextToken;
        $success['name']  =  $user->getAttribute('name');
        $success['email'] =  $user->getAttribute('email');

        return $this->sendResponse($success, 'User created successfully.');
    }

}
