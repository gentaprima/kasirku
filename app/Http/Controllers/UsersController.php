<?php

namespace App\Http\Controllers;

use App\Models\ModelUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    public function auth(Request $request){
        $email = $request->email;
        $password = $request->password;

        if($email == null){
            return response()->json([
                'success' => false,
                'message' => "Mohon maaf, Email tidak boleh kosong."
            ]);
        }
        if($password == null){
            return response()->json([
                'success' => false,
                'message' => "Mohon maaf, Password tidak boleh kosong."
            ]);
        }

        $checkEmail = DB::table('tbl_users')
                            ->where('email','=',$email)->first();
        if($checkEmail == null){
            return response()->json([
                'success' => false,
                'message' => "Mohon maaf, Email tidak ditemukan."
            ]);
        }

        if(password_verify($password,$checkEmail->password)){
            return response()->json([
                'success' => true,
                'message' => "Success",
                'data'    => $checkEmail
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Mohon maaf, Password tidak sesuai."
            ]);
        }
    }

    public function showKaryawan(Request $request){
        if ($request->search != '') {
            $dataUsers = DB::table('tbl_users')
                    ->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('tgl_lahir', 'like', '%' . $request->search . '%')
                    ->orWhere('jenis_kelamin', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_telepon', 'like', '%' . $request->search . '%')
                    ->paginate(10);
        } else {
            $dataUsers = DB::table('tbl_users')
                          ->paginate(10);
        }

        return response()->json([
            'success'   => true,
            'data'      => $dataUsers
        ]);
    }

    public function store(Request $request){
        ModelUsers::create([
            'email'     => $request->email,
            'nama_lengkap'  => $request->fullName,
            'jenis_kelamin'         => $request->gender,
            'nomor_telepon'        => $request->phoneNumber,
            'password'      => Hash::make('1234')
        ]);

        Session::flash('message', 'Karyawan berhasil ditambahkan.');
        Session::flash('icon', 'success');
        return redirect()->back()
            ->withInput($request->input());
    }

    public function update(Request $request,$id){
        $data = ModelUsers::find($id);
        $data->email = $request->email;
        $data->nama_lengkap = $request->fullName;
        $data->jenis_kelamin = $request->gender;
        $data->nomor_telepon = $request->phoneNumber;
        $data->save();

        Session::flash('message', 'Karyawan berhasil diperbarui.');
        Session::flash('icon', 'success');
        return redirect()->back()
            ->withInput($request->input());
    }

    public function destroy($id){
        $data = ModelUsers::find($id);
        $data->delete();
        Session::flash('message', 'Karyawan berhasil dihapus.');
        Session::flash('icon', 'success');
        return redirect()->back();
    }
}
