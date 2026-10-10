<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    // List all rates
    public function index(Request $request)
    {
        $user = $request->user();
        if (! ($user?->admin ?? false)) {
            return response()->json([
                'message' => __('messages.unauthorized_admin'),
            ], 403);
        }

        return response()->json(Rate::all());
    }

    // Store new rate
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }

        $validated = $request->validate([
            'value1' => 'required|numeric',
            'value2' => 'required|numeric',
            'comment' => 'required|string',
        ]);

        $rate = Rate::create(array_merge($validated, [
            'user_id' => $user->id,
        ]));

        return response()->json(['message' => __('messages.rate_created')], 201);
    }

    // Show single rate
    public function show($id)
    {
        $actor = request()->user();
        if (! $actor) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }

        $rate = Rate::with('user')->findOrFail($id);
        if (! ($actor->admin ?? false) && (int) $rate->user_id !== (int) $actor->id) {
            return response()->json([
                'message' => __('messages.unauthorized'),
            ], 403);
        }

        return response()->json($rate);
    }

    // Update rate
    public function update(Request $request, $id)
    {
        $actor = $request->user();
        if (! $actor) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }

        $rate = Rate::findOrFail($id);
        if (! ($actor->admin ?? false) && (int) $rate->user_id !== (int) $actor->id) {
            return response()->json([
                'message' => __('messages.unauthorized'),
            ], 403);
        }

        $validated = $request->validate([
            'value1' => 'sometimes|numeric',
            'value2' => 'sometimes|numeric',
            'comment' => 'sometimes|string',
        ]);

        $rate->update($validated);

        return response()->json($rate);
    }

    // Delete rate
    public function destroy($id)
    {
        $actor = request()->user();
        if (! $actor) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }

        $rate = Rate::findOrFail($id);
        if (! ($actor->admin ?? false) && (int) $rate->user_id !== (int) $actor->id) {
            return response()->json([
                'message' => __('messages.unauthorized'),
            ], 403);
        }
        $rate->delete();

        return response()->json(['message' => __('messages.rate_deleted')]);
    }
}
