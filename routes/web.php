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
        $ip = request()->ip();
        try {
            $record = $reader->city($ip);
            return response()->json([
                'city' => $record->city->name,
                'country' => $record->country->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'city' => 'Unknown',
                'country' => 'Unknown'
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