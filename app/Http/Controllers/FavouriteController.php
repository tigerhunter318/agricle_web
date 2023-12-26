<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function set_favourite(Request $request)
    {
        $favourite_id = $request->input('favourite_id');

        Favourite::create([
            'user_id' => Auth::user()->id,
            'favourite_id' => $favourite_id
        ]);

        echo true;
    }

    public function unset_favourite(Request $request)
    {
        $favourite_id = $request->input('favourite_id');

        Favourite::where('user_id', Auth::user()->id)
            ->where('favourite_id', $favourite_id)
            ->delete();

        echo true;
    }

    public function is_favourite($favourite_id)
    {
        return Favourite::where('user_id', Auth::user()->id)
            ->where('favourite_id', $favourite_id)
            ->count() > 0;
    }

    public function get_favourites()
    {
        $favourites = Favourite::join('users', 'users.id', '=', '_favourites.favourite_id')
            ->where('user_id', Auth::user()->id)
            ->get();

        return $favourites;
    }
}
