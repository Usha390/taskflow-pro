<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'file_path',
        'original_name',
        'file_size',
    ];

    // Attachment কোন task এর
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Attachment কে upload করেছে
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Human-readable file size
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}