<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::with(['uploader', 'views'])->latest()->paginate(10);

        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        return view('resources.create');
    }

    public function store(Request $request)
    {
        $resourceType = $request->input('resource_type', 'file');

        if ($resourceType === 'youtube') {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'youtube_url' => 'required|url',
                'is_public' => 'boolean',
            ]);

            // Additional validation for YouTube URL
            $url = $request->youtube_url;
            if (! preg_match('/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/i', $url)) {
                return back()->withErrors(['youtube_url' => 'Please enter a valid YouTube URL'])->withInput();
            }

            Resource::create([
                'title' => $request->title,
                'description' => $request->description,
                'youtube_url' => $request->youtube_url,
                'is_youtube' => true,
                'file_type' => 'youtube',
                'file_name' => 'YouTube Video',
                'file_path' => '',
                'mime_type' => 'video/youtube',
                'file_size' => 0,
                'is_public' => $request->boolean('is_public', true),
                'uploaded_by' => auth()->id(),
            ]);
        } else {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'required|file|max:102400', // 100MB max
                'is_public' => 'boolean',
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
                $filePath = $file->storeAs('resources', $fileName, 'public');

                $fileType = $this->getFileType($file->getMimeType());

                Resource::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'is_public' => $request->boolean('is_public', true),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('resources.index')->with('success', 'Resource uploaded successfully!');
    }

    public function show(Resource $resource)
    {
        return view('resources.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $resource->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', true),
        ]);

        return redirect()->route('resources.index')->with('success', 'Resource updated successfully!');
    }

    public function destroy(Resource $resource)
    {
        Storage::disk('public')->delete($resource->file_path);
        $resource->delete();

        return redirect()->route('resources.index')->with('success', 'Resource deleted successfully!');
    }

    public function download(Resource $resource)
    {
        if (! $resource->is_public && ! auth()->check()) {
            abort(403);
        }

        return Storage::disk('public')->download($resource->file_path, $resource->file_name);
    }

    public function publicIndex()
    {
        $resources = Resource::where('is_public', true)
            ->with('uploader')
            ->latest()
            ->paginate(12);

        return view('resources.public', compact('resources'));
    }

    public function publicShow(Resource $resource)
    {
        if (! $resource->is_public) {
            abort(404);
        }

        return view('resources.public-show', compact('resource'));
    }

    private function getFileType(string $mimeType): string
    {
        return match (true) {
            str_contains($mimeType, 'video/') => 'video',
            $mimeType === 'application/pdf' => 'pdf',
            in_array($mimeType, [
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]) => 'word',
            in_array($mimeType, [
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]) => 'excel',
            in_array($mimeType, [
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ]) => 'powerpoint',
            default => 'other',
        };
    }

    public function trackView(Request $request, Resource $resource)
    {
        $request->validate([
            'watch_duration' => 'required|numeric|min:0',
            'total_duration' => 'required|numeric|min:0',
            'completion_percentage' => 'required|numeric|min:0|max:100',
        ]);

        ResourceView::create([
            'resource_id' => $resource->id,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'watch_duration' => $request->watch_duration,
            'total_duration' => $request->total_duration,
            'completion_percentage' => $request->completion_percentage,
        ]);

        return response()->json(['success' => true]);
    }
}
