<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\RolePermission;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.user.index',compact('users'));
    }

    public function add()
    {
        $roles = Role::orderBy('id','asc')->get();
        return view('admin.user.add',compact('roles'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $this->createUserRole($request->roles,$user->id);

        return redirect()->route('user')->with('success', true)->with('message','User created successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = DB::select("select roles.id, roles.name,roles.description,user_roles.role_id
                from roles left join 
                (select user_roles.user_id,user_roles.role_id from user_roles where user_roles.user_id = $id) as user_roles
                on roles.id = user_roles.role_id
                order by roles.id asc
                ");
        return view('admin.user.edit',compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->name  = $request->name;
        $user->email = $request->email;
        if(!(is_null($request->password)) || (str_replace(' ', '', $request->password) !='')){
            $user->password = $request->password;
        }
        $user->save();

        $this->createUserRole($request->roles,$request->id);
        return redirect()->route('user')->with('success', true)->with('message','User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        User::destroy($request->id);
        return redirect()->route('user');
    }

    function createUserRole($roles,$id){
        UserRole::where('user_id', $id)->delete();
        if($roles != null){
            foreach($roles as $role){
                $userRole = new UserRole();
                $userRole->role_id = $role;
                $userRole->user_id = $id;
                $userRole->save();
            }
        }
    }
}
