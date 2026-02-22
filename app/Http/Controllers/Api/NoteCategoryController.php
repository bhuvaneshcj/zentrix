<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\NoteCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteCategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $categories = NoteCategory::where('user_id', $request->user()->id)
                ->latest()
                ->get();

            return ApiResponse::success([
                'categories' => $categories,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to fetch categories',
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

            $category = NoteCategory::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
            ]);

            return ApiResponse::success([
                'message' => 'Category created successfully',
                'category' => $category,
            ], 201);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to create category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $category = NoteCategory::where('user_id', $request->user()->id)
                ->findOrFail($id);

            return ApiResponse::success([
                'category' => $category,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Category not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = NoteCategory::where('user_id', $request->user()->id)
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

            $category->update([
                'name' => $request->name,
            ]);

            return ApiResponse::success([
                'message' => 'Category updated successfully',
                'category' => $category,
            ]);

        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to update category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $category = NoteCategory::where('user_id', $request->user()->id)
                ->findOrFail($id);

            $category->delete();

            return ApiResponse::success([
                'message' => 'Category deleted successfully',
            ]);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Failed to delete category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
