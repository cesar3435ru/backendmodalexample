<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required | string | max:30',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $category = Category::create([
            'title' => $request->title,
            'active' => true
        ]);

        return response()->json($category, 201);
    }

    public function getCategories()
    {
        $categories = Category::where('active', true)->get();
        if ($categories->isEmpty()) {
            return response()->json(['message' => 'No categories'], 404);
        }

        return response()->json($categories, 200);
    }

    public function getInactiveCategories()
    {
        $categories = Category::where('active', false)->get();
        if ($categories->isEmpty()) {
            return response()->json(['message' => 'No categories'], 404);
        }

        return response()->json($categories, 200);
    }

    public function  archiveCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->active = false;
        $category->update();

        return response()->json(['message' => 'Category archived!'], 200);
    }

    
    public function  inArchiveCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->active = true;
        $category->update();

        return response()->json(['message' => 'Category activated!'], 200);
    }
}
