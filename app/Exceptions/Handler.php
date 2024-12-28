<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Daftar jenis exception yang tidak akan dilaporkan.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];

    /**
     * Daftar input yang tidak akan disimpan untuk validasi.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Tangani laporan exception.
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
