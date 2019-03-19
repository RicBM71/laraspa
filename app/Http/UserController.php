<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
	public function show(Request $request)
	{
        return $request->user();

    }

    public function dash(Request $request){

        $authUser = $request->user();

        $admin = ($request->user()->hasRole('Root') || $request->user()->hasRole('Admin'));

        $role=[
            'root'=>$request->user()->hasRole('Root'),
            'admin'=>$request->user()->hasRole('Admin'),
        ];

        if (request()->wantsJson())
            return (compact('authUser','role'));

    }


	public function updatePassword(Request $request)
	{
		$rules = [
			'new_password'         => 'min:8|required',
			'password_confirmation' => 'required|same:new_password'
		];

		$this->validate($request, $rules);

        $user = $request->user();

		$user->password = Hash::make($request->input('password'));
		$user->saveOrFail();

        return response('Se ha modificado la contraseña', 200);
		//return response()->json(compact('user'));
	}
}
