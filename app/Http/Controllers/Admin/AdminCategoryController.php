<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Premium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()->orderBy('name');

        $q = $request->query('q');
        if (is_string($q) && $q !== '') {
            $query->where('name', 'like', '%'.$q.'%');
        }

        $active = $request->query('active');
        if ($active === '1') {
            $query->where('active', true);
        } elseif ($active === '0') {
            $query->where('active', false);
        }

        $categories = $query->paginate(25)->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'q' => $q,
            'active' => $active,
        ]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'sub_heading' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
            'pic' => 'nullable|image|mimes:jpg,jpeg,png',
            'audio_file' => 'nullable|mimes:mp3,wav',
        ]);

        $category = new Category;
        $category->name = $validated['name'];
        $category->sub_heading = $validated['sub_heading'] ?? null;
        $category->active = (bool) ($validated['active'] ?? false);

        if ($request->hasFile('pic')) {
            $category->pic = $request->file('pic')->store('categories', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $category->audio_file = $request->file('audio_file')->store('categories/audio', 'public');
        }

        $category->save();

        return redirect()->route('admin.categories.edit', $category)->with('status', 'Created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'sub_heading' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
            'pic' => 'nullable|image|mimes:jpg,jpeg,png',
            'audio_file' => 'nullable|mimes:mp3,wav',
        ]);

        DB::transaction(function () use ($request, $validated, $category) {
            $oldName = $category->name;
            $newName = $validated['name'];

            $category->name = $newName;
            $category->sub_heading = $validated['sub_heading'] ?? null;
            $category->active = (bool) ($validated['active'] ?? false);

            if ($request->hasFile('pic')) {
                $category->pic = $request->file('pic')->store('categories', 'public');
            }

            if ($request->hasFile('audio_file')) {
                $category->audio_file = $request->file('audio_file')->store('categories/audio', 'public');
            }

            $category->save();

            if ($oldName !== $newName) {
                Premium::where('category', $oldName)->update(['category' => $newName]);
            }
        });

        return redirect()->route('admin.categories.edit', $category)->with('status', 'Saved.');
    }
}
