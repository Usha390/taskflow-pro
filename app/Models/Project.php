<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'color',
    ];

    // Project এর creator (একজন user)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Project এর members (অনেক user)
    public function members()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Project এর tasks (অনেক task)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    

    // শুধু যেসব project এ user member
    public function scopeForUser($query, $userId)
    {
        return $query->where('created_by', $userId)
                     ->orWhereHas('members', function ($q) use ($userId) {
                         $q->where('user_id', $userId);
                     });
    }
}