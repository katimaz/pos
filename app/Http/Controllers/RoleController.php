<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\RolePermission;
use App\User;
use DB;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.role.index',compact('roles'));
    }

    public function add()
    {
        $permissions = Permission::orderBy('id','asc')->get();
        return view('admin.role.add',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();

        $this->createRolePermission($request->permissions,$role->id);

        return redirect()->route('role')->with('success', true)->with('message','Role created successfully!');
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
        $role = Role::find($id);
        $permissions = DB::select("select permissions.id, permissions.name,permissions.description,user_permission.permission_id as user_permission_id
                from permissions left join
                (select roles.id, role_permissions.permission_id 
                from roles , role_permissions 
                where roles.id = role_permissions.role_id and roles.id = $id) as user_permission
                on permissions.id = user_permission.permission_id
                order by permissions.id asc
                ");

        return view('admin.role.edit',compact('role','permissions'));
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
        $role= Role::find($request->id);
        $role->name  = $request->name;
        $role->description = $request->description;
        $role->save();

        $this->createRolePermission($request->permissions,$request->id);

        return redirect()->route('role')->with('success', true)->with('message','Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Role::destroy($request->id);
        RolePermission::where('role_id', $request->id)->delete();
        return redirect()->route('role');
    }

    function createRolePermission($permissions,$id){
        RolePermission::where('role_id', $id)->delete();
        if($permissions != null){
            foreach($permissions as $permission){
                $rolePermission = new RolePermission();
                $rolePermission->role_id = $id;
                $rolePermission->permission_id = $permission;
                $rolePermission->save();
            }
        }
    }
}
