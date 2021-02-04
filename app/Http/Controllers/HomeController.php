<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $response = \Telegram::getMe();
        dd($response);
        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();
        return view('home');
    }
}
