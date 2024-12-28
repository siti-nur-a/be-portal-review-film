<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $request->validate([
            'biodata' => 'required',
            'age' => 'required|integer',
            'address' => 'required',


        ], [
            'required' => 'inputan :attribute harus diisi, tidak boleh kosong.',
        ]);

        $user = auth()->user();
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'biodata' => $request->input('biodata'),
                'age' => $request->input('age'),
                'address' => $request->input('address')

            ]
        );
        return response([
            "message" => "Profile berhasil dibuat/diupdate",
            "data" => $profile

        ], 201);
    }
}
