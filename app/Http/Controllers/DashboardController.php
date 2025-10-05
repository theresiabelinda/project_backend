<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'user' => Auth::user(),
            'message' => 'Welcome to your dashboard!'
        ]);
    }
}
