<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Note;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Note::where('user_id', $request->user()->id)
                ->with(['category', 'tags']);

            if ($request->has('is_archived')) {
                $query->where('is_archived', $request->is_archived);
            }

            if ($request->has('is_pinned')) {
                $query->where('is_pinned', $request->is_pinned);
            }

            if ($request->has('is_favorite')) {
                $query->where('is_favorite', $request->is_favorite);
            }

            if ($request->has('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%")
                        ->orWhere('content', 'like', "%{$request->search}%");
                });
            }

            $notes = $query->orderByDesc('is_pinned')
                ->latest()
                ->paginate(10);

            return ApiResponse::success(['notes' => $notes]);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to fetch notes',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'content' => 'nullable|string',
                'category_id' => 'nullable|exists:note_categories,id',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:note_tags,id',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $content = $request->content ?? '';

            $note = Note::create([
                'user_id' => $request->user()->id,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'content' => $content,
                'word_count' => str_word_count(strip_tags($content)),
                'character_count' => strlen($content),
            ]);

            if ($request->tags) {
                $note->tags()->sync($request->tags);
            }

            return ApiResponse::success([
                'message' => 'Note created successfully',
                'note' => $note->load(['category', 'tags']),
            ], 201);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to create note',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $note = Note::where('user_id', $request->user()->id)
                ->with(['category', 'tags'])
                ->findOrFail($id);

            return ApiResponse::success(['note' => $note]);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Note not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $note = Note::where('user_id', $request->user()->id)
                ->findOrFail($id);

            $content = $request->content ?? $note->content;

            $note->update([
                'title' => $request->title ?? $note->title,
                'content' => $content,
                'category_id' => $request->category_id,
                'word_count' => str_word_count(strip_tags($content)),
                'character_count' => strlen($content),
            ]);

            if ($request->tags) {
                $note->tags()->sync($request->tags);
            }

            return ApiResponse::success([
                'message' => 'Note updated successfully',
                'note' => $note->load(['category', 'tags']),
            ]);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to update note',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $note = Note::where('user_id', $request->user()->id)
                ->findOrFail($id);

            $note->delete();

            return ApiResponse::success([
                'message' => 'Note moved to trash',
            ]);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to delete note',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            $note = Note::onlyTrashed()
                ->where('user_id', $request->user()->id)
                ->findOrFail($id);

            $note->restore();

            return ApiResponse::success([
                'message' => 'Note restored successfully',
            ]);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to restore note',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            $note = Note::withTrashed()
                ->where('user_id', $request->user()->id)
                ->findOrFail($id);

            if (! $note->trashed()) {
                return ApiResponse::error([
                    'message' => 'Note must be in trash before permanent deletion',
                ], 400);
            }

            $note->forceDelete();

            return ApiResponse::success([
                'message' => 'Note permanently deleted',
            ]);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to permanently delete note',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
