<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Show all users
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

        public function promote(User $user)
        {
            $user->update(['role' => 'admin']);
            return back()->with('success', "{$user->name} wurde zum Admin befördert.");
        }

        public function revoke(User $user)
        {
            $user->update(['role' => 'user']);
            return back()->with('success', "{$user->name} wurden die Admin-Rechte entzogen.");
        }
    }

