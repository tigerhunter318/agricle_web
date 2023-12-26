<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\News;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $events = [];

        $news = News::where('user_id', Auth::user()->id)
            ->where('read', 0)
            ->get();

        if(Auth::user()->role == 'producer') {
            $recruitments = Recruitment::where('producer_id', Auth::user()->id)
                ->get();
            foreach ($recruitments as $recruitment) {
                if($recruitment['status'] == 'draft') {
                    $color = '#b61889';
                    $url = url('dashboard/producer/recruitments/'.$recruitment['id'].'/edit');
                }
                elseif($recruitment['status'] == 'collecting') {
                    $color = '#02bef5';
                    $url = route('recruitment_applicants_view', ['recruitment_id' => $recruitment['id']]);
                }
                elseif($recruitment['status'] == 'working') {
                    $color = '#172a89';
                    $url = route('recruitment_detail_view', ['recruitment_id' => $recruitment['id']]);
                }
                elseif($recruitment['status'] == 'canceled') {
                    $color = '#fa0428';
                    $url = route('recruitment_result_view', ['recruitment_id' => $recruitment['id']]);
                }
                elseif($recruitment['status'] == 'deleted') {
                    $color = '#c19f04';
                    $url = route('recruitment_result_view', ['recruitment_id' => $recruitment['id']]);
                }
                elseif($recruitment['status'] == 'completed') {
                    $color = '#0b5306';
                    $url = route('recruitment_result_view', ['recruitment_id' => $recruitment['id']]);
                }
                array_push($events, [
                    "title" => $recruitment['title'],
                    "start" => $recruitment['work_date_start'],
                    "end" => $recruitment['work_date_end'],
                    "color" => $color,
                    "url" => $url
                ]);
            }
        }
        else {
            $recruitments = Applicant::join('_recruitments', '_recruitments.id', '=', '_applicants.recruitment_id')
                ->select('_applicants.id as applicant_id', '_applicants.status as applicant_status', '_recruitments.status as recruitment_status', '_applicants.*', '_recruitments.*')
                ->where('_applicants.worker_id', Auth::user()->id)
                ->get();

            foreach ($recruitments as $recruitment) {
                $url = route('application_detail_view', ['applicant_id' => $recruitment['applicant_id']]);
                if($recruitment['recruitment_status'] == 'canceled') {
                    $color = '#fa0428';
                    $url = route('result_detail_view', ['applicant_id' => $recruitment['applicant_id']]);
                }
                elseif($recruitment['recruitment_status'] == 'deleted') {
                    $color = '#c19f04';
                    $url = route('application_detail_view', ['applicant_id' => $recruitment['applicant_id']]);
                }
                elseif($recruitment['applicant_status'] == 'waiting') {
                    $color = '#8058ef';
                }
                elseif($recruitment['applicant_status'] == 'approved') {
                    $color = '#069367';
                }
                elseif($recruitment['applicant_status'] == 'rejected') {
                    $color = '#fd8503';
                }
                elseif($recruitment['applicant_status'] == 'abandoned') {
                    $color = '#ac3d11';
                }
                elseif($recruitment['applicant_status'] == 'fired') {
                    $color = '#90ac11';
                }
                elseif($recruitment['applicant_status'] == 'finished') {
                    $color = '#219ff3';
                }
                array_push($events, [
                    "title" => $recruitment['title'],
                    "start" => $recruitment['work_date_start'],
                    "end" => $recruitment['work_date_end'],
                    "color" => $color,
                    "url" => $url
                ]);
            }
        }

        return view('dashboard', ['events' => $events, 'news' => $news]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
