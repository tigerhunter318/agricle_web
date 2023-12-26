<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        $farms = [];
        $producers = User::where('role', 'producer')
            ->select('id', 'name', 'avatar', 'appeal_point')
            ->get()
            ->toArray();
        foreach ($producers as $producer) {
            $producer['avatar'] = $producer['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$producer['avatar']);
            $producer['review'] = ProducerController::calculate_review($producer['id']);
            array_push($farms, $producer);
        }
        $farms = array_reverse(Arr::sort($farms, function ($value) {
            return $value['review'];
        }));
        $farms = array_slice($farms, 0, 4);
        if(!empty(Auth::user())) {
            if(Auth::user()->role == 'admin') return redirect()->route('view_user_list');
            else return redirect()->route('dashboard');
        }
        return view('landing', ['farms' => $farms]);
    }
}
