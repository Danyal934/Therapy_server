<?php

namespace App\Http\Controllers;

use App\Models\Gratitude;
use Illuminate\Http\Request;

class GratitudeController extends Controller
{
    // List all
    public function index()
    {
        return response()->json(Gratitude::all());
    }

    // Store new
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field1' => 'required|string|max:255',
            'field2' => 'required|string|max:255',
            'field3' => 'nullable|string|max:255',
            'field4' => 'nullable|string|max:255',
            'field5' => 'nullable|string|max:255',
            'user_id' => 'required|integer',
        ]);

        $gratitude = Gratitude::create($validated);
        return response()->json(['message' => __('messages.gratitude_created')], 201);
    }

    // Show one
    public function show($id)
    {
        return response()->json(Gratitude::findOrFail($id));
    }

    // Update
    public function update(Request $request, $id)
    {
        $gratitude = Gratitude::findOrFail($id);

        $validated = $request->validate([
            'field1' => 'sometimes|string|max:255',
            'field2' => 'sometimes|string|max:255',
            'field3' => 'sometimes|nullable|string|max:255',
            'field4' => 'sometimes|nullable|string|max:255',
            'field5' => 'sometimes|nullable|string|max:255',
        ]);

        $gratitude->update($validated);
        return response()->json($gratitude);
    }

    // Delete
    public function destroy($id)
    {
        $gratitude = Gratitude::findOrFail($id);
        $gratitude->delete();

        return response()->json(['message' => __('messages.gratitude_deleted')]);
    }
}
