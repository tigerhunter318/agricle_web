<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManageController extends Controller
{
    public $page_count = 10;

    public function view_user_list(Request $request, $role = '*', $approved = '*')
    {
        $users = User::where('role', '<>', 'admin')
            ->when($role != '*', function($query) use($role) {
                $query->where('role', $role);
            })
            ->when($approved != '*', function($query) use($approved) {
                $query->where('approved', $approved);
            })
            ->paginate($this->page_count);

        return view('admin.users.list', compact('users'), ['role' => $role, 'approved' => $approved])
            ->with('i', (request()->input('page', 1) - 1) * $this->page_count);
    }

    public function set_user_approve(Request $request)
    {
        return User::find($request->input('id'))
            ->update(['approved' => $request->input('approved')]);
    }

    public function view_user_detail(Request $request, $id)
    {
        $user = User::find($id);

        return view('admin.users.detail', ['user' => $user]);
    }
}
