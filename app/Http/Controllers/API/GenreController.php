<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genres;

class GenreController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth:api', 'IsAdmin'])->only('store', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genre = Genres::get();

        return response([
            "message" => "Tampil data berhasil",
            "data" => $genre
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',


        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'mini' => 'inputan :attribute minimal :min karakter.',
        ]);

        Genres::create([
            'name' => $request->input('name'),

        ]);

        return response([
            "message" => "Tambah genre berhasil"

        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genres::with('listMovie')->find($id);
        return response([
            "message" => "Detail data genre",
            "data" => $genre
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2',


        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'min' => 'inputan :attribute minimal :min karakter.',
        ]);
        $genre = Genres::find($id);

        $genre->name = $request->input('name');


        $genre->save();
        return response([
            "message" => "Update cast berhasil",
            "data" => $genre
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genres::find($id);
        $genre->delete();
        return response([
            "message" => "Berhasil menghapus genre",

        ], 200);
    }
}
