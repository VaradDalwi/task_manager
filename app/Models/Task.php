<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
