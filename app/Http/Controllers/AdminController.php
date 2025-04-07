<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::all();
        $transactions = Transaction::orderBy('transaction_timestamp', 'desc')->get();

        return view('admin.dashboard', compact('users', 'transactions'));
    }
}
