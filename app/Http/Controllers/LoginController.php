<?php

namespace App\Http\Controllers;

use App\Models\ModelUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(){

        $isLogin = Session::get('login');
        if($isLogin != null){
            return redirect('/beranda');
        }
        return view('login');
    }

    public function auth(Request $request){
        $getData = ModelUsers::where('email',$request->email)->first();

        if($getData == null){
            Session::flash('message', 'Mohon maaf, Akun tidak ditemukan.'); 
            Session::flash('icon', 'error'); 
            return redirect()->back()
                            ->withInput($request->input());
        }

        if(!Hash::check($request->password, $getData->password)){
            Session::flash('message', 'Mohon maaf, Email atau Password tidak sesuai.'); 
            Session::flash('icon', 'error');
            return redirect()->back()
                                ->withInput($request->input());
        }

        if($getData->role == 0){
            Session::flash('message', 'Mohon maaf, anda tidak memiliki akses ke halaman ini.'); 
            Session::flash('icon', 'error`');
            return redirect()->back()
                                ->withInput($request->input());
        }


        Session::put('dataUsers',$getData);
        Session::put('login', true);
        return redirect('/beranda');
    }

    public function loginApi(Request $request){
        $getData = ModelUsers::where('email',$request->email)->first();

        if($getData == null){
            return response()->json([
                'status' => false,
                'message' => "Mohon maaf akun tidak ditemukan.",
            ]);
        }

        if(!Hash::check($request->password, $getData->password)){
            Session::flash('message', 'Mohon maaf, Email atau Password tidak sesuai.'); 
            Session::flash('icon', 'error');
            return response()->json([
                'status' => false,
                'message' => "Mohon maaf, Email atau Password tidak sesuai.",
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => "successfully login.",
            'data' => $getData
        ]);



    }
}
