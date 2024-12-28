<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class MoviesController extends Controller
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
        $movie = Movie::get();

        return response([
            "message" => "Tampil data berhasil",
            "data" => $movie
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

            'genre_id' => 'required|exists:genres,id',
            'year' => 'required',


        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'min' => 'inputan :attribute minimal :min karakter.',
        ]);

        $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
            'folder' => 'image',

        ])->getSecurePath();

        Movie::create([
            'title' => $request->input('title'),
            'summary' => $request->input('summary'),
            'poster' => $uploadedFileUrl,
            'genre_id' => $request->input('genre_id'),
            'year' => $request->input('year'),

        ]);

        return response([
            "message" => "Tambah movie berhasil"

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
        $movie = Movie::with(['genre', 'casts', 'list_review'])->find($id);
        return response([
            "message" => "Detail data movie",
            "data" => $movie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'poster' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            'genre_id' => 'required|exists:genres,id',
            'year' => 'required',


        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'min' => 'inputan :attribute minimal :min karakter.',
        ]);
        $movie = Movie::find($id);


        if ($request->hasFile('poster')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'image',

            ])->getSecurePath();
            $movie->poster = $uploadedFileUrl;
        }

        if (!$movie) {
            return response([
                "message" => "Data tidak ditemukan"
            ], 404);
        }


        $movie->title = $request->input('title');
        $movie->summary = $request->input('summary');


        $movie->genre_id = $request->input('genre_id');
        $movie->year = $request->input('year');

        $movie->save();
        return response([
            "message" => "Update movie berhasil",
            "data" => $movie
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response([
                "message" => "Data tidak ditemukan"
            ], 404);
        }

        $movie->delete();
        return response([
            "message" => "berhasil movie didelete",

        ], 200);
    }
}
