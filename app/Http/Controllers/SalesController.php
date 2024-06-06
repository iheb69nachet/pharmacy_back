<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use App\Models\SalesProduct;
use App\Models\Sale;
use App\Models\Purchase;

use App\Models\Customer;
use App\Models\User;



class SalesController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validatedData) {
            $totalPrice = 0;
            $sale = Sale::create([
                'customer_id' => $validatedData['customer_id'],
                'total_price' => 0, 
            ]);
            foreach ($validatedData['products'] as $productData) {
                $product = Product::find($productData['product_id']);
                $price = $product->price * $productData['quantity'];
                $totalPrice += $price;
                SalesProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $price,
                ]);
            }
            $sale->update(['total_price' => $totalPrice]);
        });
        return response()->json(['message' => 'Sale created successfully'], 201);
    }
    public function fetchSales()
    {
        $sales = Sale::with(['customer', 'products.product'])
            ->get()
            ->map(function ($sale) {
                return [
                    'id'=>$sale->id,
                    'customer' => $sale->customer->first_name . ' ' . $sale->customer->last_name,
                    'products' => $sale->products->map(function ($salesProduct) {
                        return $salesProduct->product->name."(". $salesProduct->quantity.")";
                    })->implode(', '),
                    'total_price' => $sale->total_price,
                    'date' => $sale->created_at->toDateString(),
                ];
            });

        return response()->json(['data'=>$sales]);
    }
    public function getDashboardData()
    {
        $productCount = Product::count();
        $customerCount = Customer::count();
        $usersCount = User::count();

        $totalIncome = Sale::sum('total_price');
        $totalOutcome = Purchase::sum('total_price');

        $bestSellingProducts = Sale::with('products.product')
            ->get()
            ->flatMap(function ($sale) {
                return $sale->products;
            })
            ->groupBy('product_id')
            ->map(function ($productGroup) {
                return [
                    'product' => $productGroup->first()->product->name,
                    'sales by product' => $productGroup->sum('quantity'),
                ];
            })
            ->sortByDesc('sales by product')
            ->take(5)
            ->values();

        return response()->json([
            'data'=>['product_count' => $productCount,
            'customer_count' => $customerCount,
            'users_count' => $usersCount,
            'total_income' => $totalIncome,
            'best_selling_products' => $bestSellingProducts,
            "total_outcome"=>$totalOutcome
            ]
        ]);
    }
}
