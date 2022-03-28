<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Permission;

class RoleController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum')->only('store','update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all()->load('permissions');
     
        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $credentials = $request->only(['name', 'code', 'permissions']);
        $role = Role::create([
            'name' => $credentials['name'],
            'code' => $credentials['code'],
        ]);
        $role->permissions()->sync($credentials['permissions']);
        $role->load('permissions');
        
        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id)->load('permissions');

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $credentials = $request->only(['name', 'code', 'permissions']);
        $role->update([
            'name' => $credentials['name'],
            'code' => $credentials['code'],
        ]);
        $role->permissions()->sync($credentials['permissions']);
        $role->load('permissions');
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        //$role->permissions()->detach();
        $role->delete();
        
        return response()->json([
            'data' => 'OK'
        ]);
    }
}
