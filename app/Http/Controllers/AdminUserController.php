<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'siswa')->get();
        return view('admin.users.index', compact('users'));
    }

    public function blacklist($id)
    {
        $user = User::findOrFail($id);
        $user->blacklist = !$user->blacklist;
        $user->save();

        $status = $user->blacklist ? 'di-blacklist' : 'diaktifkan kembali';
        return back()->with('success', "User berhasil {$status}");
    }
}