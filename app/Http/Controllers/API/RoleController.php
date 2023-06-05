<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        /*Gate::authorize('read-role');*/

        $roles = Role::all();
        return response()->json([
            'message' => 'success' ,
            'roles' => $roles ,
        ] , 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*Gate::authorize('create-role');*/
        $role = Role::query()->create([
            'title' => $request->title
        ]);

        $role->permissions()->attach($request->permissions);

        return response()->json(['message' => 'success'] , 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        /*Gate::authorize('update-role' , $role);*/

        $role->update([
            'title' => $request->title
        ]);

        $role->permissions()->sync($request->permissions);

        return response()->json(['message' => 'success'] , 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Role $role
     * @return
     */
    public function destroy(Role $role)
    {
        /*Gate::authorize('delete-role' , $role);*/

        $role->permissions()->detach();

        $role->delete();

        return response()->json(['message' => 'success'] , 200);
    }

    /**
     * @param Role $role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function addPermissionRole(Role $role , Request $request){
        $role->permissions()->attach($request->permissions);
        return response()->json(['message' => 'success'] , 201);
    }

    /**
     * @param Role $role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function updatePermissionRole(Role $role , Request $request){
        $role->permissions()->sync($request->permissions);
        return response()->json(['message' => 'success'] , 200);
    }

    /**
     * @param Role $role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePermissionRole(Role $role , Request $request){
        $role->permissions()->detach($request->permissions);
        return response()->json(['message' => 'success'] , 200);

    }
}
