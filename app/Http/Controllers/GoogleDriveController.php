<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;


class GoogleDriveController extends Controller
{
    public function listBackups()
    {
        try {
            $allFiles = Storage::disk('google')->listContents('/', true); // List all contents from Google Drive
            $zipFiles = collect($allFiles)->filter(function ($file) {
                return $file['type'] === 'file' && isset($file['extension']) && $file['extension'] === 'zip';
            })->values(); // Filter to get only .zip files

            // Make sure you reference the correct view file
            return view('staff.SSetting', ['zipFiles' => $zipFiles]); // Pass 'zipFiles' to the view
        } catch (\Exception $e) {
            return back()->withErrors('Failed to fetch files: ' . $e->getMessage());
        }
    }
}


