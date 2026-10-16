<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Simpan gambar ke public/image dan kembalikan nama filenya
    protected function storeImage(UploadedFile $file): string
    {
        $filename = time() . '_' . Str::random(6) . '.' . $file->extension();
        $file->move(public_path('image'), $filename);

        return $filename;
    }

    protected function deleteImage(?string $filename): void
    {
        if ($filename && $filename !== 'default.jpg') {
            File::delete(public_path('image/' . $filename));
        }
    }
}
