<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SalesProductController extends Controller
{
    // Show all products to Salesperson
    public function index()
    {
        // Fetch products with warehouse stock
        $products = Product::with('warehouses')->paginate(12);

        // Return the products page
        return view('sales.products', compact('products'));
    }

    // Optional: Show single product details if needed
    public function show($id)
    {
       

        $product = Product::with('warehouses')->findOrFail($id);
        
        return view('sales.product_show', compact('product'));
    }
}
