<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'project_id',
        'action',
        'description',
    ];

    // Activity কে করলো
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Activity কোন task এর (nullable)
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Activity কোন project এর (nullable)
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Icon for action (view এ ব্যবহারের জন্য)
    public function getIconAttribute(): string
    {
        return match ($this->action) {
            'created' => '➕',
            'updated' => '✏️',
            'deleted' => '🗑️',
            'commented' => '💬',
            'uploaded' => '📎',
            'status_changed' => '🔄',
            default => '📌',
        };
    }

    // Color for action
    public function getColorAttribute(): string
    {
        return match ($this->action) {
            'created' => 'text-green-600 bg-green-50',
            'updated' => 'text-blue-600 bg-blue-50',
            'deleted' => 'text-red-600 bg-red-50',
            'commented' => 'text-purple-600 bg-purple-50',
            'uploaded' => 'text-indigo-600 bg-indigo-50',
            'status_changed' => 'text-yellow-600 bg-yellow-50',
            default => 'text-gray-600 bg-gray-50',
        };
    }
}
