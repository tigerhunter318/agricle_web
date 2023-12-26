<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\News;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public $page_count = 10;

    ##############################################################################
    ############################   NEWS FUNCTIONS   ##############################
    ##############################################################################

    public function news_view()
    {
        $news = News::where('user_id', Auth::user()->id)
            ->orderByDesc('created_at')
            ->paginate($this->page_count);

        return view('news', compact('news'));
    }

    public function getNews()
    {
        $news = News::where('user_id', Auth::user()->id)
            ->orderByDesc('created_at')
            ->take(10)
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

    public function setReadAllNews(Request $request)
    {
        News::where('type', $request->input('type'))
            ->where('user_id', Auth::user()->id)
            ->where('owner_id', Auth::user()->id)
            ->update(['read' => 1]);

        echo true;
    }

    public function setReadNews(Request $request)
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

    public function clearAllNews()
    {
        $user_id = Auth::user()->id;

        News::where('user_id', $user_id)
            ->delete();

        echo true;
    }



    ##############################################################################
    ##########################   MESSAGE FUNCTIONS   #############################
    ##############################################################################

    public function messages_view()
    {
        return view('messages');
    }

    public function search_messages(Request $request)
    {
        // request params: type(send, receive), isRead(1, 0), userId, keyword
        $data = $request->all();

        $messages = Message::where('owner_id', Auth::user()->id)
            ->where(function($query) use($data){
                if(isset($data['type'])) {
                    if($data['type'] == 'send') {
                        if(isset($data['userId']) && !!$data['userId']) $query->where('sender_id', Auth::user()->id)->where('receiver_id', $data['userId']);
                        else $query->where('sender_id', Auth::user()->id);
                    }
                    elseif($data['type'] == 'receive'){
                        if(isset($data['userId']) && !!$data['userId']) $query->where('receiver_id', Auth::user()->id)->where('sender_id', $data['userId']);
                        else $query->where('receiver_id', Auth::user()->id);
                    }
                }
            })
            ->where(function($query) use($data){
                if(isset($data['isRead'])) {
                    if($data['isRead']) $query->where('read', 1);
                    elseif(!$data['isRead']) $query->where('read', 0);
                }
            })
            ->where(function($query) use($data){
                if(isset($data['keyword']) && !!$data['keyword']) $query->where('message', 'like', "%".$data['keyword']."%");
            })
            ->paginate($this->page_count);

        foreach ($messages as $message) {
            $message['sender'] = User::find($message['sender_id']);
            $message['receiver'] = User::find($message['receiver_id']);
        }

        return view('messageList', compact("messages"))
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function getMsg()
    {
        $news = Message::where('receiver_id', Auth::user()->id)
            ->where('owner_id', Auth::user()->id)
            ->where('read', 0)
            ->get();

        foreach ($news as $msg) {
            $msg['sender'] = User::find($msg['sender_id']);
        }

        echo json_encode($news);
    }

    public function is_double($user_id, $type, $message, $link)
    {
        return News::where('user_id', $user_id)
                ->where('type', $type)
                ->where('message', $message)
                ->where('link', $link)
                ->count() > 0;
    }

    public function setReadAllMsg(Request $request)
    {
        Message::where('receiver_id', Auth::user()->id)
            ->where('owner_id', Auth::user()->id)
            ->update(['read' => 1]);

        echo true;
    }

    public function setReadMsg(Request $request)
    {
        Message::find($request->input('id'))
            ->update(['read' => 1]);

        echo true;
    }

    public function clearAllMsg(Request $request)
    {
        Message::find($request->input('id'))
            ->delete();

        echo true;
    }
}
