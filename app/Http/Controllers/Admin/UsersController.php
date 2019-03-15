<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\UsuarioFueCreado;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = new User;
        $this->authorize('view', $user);

        $users = User::Permitidos()->get();
        //$users = User::get();

        if (request()->wantsJson())
            return $users;

        return redirect()->route('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = new User;

        $this->authorize('create', $user);

        $roles = Role::with('permissions')->get(); // para listar también los permisos
        $permisos = Permission::pluck('name','id');

        $accion = "post";

        return view('admin.users.edit', compact('user','roles','permisos','accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //  \Log::info('storee...');
        // return $request;


        // $this->authorize('create', new User);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        // $data['password']= str_random(8);
        //$data = $request->validated();

        //     \Log::info('enviando mensaje...');

        if (isset($data['password']))
            $data['password'] = Hash::make($data['password']);
       else
            $data['password'] = Hash::make('12345678');

        $user = User::create($data);

        //$user->assignRole($request->roles);
        //$user->givePermissionTo($request->permissions);

        // enviar email
        //UsuarioFueCreado::dispatch($user, $data['password']);
        if (request()->wantsJson())
            return ['status'=>'S','user'=>$user, 'msg' => 'Usuario creado'];

        return redirect()->route('admin.users.index')->withFlash('El usuario ha sido creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //$this->authorize('view', $user);

        //return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        $this->authorize('update', $user);

        $roles = Role::with('permissions')->get(); // para listar también los permisos
        //$permisos = Permission::pluck('name','id');
        $permisos = Permission::get();

        $role_user=[];
        $data = User::find($user->id)->roles()->get();
        foreach($data as $role){
            $role_user[]=$role->name;
        }

        $permisos_user=[];
        $data = User::find($user->id)->permissions()->get();
        foreach($data as $permiso){
            $permisos_user[]=$permiso->name;
        }

        if (request()->wantsJson())
            return [
                'user'          =>$user,
                'role_user'     => $role_user,
                'permisos'      =>$permisos,
                'permisos_user' => $permisos_user];

        return redirect()->route('home');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        //return $request;

        $data = $request->validated();

        if($data['blocked'] && !isset($data['blocked_at']))
            $data['blocked_at'] = Carbon::now()->toDateTimeString();
        elseif($data['blocked']===false){
            $data['blocked_at'] = null;
        }


        //return $data;
        //\Log::info('enviando mensaje...'.$data['deleted_at']);

        if (isset($data['password']))
           $data['password'] = Hash::make($data['password']);

        $user->update($data);

        if (request()->wantsJson())
            return ['status'=>'U','user'=>$user, 'msg' => 'EL Usuario ha sido modficado'];
            // return response()->json([
            //     'status'=>'U',
            //     'user'=>$user,
            //     'msg' => 'Usuario modficado']);


            //return response('ok',200);

        //return back()->withFlash('Usuario actualizado');
        return redirect()->route('admin.users.edit', $user)
             ->withFlash('El usuario ha sido actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user de esta manera inyectamos el modelo y de esta forma
     *          laravel busca el usuario automáticamente
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //abort(404, 'dfasdfa');

        $this->authorize('delete', $user);

        $user->delete();

        $msg = 'El usuario ha sido eliminado';

        if (request()->wantsJson()){
            return response()->json(User::Permitidos()->get());
        }

        return redirect()->route('admin.users.index')->withFlash($msg);
    }
}
