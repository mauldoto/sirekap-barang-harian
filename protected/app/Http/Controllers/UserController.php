<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('username', '!=', 'superadmin')->get();
        return view('contents.user.index', compact('users'));
    }

    public function getDetail($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return;
        }

        return response()->json([
            'status' => 'success',
            'data'   => $user
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required|string',
            'username'  => 'required|unique:users',
            'email'     => 'required|email|unique:users',
            'role'      => 'required|in:jpn,admin,finance',
            'password'  => 'required_with:ulangi_password',
            'ulangi_password'  => 'required_with:password',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $createUser = User::create([
            'name'      => $request->nama,
            'email'     => $request->email,
            'username'  => $request->username,
            'role'      => $request->role,
            'password'  => bcrypt($request->password),
        ]);

        if (!$createUser) {
            return back()->withErrors(['Gagal create user']);
        }

        return back()->with(['success' => 'Create user berhasil']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required|string',
            'username'  => 'required',
            'email'     => 'required|email',
            'role'      => 'required|in:jpn,admin,finance',
            'password'  => 'required_with:ulangi_password',
            'ulangi_password'  => 'required_with:password',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $checkUsername = User::where('username', $request->username)->whereNot('id', $id)->first();
        $checkEmail = User::where('email', $request->email)->whereNot('id', $id)->first();

        if ($checkUsername) {
            return back()->withErrors(['Username sudah ada']);
        }

        if ($checkEmail) {
            return back()->withErrors(['Email sudah ada']);
        }

        $createUser = User::updateOrCreate(
            ['id' => $id],
            [
                'name'      => $request->nama,
                'email'     => $request->email,
                'username'  => $request->username,
                'role'      => $request->role,
                'password'  => bcrypt($request->password),
            ]
        );

        if (!$createUser) {
            return back()->withErrors(['Gagal Update user']);
        }

        return back()->with(['success' => 'Update user berhasil']);
    }

    public function delete() {}
}
