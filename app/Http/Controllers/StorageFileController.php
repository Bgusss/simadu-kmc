<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageFileController extends Controller
{
    /**
     * Serve file dari storage/app/public.
     * Menggantikan symlink public/storage yang tidak bekerja di php artisan serve.
     */
    public function show(string $path): BinaryFileResponse
    {
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath);
    }
}
