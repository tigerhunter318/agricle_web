<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Applicant;
use App\Models\Favourite;
use App\Models\Message;
use App\Models\News;
use App\Models\Recruitment;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class NoticeController extends BaseController
{
    public $pusher;
    public function __construct()
    {
        $this->middleware('auth');

        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            array('cluster' => env('PUSHER_APP_CLUSTER'))
        );
    }

    public function get_all_news(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();
        $news = News::where('user_id', $user['id'])
            ->get();

        return $this->sendResponse($news, 'total news');
    }

    public function get_unread_news(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();
        $news = News::where('user_id', $user['id'])
            ->where('read', 0)
            ->get();

        return $this->sendResponse($news, 'unread news');
    }

    public function set_read(Request $request): JsonResponse
    {
        $news = News::where('id', $request->input('newsId'))
            ->update(['read' => 1 ]);

        return $this->sendResponse($news, 'read news');
    }

    public function delete_news(Request $request): JsonResponse
    {
        $id_array = $request->input('idArray');
        if(News::whereIn('id', $id_array)->delete()) return $this->sendResponse($id_array, 'deleted successfully');
        else return $this->sendError($id_array, 'delete failed');
    }
}
