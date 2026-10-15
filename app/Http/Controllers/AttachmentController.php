<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use App\Models\Activity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    // File upload করো
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,png,jpg,jpeg,zip,txt',
        ], [
            'file.required' => 'Please select a file.',
            'file.max' => 'File size must be less than 10 MB.',
            'file.mimes' => 'Only PDF, DOC, DOCX, PNG, JPG, JPEG, ZIP, TXT files allowed.',
        ]);

        $file = $request->file('file');


        // File storage এ save করো
        $path = $file->store('attachments/' . $task->id, 'public');

        // Database এ record save করো
        $task->attachments()->create([
            'user_id' => auth()->id(),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ]);
        // Activity log
Activity::create([
    'user_id' => auth()->id(),
    'task_id' => $task->id,
    'project_id' => $task->project_id,
    'action' => 'uploaded',
    'description' => 'Uploaded file to "' . $task->title . '"',
]);
      

        return back()->with('success', 'File uploaded successfully!');
    }

    // File delete করো
    public function destroy(Attachment $attachment)
    {
        // Authorization: নিজের upload বা admin
        if ($attachment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'You can only delete your own uploads.');
        }

        // Storage থেকে file মুছো
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Database থেকে record মুছো
        $attachment->delete();

        return back()->with('success', 'File deleted successfully!');
    }
}