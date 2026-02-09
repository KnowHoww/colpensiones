<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permission::select('*')->OrderBy('name','asc')->get();
        return view('roles.create', compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $roles = Role::create([
            'name' => ($request->name),
        ]);

        $roles->permissions()->sync($request->permisos);
        return redirect()->route('roles.index')->with('info', 'Rol creado correctamente.');
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
    public function edit($role)
    {
        $rol = Role::find($role);
        $permisos = Permission::select('*')->OrderBy('name','asc')->get();
        return view('roles.edit', compact('rol', 'permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $rol)
    {
        $roles = Role::find($rol);
        $roles->update($request->all());
        $roles->syncPermissions($request->permisos);
        return redirect()->route('roles.index')->with('info', 'Rol editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
