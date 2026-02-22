<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\NoteTag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteTagController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tags = NoteTag::where('user_id', $request->user()->id)
                ->latest()
                ->get();

            return ApiResponse::success([
                'tags' => $tags,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to fetch tags',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $tag = NoteTag::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
            ]);

            return ApiResponse::success([
                'message' => 'Tag created successfully',
                'tag' => $tag,
            ], 201);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to create tag',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $tag = NoteTag::where('user_id', $request->user()->id)
                ->findOrFail($id);

            return ApiResponse::success([
                'tag' => $tag,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Tag not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $tag = NoteTag::where('user_id', $request->user()->id)
                ->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $tag->update([
                'name' => $request->name,
            ]);

            return ApiResponse::success([
                'message' => 'Tag updated successfully',
                'tag' => $tag,
            ]);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to update tag',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $tag = NoteTag::where('user_id', $request->user()->id)
                ->findOrFail($id);

            $tag->delete();

            return ApiResponse::success([
                'message' => 'Tag deleted successfully',
            ]);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to delete tag',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
