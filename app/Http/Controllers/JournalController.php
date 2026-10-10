<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the journal entries for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        // Use direct query instead of relationship
        $query = Journal::where('user_id', $user->id);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        $journals = $query->latest()->get();

        return response()->json([
            'journals' => $journals
        ]);
    }

    /**
     * Store a newly created journal entry.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.validation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }

        // Create journal directly
        $journal = Journal::create([ 
            'user_id' => $user->id,
            'text' => $request->text,
            'category_id' => $request->has('category_id') ? $request->category_id : -1,
            'sub_category_id' => $request->has('sub_category_id') ? $request->sub_category_id : -1
        ]);

        return response()->json([
            'message' => __('messages.journal_created'),
            'journal' => $journal
        ], 201);
    }

    /**
     * Display the specified journal entry.
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        $journal = Journal::find($id);

        if (!$journal) {
            return response()->json([
                'message' => __('messages.journal_not_found')
            ], 404);
        }

        // Check if the journal belongs to the authenticated user
        if ($journal->user_id !== $user->id) {
            return response()->json([
                'message' => __('messages.unauthorized')
            ], 403);
        }

        return response()->json([
            'journal' => $journal
        ]);
    }

    /**
     * Update the specified journal entry.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        $journal = Journal::find($id);

        if (!$journal) {
            return response()->json([
                'message' => __('messages.journal_not_found')
            ], 404);
        }

        // Check if the journal belongs to the authenticated user
        if ($journal->user_id !== $user->id) {
            return response()->json([
                'message' => __('messages.unauthorized')
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.validation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }

        $journal->update([
            'text' => $request->text,
            'category_id' => $request->has('category_id') ? $request->category_id : $journal->category_id,
            'sub_category_id' => $request->has('sub_category_id') ? $request->sub_category_id : $journal->sub_category_id
        ]);

        return response()->json([
            'message' => __('messages.journal_updated'),
            'journal' => $journal
        ]);
    }

    /**
     * Remove the specified journal entry.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        $journal = Journal::find($id);

        if (!$journal) {
            return response()->json([
                'message' => __('messages.journal_not_found')
            ], 404);
        }

        // Check if the journal belongs to the authenticated user
        if ($journal->user_id !== $user->id) {
            return response()->json([
                'message' => __('messages.unauthorized')
            ], 403);
        }

        $journal->delete();

        return response()->json([
            'message' => __('messages.journal_deleted')
        ]);
    }

    /**
     * Get journal entries for a specific user (admin only)
     */
    public function getUserJournals($userId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        // Check if the authenticated user is an admin
        if (!$user->admin) {
            return response()->json([
                'message' => __('messages.unauthorized_admin')
            ], 403);
        }

        // Check if the requested user exists
        $targetUser = User::find($userId);
        if (!$targetUser) {
            return response()->json([
                'message' => __('messages.user_not_found')
            ], 404);
        }

        $journals = Journal::where('user_id', $userId)->latest()->get();

        return response()->json([
            'journals' => $journals,
            'user' => [
                'id' => $targetUser->id,
                'name' => $targetUser->name,
                'email' => $targetUser->email
            ]
        ]);
    }
}
