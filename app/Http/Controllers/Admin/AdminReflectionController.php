<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reflection;
use Illuminate\Http\Request;

class AdminReflectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Reflection::query()->orderByDesc('id');

        $q = $request->query('q');
        if (is_string($q) && $q !== '') {
            $query->where('title', 'like', '%'.$q.'%');
        }

        $premiumFlag = $request->query('premium');
        if ($premiumFlag === '1') {
            $query->where('premium', true);
        } elseif ($premiumFlag === '0') {
            $query->where('premium', false);
        }

        $reflections = $query->paginate(25)->withQueryString();

        return view('admin.reflections.index', [
            'reflections' => $reflections,
            'q' => $q,
            'premiumFlag' => $premiumFlag,
        ]);
    }

    public function create()
    {
        return view('admin.reflections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subheadings' => 'nullable|string',
            'premium' => 'nullable|boolean',
            'pic' => 'required|image|mimes:jpg,jpeg,png',
            'audio_file' => 'required|mimes:mp3,wav',
            'prompts' => 'nullable|string',
        ]);

        $isPremium = (bool) ($validated['premium'] ?? false);
        $picPath = $request->file('pic')->store('reflections/pics', 'public');
        $audioDisk = $isPremium ? 'local' : 'public';
        $audioPath = $request->file('audio_file')->store('reflections/audio', $audioDisk);

        $reflection = Reflection::create([
            'title' => $validated['title'],
            'pic' => $picPath,
            'audio_file' => $audioPath,
            'premium' => $isPremium,
        ]);

        $subheadings = $validated['subheadings'] ?? null;
        if (is_string($subheadings) && $subheadings !== '') {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $subheadings))));
            $reflection->subheadings = $lines;
            $reflection->save();
        }

        $prompts = $validated['prompts'] ?? null;
        if (is_string($prompts) && $prompts !== '') {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $prompts))));
            $reflection->journal_prompts = $lines;
            $reflection->save();
        }

        return redirect()->route('admin.reflections.edit', $reflection)->with('status', 'Created.');
    }

    public function edit(Reflection $reflection)
    {
        return view('admin.reflections.edit', [
            'reflection' => $reflection,
        ]);
    }

    public function update(Request $request, Reflection $reflection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subheadings' => 'nullable|string',
            'premium' => 'nullable|boolean',
            'pic' => 'nullable|image|mimes:jpg,jpeg,png',
            'audio_file' => 'nullable|mimes:mp3,wav',
            'prompts' => 'nullable|string',
        ]);

        $isPremium = (bool) ($validated['premium'] ?? false);
        $reflection->title = $validated['title'];
        $reflection->premium = $isPremium;

        if ($request->hasFile('pic')) {
            $reflection->pic = $request->file('pic')->store('reflections/pics', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $audioDisk = $isPremium ? 'local' : 'public';
            $reflection->audio_file = $request->file('audio_file')->store('reflections/audio', $audioDisk);
        }

        $subheadings = $validated['subheadings'] ?? null;
        if (is_string($subheadings)) {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $subheadings))));
            $reflection->subheadings = $lines;
        }

        $prompts = $validated['prompts'] ?? null;
        if (is_string($prompts)) {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $prompts))));
            $reflection->journal_prompts = $lines;
        }

        $reflection->save();

        return redirect()->route('admin.reflections.edit', $reflection)->with('status', 'Saved.');
    }

    public function destroy(Reflection $reflection)
    {
        $reflection->delete();

        return redirect()->route('admin.reflections.index')->with('status', 'Deleted.');
    }
}
