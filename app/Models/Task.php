<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'position',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Task কোন project এর অধীনে
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Task কাকে assign করা হয়েছে
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    //Task এর comments (পরে তৈরি করবো)
    public function comments()
     {
         return $this->hasMany(Comment::class);
     }

    // Task এর attachments (পরে তৈরি করবো)
    // public function attachments()
    // {
    //     return $this->hasMany(Attachment::class);
    // }

    // Overdue tasks check করার scope
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', '!=', 'done');
    }

    // Status filter scope
    public function scopeByStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    // Priority filter scope
    public function scopeByPriority($query, $priority)
    {
        return $priority ? $query->where('priority', $priority) : $query;
    }
}