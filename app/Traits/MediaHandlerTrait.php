<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait MediaHandlerTrait
{
    protected function handleMediaUpload(UploadedFile $file, string $type): string
    {
        $allowedTypes = [
            'thumbnail' => 'thumbnails',
            'content_image' => 'articles/images',
            'category_image' => 'categories',
            'user_photo' => 'users/photos',
        ];

        if (!array_key_exists($type, $allowedTypes)) {
            throw new \InvalidArgumentException("Invalid media type: {$type}");
        }

        // Buat direktori di public/storage jika belum ada
        $directory = public_path('storage/' . $allowedTypes[$type]);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Generate nama file unik
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();

        // Path relatif untuk database
        $relativePath = $allowedTypes[$type] . '/' . $fileName;

        // Pindahkan file ke public/storage
        $file->move(public_path('storage/' . $allowedTypes[$type]), $fileName);

        return $relativePath;
    }

    protected function deleteMedia(?string $path): void
    {
        if ($path && File::exists(public_path('storage/' . $path))) {
            File::delete(public_path('storage/' . $path));
        }
    }
}
