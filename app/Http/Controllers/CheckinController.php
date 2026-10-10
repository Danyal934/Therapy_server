<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckinController extends Controller
{
    // ✅ List checkins (optionally filter by user_id)
    public function index(Request $request)
    {
        $query = Checkin::query();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $checkins = $query->orderBy('created_at', 'desc')->get()
            ->each->append('voice_url');

        return response()->json($checkins);
    }

    // ✅ Store a new checkin
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'text' => 'nullable|string|max:5000',
            'tags' => 'nullable',
            // 'voice' => 'nullable|file|mimetypes:audio/mpeg,audio/wav,audio/ogg|max:10240',
            'voice' => 'nullable|file',
        ]);

        $data = $request->only(['user_id', 'text', 'tags']);

        // Handle file upload
        if ($request->hasFile('voice')) {
            $file = $request->file('voice');
            $path = $file->storeAs(
                'checkins/' . date('Y/m'),
                Str::uuid() . '.' . $file->getClientOriginalExtension(),
                'public'
            );
            $data['voice_path'] = $path;
        }

        $checkin = Checkin::create($data);
        $checkin->append('voice_url');

        return response()->json($checkin, 201);
    }
}
