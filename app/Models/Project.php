<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'user_id'];

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function($project) {
            $project->tasks()->delete();
        });
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
