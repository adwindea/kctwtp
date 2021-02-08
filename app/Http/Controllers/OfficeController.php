<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(){
        return view('office.dashboard');
    }
    public function dataPelanggan(){
        return view('office.dataPelanggan');
    }
}
