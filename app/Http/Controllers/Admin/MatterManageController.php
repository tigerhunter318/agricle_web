<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RecruitmentController;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\Request;

class MatterManageController extends Controller
{
    public $page_count = 8;

    public function view_matter_list(Request $request)
    {
        $matters = Recruitment::join('users', 'users.id', '=', '_recruitments.producer_id')
            ->select('_recruitments.*', 'users.avatar', 'users.family_name')
            ->where('status', '<>', 'draft')
            ->paginate($this->page_count);

        return view('admin.matters.list', compact('matters'))
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function view_matter_detail(Request $request, $id)
    {
        $matter = RecruitmentController::get_recruitment_info($id);

        return view('admin.matters.detail', ['matter' => $matter]);
    }

    public function set_matter_approve(Request $request)
    {
        return Recruitment::find($request->input('id'))
            ->update(['approved' => $request->input('approved')]);
    }
}
