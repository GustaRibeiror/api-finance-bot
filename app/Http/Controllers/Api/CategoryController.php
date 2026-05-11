<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->categories()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Category successfully created!',
            'object' => $category,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        return $request->user()->categories()->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, string $id)
    {
        $category = $request->user()->categories()->findOrFail($id);
        
        $validated = $request->validated();
        $category->update($validated);
        
        // Melhor retornar a categoria atualizada do que só "true"
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $category = $request->user()->categories()->findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted'], 204);
    }
}
