<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ErrorResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function create(Request $request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255'
        ]);
        if ($validator->fails()) {
            return new ErrorResource($validator);
        }
        $category = Category::create($request->all());

        return new CategoryResource($category);
    }

    public function delete(Category $category): JsonResponse
    {
        if (!$category->products()->exists()) {
            $category->delete();
            return new JsonResponse(
                ['message' => 'Category succesfully deleted',]
            );
        } else {
            return new JsonResponse([
                'error' => 'Can`t delete category. Category must not have related products',
            ]);
        }
    }
}
