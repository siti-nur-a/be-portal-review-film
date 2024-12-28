<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cast_movie;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class CastMovieController extends Controller
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
        $castMovie = Cast_movie::get();

        return response([
            "message" => "Tampil data berhasil",
            "data" => $castMovie
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',


        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'min' => 'inputan :attribute minimal :min karakter.',
        ]);

        Cast_movie::create([
            'name' => $request->input('name'),
            'cast_id' => $request->input('cast_id'),
            'movie_id' => $request->input('movie_id'),

        ]);

        return response([
            "message" => "Tambah cast movie berhasil"

        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        /*return response([
            "message" => "data tidak ditemukan",
        ], 404);*/
        $castMovie = Cast_movie::with(['movie', 'cast'])->find($id);
        return response([
            "message" => "Detail data cast movie",
            "data" => $castMovie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'name' => 'required',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',


        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'min' => 'inputan :attribute minimal :min karakter.',
        ]);

        $castMovie = Cast_movie::find($id);

        $castMovie->name = $request->input('name');
        $castMovie->cast_id = $request->input('cast_id');
        $castMovie->movie_id = $request->input('movie_id');

        $castMovie->save();
        return response([
            "message" => "Update cast movie berhasil",
            "data" => $castMovie
        ], 200);

        if (!$castMovie) {
            return response([
                "message" => "Data tidak ditemukan"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $castMovie = Cast_movie::find($id);

        $castMovie->delete();
        return response([
            "message" => "berhasil didelete",

        ], 200);

        if (!$castMovie) {
            return response([
                "message" => "Data tidak ditemukan"
            ], 404);
        }
    }
}
