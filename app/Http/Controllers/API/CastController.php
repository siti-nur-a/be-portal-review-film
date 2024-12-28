<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casts;
use PhpParser\Node\Expr\Cast;

class CastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $casts = Casts::get();

        return response([
            "message" => "Tampil data berhasil",
            "data" => $casts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'age' => 'nullable|integer',
            'bio' => 'nullable|string',

        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'mini' => 'inputan :attribute minimal :min karakter.',
        ]);

        Casts::create([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'bio' => $request->input('bio'),
        ]);

        return response([
            "message" => "Tambah data berhasil"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $casts = Casts::with('movies')->find($id);
        return response([
            "message" => "Detail data cast",
            "data" => $casts
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2',
            'age' => 'nullable|integer',
            'bio' => 'nullable|string',

        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'mini' => 'inputan :attribute minimal :min karakter.',
        ]);
        $casts = Casts::find($id);

        $casts->name = $request->input('name');
        $casts->age = $request->input('age');
        $casts->bio = $request->input('bio');

        $casts->save();
        return response([
            "message" => "Update cast berhasil",
            "data" => $casts
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $casts = Casts::find($id);
        $casts->delete();
        return response([
            "message" => "Berhasil menghapus cast",

        ], 200);
    }
}
