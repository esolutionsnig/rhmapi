<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    /**
     * Display a list of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all permissions
        $permissions = Permission::all();
        return response([
            'data' => $permissions,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::findById($id);
        return $permission;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
        ]);

        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;

        $roles = $request['roles'];

        $permission->save();

        // Check if one or more roles is selected
        if (!empty($request['roles'])) {
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail();
                // Match input permission to db record
                $permission = Permission::where('name', '=', $name)->first();
                $r->givePermissionTo($permission);
            }
        }
        return response([
            'data' => "New permission ( " . $permission->name . " ) successfully added!",
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:50',
        ]);
        // $permission->update($request->all());
        $input = $request->all();
        $permission->fill($input)->save();
        return response([
            'data' => "Permission ( " . $permission->name . " ) successfully updated!",
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // Make it impossible to delete this specific permission
        if ($permission->name == "Administer roles & permissions") {
            return response([
                'error' => "This role or permission can not be deleted!",
            ], Response::HTTP_FORBIDDEN);
        }
        $permission->delete();
        return response([
            'data' => "Permission successfully deleted!",
        ], Response::HTTP_OK);
    }

    /**
     * Assign Permission to a User
     * @param  [string] permission id
     * @return [string] data
     */
    public function assign()
    {
        $user = Auth::user();
        $user->givePermissionTo('Manage Users');
        // $this->validate($request, [
        //     'permission' => 'required',
        //     'users' => 'required'
        // ]);
        // $permission = $request['permission'];
        // $users = $request['users'];
        // // Check if one or more users is selected
        // if (!empty($request['users'])) {
        //     foreach ($users as $user) {
        //         $p = Permission::where('name', '=', $permission)->first();
        //         $user->givePermissionTo($p);
        //     }
        // }
        return response([
            'data' => "Permission successfuly assigned to user",
        ], Response::HTTP_CREATED);
    }

}
