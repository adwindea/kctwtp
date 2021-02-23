<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DataTables;

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
    public function unauthorizedAccess(){
        return view('unauthorized');
    }
    public function userList(){
        return view('userList');
    }
    public function userListTable(Request $request){
        $status = $request->input('status');
        $pengguna = \App\Models\User::select('*');
        if(isset($status)){
            $pengguna = $pengguna->where('active', $status);
        }
        $pengguna = $pengguna->get();
        return Datatables::of($pengguna)
        ->addColumn('status', function ($user) {
            $status = '<span class="badge badge-warning">Tidak Aktif</span>';
            if($user->active == 1){
                $status = '<span class="badge badge-success">Aktif</span>';
            }
            return $status;
        })
        ->addColumn('action', function($user){
            $btn = '';
            if($user->active == 0){
                $btn = '<btn class="btn btn-success btn-sm" onclick="confirmModal(`'.$user->name.'`,`'.Crypt::encrypt($user->id).'`)"><span class="fa fa-check"></span></btn>';
            }
            return $btn;
        })
        ->editColumn('created_at', function($user){
            return date('d M Y H:i:s', strtotime($user->created_at));
        })
        ->removeColumn('id')
        ->addIndexColumn()
        ->rawColumns(['status', 'action'])
        ->make(true);
    }
    public function userActivation(Request $request){
        $user_id = $request->input('id');
        $user_id = Crypt::decrypt($user_id);
        $user = \App\Models\User::where('id', $user_id)->first();
        $user->active = true;
        $user->save();
        return response()->json( array('success' => true) );
    }
}
