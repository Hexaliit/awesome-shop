<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Category;
use App\Models\Image;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return
     */
    public function index(Request $request)
    {

//        Gate::authorize('read-product');

        /*$page = $request->input('page' , 1);*/

/*        $products = Cache::remember('products' , 60*30 , function (){
            return Product::query()->paginate(5);
        });*/
/*        $categories = Category::query()->where('category_id' , '!=' , null)->get();
        return response()->json([
            'products' => $products->forPage($page , 10),
            'categories' => $categories,
            'meta' => [
                'total' => $products->count(),
                'page' => $page,
                'last_page' => ceil($products->count() / 10)
            ]
        ] , 200);*/


        $perPage = $request->get('per_page') ?? 5;
        $products = Product::query()->with('category')->paginate($perPage);
        return ProductResource::collection($products);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function temp(){
        $name = \request()->input('name');
        return Str::slug($name);
    }

    public function test(Request $request){

        $days = (int) $request->input('days' , 7) ;
        $sort = $request->input('sort' , 'desc');
        $day = new \DateTime(date('Y-m-d' , strtotime('-'.$days.' days')));
        /*dd($day);*/
        $products = OrderItems::with('product')->select(['product_id' ,
            DB::raw('SUM(quantity) as quantity'),
            ])
            ->where('created_at' , '>' , $day)
            ->groupBy('product_id')
            ->orderBy('quantity' , $sort)
            ->get();
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     * @param ProductRequest
     * @return
     */
    public function store(ProductRequest $request)
    {

        /*Gate::authorize('create-product');*/

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->image ='temp.png';
        $product->save();


        $image = $request->file("image");

        foreach ($image as $photo) {
            Image::query()->create([
                'imageable_id' => $product->id ,
                'imageable_type' => Product::class ,
                'path' => $photo->storeAs('/public/products' , $photo->getClientOriginalName())
            ]);
        }
        // The same operator

        /*$image = Storage::putFileAs('/public/products' , $request->file('image') , $request->file('image')->getClientOriginalName());*/



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
        /*$product->load(['comments' , 'ratings' , 'category']);*/
        $product->load(['category']);
        $product->load(['ratings']);
        $product->load(['images']);
        $product->load(['comments' => function($query) {
            $query->where('status' , '1')->get();
        }]);
        return (new ProductResource($product));


/*        $category = $product->category;
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
            'comments' => $product->confirmedComments()
        ],200);*/
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

        $products = Product::query()->withAvg('ratings as ratings' , 'value')->withSum('orderItems as sells' , 'quantity')->get();

        if ($s = $request->input('s')){
            $products = $products->filter(fn(Product $product) => Str::contains($product->name , $s) || Str::contains($product->description , $s));
        }

        if ($sort = $request->input('sort')){
            if ($sort === 'priceAsc'){
                $products = $products->sortBy('price');
            } elseif ($sort === 'priceDesc'){
                $products = $products->sortByDesc('price');
            } elseif ($sort === 'rating'){
                $products = $products->sortByDesc('ratings');
            } elseif ($sort === 'sell'){
                $products = $products->sortByDesc('sells');
            } elseif ($sort === 'newest'){
                $products = $products->sortByDesc('created_at');
            }
        }


        $total = $products->count();

        return ProductResource::collection($products->forPage($page , 5))->additional([
            'meta' => [
                'count' => $total ,
                'page' => $page ,
                'last_page' => ceil($total / 5) ,
            ]
        ]);

    }
}
