<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::query()->where('category_id' , null)->get();
        return response()->json($category , 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {

        Gate::authorize('create-category');

        Category::query()->create([
            'title' => $request->title,
            'status' => $request->status,
            'category_id' => null,
        ]);

        return response()->json([
            'message' => 'category created successfully'
        ] , 201);
    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function storeSubCat(CategoryRequest $request){

        Gate::authorize('create-category');

        Category::query()->create([
            'title' => $request->title,
            'status' => $request->status,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'message' => 'category created successfully'
        ] , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param  CategoryRequest
     * @param Category
     * @return
     */
    public function update(CategoryRequest $request, Category $category)
    {

        Gate::authorize('update-category' , $category);

        $category->update([
            'title' => $category->title ,
            'status' => $category->status ,
            'category_id' => $category->category_id ,
        ]);
        return \response()->json([
            'message' => 'category updated successfully'
        ] , 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {

        Gate::authorize('delete-category' , $category);

        $category->products()->delete();
        if ($category->hasChild){
            $category->child()->delete();
        }
        $category->delete();






/*        $filtered = $products->filter(function (Product $product){
            return $product->price < 200 && $product->price > 100;
        });

        $mapped = $products->map(function (Product $product){
            Product::query()->where('id' , $product->id)->update([
                'category_id' => 1
            ]);
        });
        return $mapped;*/
    }
}
