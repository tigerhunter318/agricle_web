<?php

namespace App\Http\Controllers;

use App\Models\News;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getNews()
    {
        $news = News::where('user_id', Auth::user()->id)
            ->where('read', 0)
            ->get();

        echo json_encode($news);
    }

    public function create($user_id, $type, $message, $link)
    {
        $news = News::create([
            'user_id' => $user_id,
            'type' => $type,
            'message' => $message,
            'link' => $link
        ]);

        return $news;
    }

    public function setReadAll(Request $request)
    {
        News::where('type', $request->input('type'))
            ->where('user_id', Auth::user()->id)
            ->update(['read' => 1]);

        echo true;
    }

    public function setRead(Request $request)
    {
        News::find($request->input('id'))
            ->update(['read' => 1]);

        echo true;
    }

    public function clearNews(Request $request)
    {
        News::find($request->input('id'))
            ->delete();

        echo true;
    }
}
