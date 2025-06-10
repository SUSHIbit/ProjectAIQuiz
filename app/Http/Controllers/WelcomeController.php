<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
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

        $user = auth()->user();
        $uploadKey = 'upload_in_progress_' . $user->id;

        // Prevent double submission using cache lock
        if (Cache::has($uploadKey)) {
            return response()->json([
                'success' => false,
                'message' => 'File upload already in progress. Please wait...',
            ], 429);
        }

        // Set upload lock for 30 seconds
        Cache::put($uploadKey, true, 30);

        try {
            $file = $request->file('file');
            
            // Generate unique filename
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Store file in temp directory
            $path = $file->storeAs('temp', $filename, 'local');
            
            // Clear any existing uploaded file for this user
            $existingFile = session('uploaded_file');
            if ($existingFile && Storage::exists($existingFile['path'])) {
                Storage::delete($existingFile['path']);
            }
            
            // Store file info in session
            session([
                'uploaded_file' => [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientOriginalExtension(),
                    'uploaded_at' => now(),
                    'upload_id' => Str::random(16), // Unique upload ID
                ]
            ]);

            // Clear upload lock
            Cache::forget($uploadKey);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'file_info' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => number_format($file->getSize() / 1024, 2) . ' KB',
                    'type' => strtoupper($file->getClientOriginalExtension())
                ],
                'redirect' => route('quiz.generator')
            ]);

        } catch (\Exception $e) {
            // Clear upload lock on error
            Cache::forget($uploadKey);
            
            \Log::error('File upload error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFile(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false], 401);
        }

        try {
            $fileInfo = session('uploaded_file');
            
            if ($fileInfo && Storage::exists($fileInfo['path'])) {
                Storage::delete($fileInfo['path']);
            }
            
            session()->forget('uploaded_file');
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('File removal error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove file'
            ], 500);
        }
    }
}