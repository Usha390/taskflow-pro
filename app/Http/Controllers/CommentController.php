<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Activity;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    // নতুন comment save করো
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);
        // Activity log
Activity::create([
    'user_id' => auth()->id(),
    'task_id' => $task->id,
    'project_id' => $task->project_id,
    'action' => 'commented',
    'description' => 'Commented on "' . $task->title . '"',
]);

        return back()->with('success', 'Comment added successfully!');
    }

    // Comment delete করো
    public function destroy(Comment $comment)
    {
        // শুধু নিজের comment delete করতে পারবে
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'You can only delete your own comments.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}