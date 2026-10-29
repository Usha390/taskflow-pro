<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password','role','theme'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // Role Helpers (Purono gulo)
    // ==========================================
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    // ==========================================
    // NEW: Corporate Role Helpers (Director, Dept Head, Employee)
    // ==========================================
    public function isDirector(): bool
    {
        return $this->role === 'admin';
    }

    public function isDepartmentHead(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'developer';
    }

    /**
     * Display Name for Role (Frontend e dekhanor jonno)
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Director',
            'manager' => 'Department Head',
            'developer' => 'Employee',
            default => ucfirst($this->role),
        };
    }

    // ==========================================
    // Relationships
    // ==========================================
    
    // User এর সব projects (Many-to-Many)
    public function projects()
    {
        return $this->belongsToMany(Project::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // User যে projects তৈরি করেছে
    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    // User এর assigned tasks
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // User এর comments (অনেক comment)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
        /**
     * Check if user is overloaded (Burnout Risk)
     */
    public function isAtBurnoutRisk(): bool
    {
        // Pending tasks (To Do, In Progress, Review)
        $pendingTasks = $this->assignedTasks()
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->get();

        // 5 er beshi pending task
        if ($pendingTasks->count() < 5) {
            return false;
        }

        // Jodi 3 din er vitore 3 ta task er deadline thake
        $urgentTasks = $pendingTasks->filter(function ($task) {
            return $task->due_date && $task->due_date->diffInDays(now()) <= 3 && $task->due_date->isFuture();
        });

        return $urgentTasks->count() >= 3;
    }
}