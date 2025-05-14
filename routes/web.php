<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/user-location', function () {
        $reader = new \GeoIp2\Database\Reader(storage_path('app/GeoLite2-City.mmdb'));
        
        // Get IP address using multiple fallback methods
        $ip = $_SERVER['HTTP_CLIENT_IP'] 
            ?? $_SERVER['HTTP_X_FORWARDED_FOR'] 
            ?? $_SERVER['REMOTE_ADDR'] 
            ?? '8.8.8.8'; // Fallback to Google's DNS as a last resort
            
        // For local testing, use a real IP if localhost is detected
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            // Use a public IP API service as fallback for localhost
            $publicIP = file_get_contents('https://api.ipify.org');
            $ip = $publicIP ?: '8.8.8.8';
        }
        
        try {
            $record = $reader->city($ip);
            return response()->json([
                'city' => $record->city->name,
                'country' => $record->country->name,
                'ip' => $ip
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'city' => 'Unknown',
                'country' => 'Unknown',
                'ip' => $ip
            ]);
        }
    });

    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::get('/projects/{project}/tasks', function ($project) {
        return \App\Models\Project::find($project)->tasks;
    });
});

require __DIR__.'/auth.php';