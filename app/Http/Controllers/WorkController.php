<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class WorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('human');
    }
    
    public function work(Request $request){
        if($request->ajax()){
            if(Session::get('role') == 'kasir'){
                $user = User::find(Session::get('userId'));
                $result = $user->stt_aktif;
                Session::put('work',$result);
            }
            return response()->json(['result' => $result]);
        }
    }

    public function update(Request $request){
        if($request->ajax()){
            if(Session::get('role') == 'kasir'){
                $user = User::find(Session::get('userId'));
                $result = $user->stt_aktif;
                if($result == 1){
                    $result = 0;
                }
                else{
                    $result = 1;
                }
                $user->stt_aktif = $result;
                $user->save();
                Session::put('work',$result);
            }
            return response()->json(['result' => $result]);
        }
    }
}
