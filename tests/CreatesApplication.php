<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     * Metode ini dipanggil oleh TestCase sebelum setiap tes untuk membuat instance Laravel.
     */
    public function createApplication(): \Illuminate\Foundation\Application
    {
        // Memuat file bootstrap/app.php untuk menginisialisasi aplikasi.
        $app = require __DIR__.'/../bootstrap/app.php';

        // Melakukan bootstrapping kernel (misalnya memuat environment/config).
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
