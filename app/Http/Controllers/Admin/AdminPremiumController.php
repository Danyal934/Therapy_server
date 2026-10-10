<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Premium;
use Illuminate\Http\Request;

class AdminPremiumController extends Controller
{
    public function index(Request $request)
    {
        $query = Premium::query()->orderByDesc('id');

        $q = $request->query('q');
        if (is_string($q) && $q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', '%'.$q.'%')
                    ->orWhere('category', 'like', '%'.$q.'%');
            });
        }

        $categoryFilter = $request->query('category');
        if (is_string($categoryFilter) && $categoryFilter !== '') {
            $query->where('category', $categoryFilter);
        }

        $premiums = $query->paginate(25)->withQueryString();
        $categories = Premium::distinct()->pluck('category');

        return view('admin.premiums.index', [
            'premiums' => $premiums,
            'q' => $q,
            'categoryFilter' => $categoryFilter,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return view('admin.premiums.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'premium' => 'nullable|boolean',
            'pic' => 'required|image|mimes:jpg,jpeg,png',
            'audio_file' => 'required|mimes:mp3,wav',
            'prompts' => 'nullable|string',
        ]);

        $isPremium = (bool) ($validated['premium'] ?? false);
        $picPath = $request->file('pic')->store('premium/pics', 'public');
        $audioDisk = $isPremium ? 'local' : 'public';
        $audioPath = $request->file('audio_file')->store('premium/audio', $audioDisk);

        $premium = Premium::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'pic' => $picPath,
            'audio_file' => $audioPath,
            'premium' => $isPremium,
        ]);

        $prompts = $validated['prompts'] ?? null;
        if (is_string($prompts) && $prompts !== '') {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $prompts))));
            $premium->journal_prompts = $lines;
            $premium->save();
        }

        return redirect()->route('admin.premiums.edit', $premium)->with('status', 'Created.');
    }

    public function edit(Premium $premium)
    {
        return view('admin.premiums.edit', [
            'premium' => $premium,
        ]);
    }

    public function update(Request $request, Premium $premium)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'premium' => 'nullable|boolean',
            'pic' => 'nullable|image|mimes:jpg,jpeg,png',
            'audio_file' => 'nullable|mimes:mp3,wav',
            'prompts' => 'nullable|string',
        ]);

        $isPremium = (bool) ($validated['premium'] ?? false);
        $premium->title = $validated['title'];
        $premium->category = $validated['category'];
        $premium->premium = $isPremium;

        if ($request->hasFile('pic')) {
            $premium->pic = $request->file('pic')->store('premium/pics', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $audioDisk = $isPremium ? 'local' : 'public';
            $premium->audio_file = $request->file('audio_file')->store('premium/audio', $audioDisk);
        }

        $prompts = $validated['prompts'] ?? null;
        if (is_string($prompts)) {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $prompts))));
            $premium->journal_prompts = $lines;
        }

        $premium->save();

        $queryParams = $request->only(['q', 'category']);

        return redirect()->route('admin.premiums.index', $queryParams)->with('status', 'Saved.');
    }

    public function destroy(Premium $premium)
    {
        $premium->delete();

        return redirect()->route('admin.premiums.index')->with('status', 'Deleted.');
    }
}
