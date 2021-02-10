<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DataTables;
use Auth;

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
            $status = '<span class="badge badge-info">Not Updated</span>';
            if($pel->upgraded == 1 && $pel->confirmed == 0){
                $status = '<span class="badge badge-warning" onclick="confirmModal(`'.$pel->img.'`,`'.Crypt::encrypt($pel->id).'`)">Updated</span>';
            }else if($pel->upgraded == 1 && $pel->confirmed == 1){
                $status = '<span class="badge badge-success">Confirmed</span>';
            }
            return $status;
        })
        ->editColumn('img', function($pel){
            $img = '';
            if(!empty($pel->img)){
                $img = '<a href="'.$pel->img.'" target="_blank"><img src="'.$pel->img.'" style="max-height: 40px;"></img></a>';
            }
            return $img;
        })
        ->removeColumn('id')
        ->addIndexColumn()
        ->rawColumns(['status', 'img'])
        ->make(true);
    }
    public function confirmUpgrade(Request $request){
        $id = $request->id;
        $mode = $request->mode;
        $id = Crypt::decrypt($id);
        $pel = \App\Models\Pelanggan::where('id', $id)->first();
        if($mode == 0){
            $pel->upgraded = false;
            $pel->img = null;
        }else if($mode == 1){
            $pel->confirmed = true;
            $pel->confirmed_by = Auth::user()->id;
            $pel->confirmed_at = date('Y-m-d H:i:s');
        }
        $pel->save();
        return response()->json( array('success' => true) );
    }
}
