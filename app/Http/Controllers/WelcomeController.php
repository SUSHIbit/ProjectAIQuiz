<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function uploadFile(FileUploadRequest $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login or register to upload files.',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $file = $request->file('file');
            
            // Generate unique filename
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Store file in temp directory
            $path = $file->storeAs('temp', $filename, 'local');
            
            // Store file info in session
            session([
                'uploaded_file' => [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientOriginalExtension(),
                    'uploaded_at' => now()
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'file_info' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => number_format($file->getSize() / 1024, 2) . ' KB'
                ],
                'redirect' => route('quiz.generator') // Will be created in Phase 4
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file. Please try again.'
            ], 500);
        }
    }

    public function removeFile(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false], 401);
        }

        $fileInfo = session('uploaded_file');
        
        if ($fileInfo && Storage::exists($fileInfo['path'])) {
            Storage::delete($fileInfo['path']);
        }
        
        session()->forget('uploaded_file');
        
        return response()->json(['success' => true]);
    }
}