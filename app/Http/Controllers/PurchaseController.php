<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\purchase;
class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'products' => 'required|array',
            'products.*.product' => 'required|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
        ]);

                   
        $purchase = Purchase::create(['total_price'=>$request->total_price]);

        foreach ($data['products'] as $product) {
            $purchase->products()->attach($product['product'], ['quantity' => $product['qty']]);
        }

        return response()->json(['message' => 'Purchase recorded successfully'], 201);
    }

    public function index()
    {
        $purchases = Purchase::with('products:id,name')
            ->get()
            ->map(function ($purchase) {
                return [
                    'id' => $purchase->id,
                    'products' => $purchase->products->map(function ($product) {
                        return $product->name . '(' . $product->pivot->quantity . ')';
                    })->implode(', '),
                    'total_price'=>$purchase->total_price,
                    'created_at' => $purchase->created_at->toDateString(), // Assuming you want the date of the purchase
                ];
            });

        return response()->json(['data'=>$purchases]);
    }
}
