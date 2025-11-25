<?php
namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $showOnboarding = false;

        // Only for clients
        if ($user && $user->category && !$user->seen_onboarding) {
            $showOnboarding = true;
        }

        $categories = Product::select('category')->distinct()->pluck('category');

        return view('clients.dashboard', compact('categories', 'showOnboarding'));
    }

    public function markSeen()
{
    $user = auth()->user();
    $user->update(['seen_onboarding' => 1]);

    return response()->json(['success' => true]);
}


    public function category($category)
    {
        $products = Product::where('category', $category)->paginate(12);
        return view('clients.products-by-category', compact('products', 'category'));
    }

    public function products()
    {
        $products = Product::paginate(12);
        return view('clients.products', compact('products'));
    }

    public function show(Product $product)
    {
        return view('clients.product-detail', compact('product'));
    }
}
