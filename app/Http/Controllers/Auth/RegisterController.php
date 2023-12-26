<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\ConfirmMail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/activate';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'except' => 'logout',
        ]);
    }

    public function index($role = 'worker')
    {
        return view('auth.register')->with(['role' => $role]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'email'                 => 'required|email|max:255',
                'password'              => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
                'name' => 'required',
                'family_name' => 'required',
                'post_number' => 'required',
                'prefectures' => 'required',
                'city' => 'required',
                'address' => 'required',
                'role' => 'required',
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
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = User::where('email', $request->input('email'))
            ->first();
        if($user) {
            return redirect()->route('register_code', ['user_id' => $user['id']]);
        }

        $user = $this->create($request->all());
        $this->send_code($user['id']);

        return redirect()->route('register_code', ['user_id' => $user['id']]);
    }

    public function send_code($user_id)
    {
        $user = User::find($user_id);

        // create random 6 digital registration code
        $user['email_code'] = random_int(100000, 999999);
        $user->save();

        Mail::to($user['email'])
            ->send(new ConfirmMail($user));

        return redirect()->route('register_code', ['user_id' => $user['id']]);
    }

    protected function create(array $data)
    {
        if(isset($data['insurance']) && $data['insurance']) $data['insurance'] = 1;
        else $data['insurance'] = 0;
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $user->save();

        return $user;
    }

    public function register_code($user_id)
    {
        $user = User::find($user_id);

        return view('auth.register_code')->with(['user' => $user]);
    }

    public function register_code_check(Request $request, $user_id)
    {
        Validator::make(
            $request->all(),
            [
                'code-digit1' => ['required', 'digits:1'],
                'code-digit2' => ['required', 'digits:1'],
                'code-digit3' => ['required', 'digits:1'],
                'code-digit4' => ['required', 'digits:1'],
                'code-digit5' => ['required', 'digits:1'],
                'code-digit6' => ['required', 'digits:1'],
            ],
            [
                'required' => 'Verification code is invalid.'
            ]
        )->validate();

        $email_code =
            $request->input('code-digit1').
            $request->input('code-digit2').
            $request->input('code-digit3').
            $request->input('code-digit4').
            $request->input('code-digit5').
            $request->input('code-digit6');

        $user = User::find($user_id);

        if($user['email_code'] == $email_code) {
            $user['email_verified_at'] = date('Y-m-d H:i:s');
            $user->save();
            return redirect()->route('login');
        }
        else {
            return redirect()->back()->with('msg', 'Verification code is invalid.');
        }
    }

}
