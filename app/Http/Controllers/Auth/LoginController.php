<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RecruitmentController;
use App\Models\News;
use App\Models\Recruitment;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Pusher\Pusher;
use function PHPUnit\Framework\exactly;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectAfterLogout = '/';
    public $pusher;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);

        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            array('cluster' => env('PUSHER_APP_CLUSTER'))
        );
    }

    public function adminLoginView()
    {
        return view('auth.adminLogin');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate(
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

        if (Auth::attempt($credentials)) {
            if(Auth::user()->role == 'admin') {
                $request->session()->regenerate();

                return redirect()->route('view_user_list');
            }
            else {
                Auth::logout();
                Session::flush();
                return back()->withErrors([
                    'error' => 'あなたはマネージャーではありません。',
                ])->withInput();
            }
        }

        return back()->withErrors([
            'error' => '提供されたクレデンシャルは、当社の記録と一致しません。',
        ])->withInput();
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(
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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if(!Auth::user()->approved) {
                Auth::logout();
                Session::flush();
                return back()->withErrors([
                    'error' => 'ログインが承認されていません。',
                ]);
            }
            if(Auth::user()->role == 'producer') RecruitmentController::remind('old', $this->pusher);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'error' => '提供されたクレデンシャルは、当社の記録と一致しません。',
        ]);
    }

    /**
     * Logout, Clear Session, and Return.
     *
     * @return void
     */
    public function logout()
    {
        // $user = Auth::user();
        // Log::info('User Logged Out. ', [$user]);
        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

}
