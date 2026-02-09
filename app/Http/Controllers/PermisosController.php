<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permisos = Permission::all();
        return view('permisos.index', compact('permisos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('permisos.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $permisos = Permission::create([
            'name' => ($request->name),
        ]);
        
        $permisos->roles()->sync($request->roles);
        return redirect()->route('permisos.index')->with('info','Permiso creado correctamente.');
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
    public function edit($permiso)
    {
        $permiso = Permission::find($permiso);
        $roles = Role::all();
        return view('permisos.edit', compact('permiso', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $permiso)
    {
        $permiso = Permission::find($permiso);
        $permiso->update($request->all());
        $permiso->roles()->sync($request->roles);
        return redirect()->route('permisos.index')->with('info','Permiso editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
