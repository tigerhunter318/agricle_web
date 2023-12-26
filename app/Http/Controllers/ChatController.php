<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Favourite;
use App\Models\Recruitment;
use App\Models\User;
use Pusher\Pusher;

use App\Models\Message;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public $pusher;
    public $page_count = 10;
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

    public function recruitments_view()
    {
        if(Auth::user()->role == 'producer') {
            $recruitments = Recruitment::where('producer_id', Auth::user()->id)
                ->whereIn('status', ['working', 'collecting'])
                ->paginate($this->page_count);
        } else {
            $applied_recruitments_id = Applicant::where('worker_id', Auth::user()->id)->whereIn('status', ['approved', 'finished', 'abandoned'])->pluck('recruitment_id')->toArray();
            $applied_recruitments_id = array_unique($applied_recruitments_id);

            $recruitments = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
                ->select('users.id as user_id', 'users.*', '_recruitments.*')
                ->whereIn('_recruitments.id', $applied_recruitments_id)
                ->whereIn('status', ['working', 'collecting'])
                ->paginate($this->page_count);
        }

        return view('chats.recruitments', compact('recruitments'))
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function recruitment_chat_view($recruitment_id, $sender_id = '')
    {
        $recruitment = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('_recruitments.*', 'users.*', 'users.id as user_id', '_recruitments.id as recruitment_id')
            ->where('_recruitments.id', $recruitment_id)
            ->first();
        if(Auth::user()->role == 'producer') {
            $applicants = Applicant::join('users', 'users.id', '=', '_applicants.worker_id')
                ->select('users.id as user_id', 'users.*', '_applicants.*')
                ->where('recruitment_id', $recruitment_id)
                ->get();

            return view('chats.recruitment_producer', ['recruitment' => $recruitment, 'applicants' => $applicants, 'sender_id' => $sender_id]);
        }
        else {
            $producer = User::find($recruitment['producer_id']);

            return view('chats.recruitment_worker', ['recruitment' => $recruitment, 'producer' => $producer]);
        }
    }

    public function favourites_chat_view($sender_id = '')
    {
        if(Auth::user()->role == 'producer') {
            $favourites = ProducerController::get_own_workers(Auth::user()->id)->toArray();
//            $favourites = FavouriteController::get_favourites()->toArray();
        }
        else {
            $favourites = WorkerController::get_own_producers(Auth::user()->id)->toArray();
//            $favourite_recruitments = RecruitmentFavouriteController::get_favourites()->toArray();
//            $favourites = count($favourite_recruitments) > 0 ? User::whereIn('id', array_column($favourite_recruitments, 'producer_id'))->get()->toArray() : [];
        }

        $id_array = [];
        if(count($favourites) > 0) $id_array = array_column($favourites, 'id');
        $users = User::whereIn('id', $id_array)
            ->where(function($query) {
                $query->orWhere('name', 'like', '%'.request()->input('user_search', '').'%')
                    ->orWhere('email', 'like', '%'.request()->input('user_search', '').'%');
            })
            ->paginate($this->page_count);

        return view('chats.favourites', compact('users'), ['user_count' => $users->count(), 'sender_id' => $sender_id])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function fetchMessages(Request $request)
    {
        $id = $request->input('user_id');
        $skip = $request->input('skip');
        $recruitment_id = $request->input('recruitment_id');

        $messages = Message::orWhere(function($query) use ($id, $recruitment_id) {
            $query->where('sender_id', $id)
                ->where('recruitment_id', $recruitment_id)
                ->where('receiver_id', Auth::user()->id)
                ->where('owner_id', Auth::user()->id);
        })
            ->orWhere(function($query) use ($id, $recruitment_id) {
                $query->where('receiver_id', $id)
                    ->where('recruitment_id', $recruitment_id)
                    ->where('sender_id', Auth::user()->id)
                    ->where('owner_id', Auth::user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->skip($skip)
            ->take(10);

        foreach ($messages->get() as $message) {
            if(!$message['read'] && $message['sender_id'] == $id) {
                $this->pusher->trigger('chat', 'read-message', $message['message_id']);
            }
        }

        Message::where('sender_id', $id)
            ->where('recruitment_id', $recruitment_id)
            ->where('receiver_id', Auth::user()->id)
            ->where('owner_id', Auth::user()->id)
            ->update(['read' => 1]);

        $msg = [];

        foreach ($messages->orderBy('created_at')->get() as $message) {
            $message['sender'] = User::find($message['sender_id']);
            array_push($msg, $message);
        }
        echo json_encode($msg);
        return;
    }

    public function sendMessage(Request $request)
    {
        $request->validate(
            [
                'receiver_id' => 'required',
                'message' => 'required',
                'element_id' => 'required',
                'recruitment_id' => 'required',
            ],
            [
                'required' => 'この項目は必須です。',
            ]
        );

        $user = Auth::user();

        $message_id = Message::max('message_id') + 1;

        $send_message = Message::create([
            'sender_id' => $user['id'],
            'receiver_id' => $request->input('receiver_id'),
            'owner_id' => $user['id'],
            'recruitment_id' => $request->input('recruitment_id'),
            'message_id' => $message_id,
            'message' => $request->input('message'),
        ]);
        $send_message['element_id'] = $request->input('element_id');
        $send_message['sender'] = $user;

        $receive_message = Message::create([
            'sender_id' => $user['id'],
            'receiver_id' => $request->input('receiver_id'),
            'owner_id' => $request->input('receiver_id'),
            'recruitment_id' => $request->input('recruitment_id'),
            'message_id' => $message_id,
            'message' => $request->input('message'),
        ]);
        $receive_message['sender'] = $user;

        $this->pusher->trigger('chat', 'send-'.$user['id'], $send_message);
        $this->pusher->trigger('chat', 'receive-'.$request->input('receiver_id'), $receive_message);

        // for broadcast
        if($request->input('others') && count($request->input('others'))) {
            foreach ($request->input('others') as $other_receiver) {
                $send_message = Message::create([
                    'sender_id' => $user['id'],
                    'receiver_id' => $other_receiver,
                    'owner_id' => $user['id'],
                    'recruitment_id' => $request->input('recruitment_id'),
                    'message_id' => $message_id,
                    'message' => $request->input('message'),
                ]);
                $send_message['sender'] = $user;

                $receive_message = Message::create([
                    'sender_id' => $user['id'],
                    'receiver_id' => $other_receiver,
                    'owner_id' => $other_receiver,
                    'recruitment_id' => $request->input('recruitment_id'),
                    'message_id' => $message_id,
                    'message' => $request->input('message'),
                ]);
                $receive_message['sender'] = $user;

                $this->pusher->trigger('chat', 'send-'.$user['id'], $send_message);
                $this->pusher->trigger('chat', 'receive-'.$other_receiver, $receive_message);
            }
        }
    }

    public function setRead(Request $request)
    {
        $message_id = $request->input('message_id');
        Message::where('message_id', $message_id)
            ->update(['read' => 1]);
        $this->pusher->trigger('chat', 'read-message', $message_id);
    }

    public function clearMessage(Request $request)
    {
        $id = $request->input('receiver_id');
        $recruitment_id = $request->input('recruitment_id');

        $messages = Message::orWhere(function($query) use ($id, $recruitment_id) {
            $query->where('sender_id', $id)
                ->where('recruitment_id', $recruitment_id)
                ->where('receiver_id', Auth::user()->id)
                ->where('owner_id', Auth::user()->id);
        })
            ->orWhere(function($query) use ($id, $recruitment_id) {
                $query->where('receiver_id', $id)
                    ->where('recruitment_id', $recruitment_id)
                    ->where('sender_id', Auth::user()->id)
                    ->where('owner_id', Auth::user()->id);
            })
            ->delete();

        echo json_encode([ 'success' => true ]);
    }
}
