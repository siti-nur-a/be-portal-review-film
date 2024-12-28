<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{


    public function review(Request $request)
    {
        $request->validate([
            'critic' => 'required',
            'rating' => 'required',
            'movie_id' => 'required|exists:movies,id',


        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
            'exist' => 'inputan :attribute tidak ditemukan di tabel movies'
        ]);

        $user = auth()->user();
        $review = Review::updateOrCreate(
            ['user_id' => $user->id],
            [
                'critic' => $request->input('critic'),
                'rating' => $request->input('rating'),
                'movie_id' => $request->input('movie_id')

            ]
        );
        return response([
            "message" => "Review
             berhasil dibuat/diupdate",
            "data" => $review

        ], 201);
    }
}
