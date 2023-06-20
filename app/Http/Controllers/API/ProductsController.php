<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     *
     */
    public function index(Request $request)
    {
        /*Gate::authorize('read-product');*/

        $page = $request->input('page' , 1);

        $products = Cache::remember('products' , 60*30 , function (){
            return Product::all();
        });
        $categories = Category::query()->where('category_id' , '!=' , null)->get();

        return response()->json([
            'products' => $products->forPage($page , 10),
            'categories' => $categories,
            'meta' => [
                'total' => $products->count(),
                'page' => $page,
                'last_page' => ceil($products->count() / 10)
            ]
        ] , 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param ProductRequest
     * @return
     */
    public function store(ProductRequest $request)
    {

        Gate::authorize('create-product');


        $image = $request->file('image')->storeAs('/public/products' , $request->file('image')->getClientOriginalName());

        // The same operator

        /*$image = Storage::putFileAs('/public/products' , $request->file('image') , $request->file('image')->getClientOriginalName());*/

        Product::query()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $image,
        ]);

        return response()->json([
            'message' => 'Product Created Successfully'
        ] , 201);
    }

    /**
     * Display the specified resource.
     * @param Product $product
     * @return Product
     */
    public function show(Product $product)
    {

        /*Gate::authorize('read-product');*/


        $category = $product->category;
        $sim_products = $category->products;
        $filtered =  $sim_products->where('id' , '!=' , $product->id);
        $suggest_product = $filtered->sortBy('price')->values()->take(5);
        $avg = $product->ratings()->avg('value');
        $rating['value'] = round($avg , 1);
        $rating['count'] = $product->ratings()->count();
        return response()->json([
            'product' => $product,
            'category' => $category,
            'suggest' => $suggest_product ,
            'rating' => $rating ,
        ],200);
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
     * @param ProductRequest
     * @param Product
     *@return
     *
     */
    public function update(ProductRequest $request, Product $product)
    {

        Gate::authorize('update-product' , $product);

        $image = $request->image;

        if ($request->hasFile('image')){
            $image = $request->file('image')->storeAs('/public/product' , $request->file('image')->getClientOriginalName());
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $image,
        ]);

        return response()->json([
            'message' => 'Product updated successfully'
        ] , 200);

    }

    /**
     * Remove the specified resource from storage.
     * @param Product
     * @return
     */
    public function destroy(Product $product)
    {

        Gate::authorize('delete-product' , $product);

        Storage::delete($product->image);

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ] ,200);
    }

    public function search(Request $request){

        $page = $request->input('page' , 1);

        $products = Product::all();

        if ($s = $request->input('s')){
            $products = $products->filter(fn(Product $product) => Str::contains($product->name , $s) || Str::contains($product->description , $s));
        }

        if ($sort = $request->input('sort')){
            if ($sort === 'asc'){
                $products = $products->sortBy('price');
            } elseif ($sort === 'desc'){
                $products = $products->sortByDesc('price');
            }
        }

        $total = $products->count();



        return response()->json([
            'data' => $products->forPage($page , 5)->values(),
            'meta' => [
                'total' => $total,
                'page' => $page,
                'last_page' => ceil( $total / 5)
            ]
        ] , 200);

    }
}
