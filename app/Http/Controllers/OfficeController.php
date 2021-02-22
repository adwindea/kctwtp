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
        $data['user'] = \App\Models\User::all();
        return view('office.dataPelanggan', $data);
    }
    public function dataPelangganTable(Request $request){
        $status = $request->input('status');
        $krn = $request->input('krn');
        $user = $request->input('user');
        $confirmed_date = $request->input('confirmed_date');
        $upgraded_date = $request->input('upgraded_date');
        $pelanggan = \App\Models\Pelanggan::select('pelanggans.*', 'users.name as username')
        ->leftJoin('users', 'pelanggans.confirmed_by', '=', 'users.id');
        if(isset($status)){
            if($status == 0){
                $pelanggan = $pelanggan->where('upgraded', '=', 0)->where('confirmed', '=', 0);
            }elseif($status == 1){
                $pelanggan = $pelanggan->where('upgraded', '=', 1)->where('confirmed', '=', 0);
            }elseif($status == 2){
                $pelanggan = $pelanggan->where('upgraded', '=', 1)->where('confirmed', '=', 1);
            }
        }
        if(!empty($krn)){
            $pelanggan = $pelanggan->where('vkrn', '=', $krn);
        }
        if(!empty($user)){
            $user = Crypt::decrypt($user);
            $pelanggan = $pelanggan->where('confirmed_by', '=', $user);
        }
        if(!empty($confirmed_date['start'])){
            $pelanggan = $pelanggan->where('confirmed_at', '>=', $confirmed_date['start']);
        }
        if(!empty($confirmed_date['end'])){
            $pelanggan = $pelanggan->where('confirmed_at', '<=', $confirmed_date['end']);
        }
        if(!empty($upgraded_date['start'])){
            $pelanggan = $pelanggan->where('upgraded_at', '>=', $upgraded_date['start']);
        }
        if(!empty($upgraded_date['end'])){
            $pelanggan = $pelanggan->where('upgraded_at', '<=', $upgraded_date['end']);
        }
        $pelanggan = $pelanggan->get();
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
        ->editColumn('kct1', function($pel){
            $kct1 = '';
            if($pel->kct1 == 1){
                $kct1 = '<span class="fa fa-check"></span>';
            }
            return $kct1;
        })
        ->editColumn('kct2', function($pel){
            $kct2 = '';
            if($pel->kct2 == 1){
                $kct2 = '<span class="fa fa-check"></span>';
            }
            return $kct2;
        })
        ->addColumn('location', function($pel){
            $location = '';
            if(!empty($pel->lat) and !empty($pel->long)){
                $location = '<a class="btn btn-sm btn-success" href="https://www.google.com/maps/search/?api=1&amp;query='.$pel->lat.','.$pel->long.'" target="_blank" title="Open Google Maps"><i class="fa fa-map"></i></a>';
            }
            return $location;
        })
        ->removeColumn('id')
        ->addIndexColumn()
        ->rawColumns(['status', 'img', 'kct1', 'kct2', 'location'])
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
