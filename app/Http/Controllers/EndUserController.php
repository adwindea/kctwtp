<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class EndUserController extends Controller
{
    public function index(){
        return view('enduser.idForm');
    }

    public function detailPel(Request $request){
        $idpel = $request->idpel;
        $pel = \App\Models\Pelanggan::where('idpel', $idpel)->first();
        return view('enduser.detailPel', $pel);
    }

    public function updateToken($id){
        $id = Crypt::decryptString($id);
        $data['pel'] = \App\Models\Pelanggan::where('id', $id)->first();
        return view('enduser.updateToken', $data);
    }

    public function submitUpgrade(Request $request){
        $id = $request->id;
        $img = $request->img;
        $id = Crypt::decrypt($id);
        $pel = \App\Models\Pelanggan::where('id', $id)->first();
        if(!empty($img)){
            $img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace('[removed]', '', $img);
			$img = str_replace(' ', '+', $img);
            $resource = base64_decode($img);
            // $prefix = Str::random(8);
            // $s3name = 'image/product/'.$prefix.time().'.png';
            $s3name = 'image/upgrade/'.$pel->idpel.'.png';
            Storage::disk('s3')->put($s3name, $resource);
            $filename = Storage::disk('s3')->url($s3name);
            // $filename = 'storage/image/rawmat/'.$prefix.time().'.png';
            // $path = storage_path().'/app/public/image/rawmat/'.$prefix.time().'.png';
            // file_put_contents($path, $resource);
        }
        $pel->img = $filename;
        $pel->upgraded = true;
        $pel->upgraded_at = date('Y-m-d H:i:s');
        $pel->krn = 3;
        $pel->vkrn = 43;
        $pel->save();
        return response()->json( array('success' => true) );
    }
    public function thanksPage(){
        return view('enduser.thanksPage');
    }
}
