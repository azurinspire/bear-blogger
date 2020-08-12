<?php

namespace AzurInspire\BearBlogger\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImportController
{
    public function preview(string $directory, string $photo)
    {
        return Storage::get(config('bear-blogger.import-path') . '/' . $directory . '/' . $photo);
    }
}
