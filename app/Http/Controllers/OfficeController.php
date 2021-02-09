<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;

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
    public function dataPelangganTable(){
        $pelanggan = \App\Models\Pelanggan::all();
        return Datatables::of($pelanggan)
        ->addColumn('status', function ($pel) {
            $status = '<span class="badge badge-info">Idle</span>';
            if($pel->upgraded == 1 && $pel->confirmed == 0){
                $status = '<span class="badge badge-warning">Upgraded</span>';
            }else if($pel->upgraded == 1 && $pel->confirmed == 1){
                $status = '<span class="badge badge-success">Confirmed</span>';
            }
            return $status;
        })
        ->removeColumn('id')
        ->addIndexColumn()
        ->rawColumns(['status'])
        ->make(true);
    }
}
