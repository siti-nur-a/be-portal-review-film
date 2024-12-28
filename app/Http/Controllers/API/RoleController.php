<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        $role = Roles::get();

        return response([
            "message" => "Tampil data berhasil",
            "data" => $role
        ], 200);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            
            
        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'mini' => 'inputan :attribute minimal :min karakter.',
        ]);

        Roles::create([
            'name' => $request->input('name'),
            
        ]);

        return response([
            "message" => "Tambah role berhasil"
        
        ], 201);
    }
    /**
     * Display the specified role.
     */
    public function show(string $id)
    {
        $role = Roles::find($id);
        return response([
            "message" => "Detail data role",
            "data" => $role
        ], 200);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2',
            
            
        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'min' => 'inputan :attribute minimal :min karakter.',
        ]);
        $role = Roles::find($id);
 
        $role->name = $request -> input('name');
        
 
        $role->save();
        return response([
            "message" => "Update role berhasil",
            "data" => $role
        ], 200);
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(string $id)
    {
        $role = Roles::find($id);
        $role->delete();
        return response([
            "message" => "Berhasil menghapus role",
            
        ], 200);
    }
}