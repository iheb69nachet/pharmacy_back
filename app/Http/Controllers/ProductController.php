<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Product;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->user() || !auth()->user()->hasRole('admin')) {
    //             throw UnauthorizedException::forRoles(['admin']);
    //         }
    //         return $next($request);
    //     })->only(['store', 'destroy']);
    // }
    public function listAll(){
        if(auth()->user()->hasPermissionTo('view products')){

            $users=Product::select('id','name', 'image_path', 'description','price','qty','type')->get();
            return response()->json([
                'success' => true,
                'data' =>  $users,
                
            ]);
        }else{
            throw UnauthorizedException::forPermissions(['view products']);
        }
    }
    public function store(Request $request)
    {

                if(auth()->user()->hasPermissionTo('publish products')){
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,jpeg,png',
            'description' => 'required|string|min:6',
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'type' => 'required|string|max:255',
        ]);

        $path = $request->file('file')->store('public/images');
        // $fullUrl = config('app.url') . Storage::url($path);
        $fullUrl = url(Storage::url($path));


        $product = Product::create([
            'name' => $request->name,
            'image_path' => $fullUrl,
            'description' => $request->description,
            'price' => $request->price,
            'qty' => $request->qty,
            'type' => $request->type,
        ]);

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
        }else{
            throw UnauthorizedException::forPermissions(['publish products']);
        }
    }
    public function getById(Product $id){
        if(auth()->user()->hasPermissionTo('view products')){
            $user=$id;
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }else{
            throw UnauthorizedException::forPermissions(['publish products']);
            }
    }
    public function destroy($id)
    {

        if(auth()->user()->hasPermissionTo('delete products')){
            try {
                $product = Product::findOrFail($id);
                $product->delete();
                return response()->json(['message' => 'Pro$product deleted successfully'], 200);
            } catch (\Exception $e) {
            return response()->json(['message' => 'Pro$product not found or could not be deleted'], 404);
            }
         }else{
            throw UnauthorizedException::forPermissions(['delete products']);
        }
    }
    public function update(Request $request, $id)
    {
        if(auth()->user()->hasPermissionTo('edit products')){
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string|min:6',
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'type' => 'required|string|max:255',
        ]);
        $product = Product::findOrFail($id);
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->qty = $request->input('qty');
        $product->type = $request->input('type');
        if ($request->hasFile('file')) {
            if ($product->image_path) {
                Storage::delete($product->image_path);
            }
            $path = $request->file('file')->store('public/products');
            $fullUrl = url(Storage::url($path));

            $product->image_path = $fullUrl;
        }
        $product->save();
        return response()->json(['message' => 'Product updated successfully!', 'data' => $product]);
         }else{
            throw UnauthorizedException::forPermissions(['edit products']);
        }
    }
}
