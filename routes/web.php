<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AttachmentController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ActivityController;

use App\Http\Controllers\ReportsController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.updateStatus');

    // Comment Routes
    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
        // Attachment Routes
Route::post('tasks/{task}/attachments', [AttachmentController::class, 'store'])
    ->name('attachments.store');
Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy'])
    ->name('attachments.destroy');
    // Kanban Routes
Route::get('/board', [KanbanController::class, 'index'])
    ->name('kanban.index');
Route::patch('/tasks/{task}/status', [KanbanController::class, 'updateStatus'])
    ->name('kanban.updateStatus');
    // Notification Routes
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications.index');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.readAll');
    // Reports Routes
Route::get('/reports', [ReportsController::class, 'index'])
    ->name('reports.index');
    // Theme Toggle Route
Route::post('/theme/toggle', [ThemeController::class, 'toggle'])
    ->name('theme.toggle');
    // Activity Log Routes
Route::get('/activity', [ActivityController::class, 'index'])
    ->name('activity.index');
    
});
require __DIR__.'/auth.php';
Route::get('/test-burnout', function () {
    // 1. Ekta project create koro
    $project = \App\Models\Project::firstOrCreate(
        ['name' => 'Burnout Test Project'],
        [
            'description' => 'Testing burnout detector.',
            'created_by' => 1,
            'color' => '#EF4444',
        ]
    );

    // 2. Test User ke khuje ber koro
    $testUser = \App\Models\User::where('email', 'test@taskflow.com')->first();

    if (!$testUser) {
        return "Test User not found!";
    }

    // 3. 5 ta task create koro (due date 2 din er vitore)
    for ($i = 1; $i <= 5; $i++) {
        \App\Models\Task::create([
            'project_id' => $project->id,
            'title' => "Urgent Task $i",
            'description' => 'This is a burnout test task.',
            'status' => 'todo',
            'priority' => 'high',
            'assigned_to' => $testUser->id,
            'due_date' => now()->addDays(2), // 2 din er vitore
        ]);
    }

    return "5 ta urgent task create hoyeche Test User er jonno! Ebar dashboard e dekho.";
});
