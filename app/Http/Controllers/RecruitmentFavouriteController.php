<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\RecruitmentFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitmentFavouriteController extends Controller
{
    public function set_favourite(Request $request)
    {
        $recruitment_id = $request->input('recruitment_id');

        RecruitmentFavourite::create([
            'user_id' => Auth::user()->id,
            'recruitment_id' => $recruitment_id
        ]);

        echo true;
    }

    public function unset_favourite(Request $request)
    {
        $recruitment_id = $request->input('recruitment_id');

        RecruitmentFavourite::where('user_id', Auth::user()->id)
            ->where('recruitment_id', $recruitment_id)
            ->delete();

        echo true;
    }

    public function is_favourite($recruitment_id)
    {
        return RecruitmentFavourite::where('user_id', Auth::user()->id)
                ->where('recruitment_id', $recruitment_id)
                ->count() > 0;
    }

    public function get_favourites()
    {
        $favourites = RecruitmentFavourite::join('_recruitments', '_recruitments.id', '=', '_recruitment_favourites.recruitment_id')
            ->where('user_id', Auth::user()->id)
            ->get();

        return $favourites;
    }
}
