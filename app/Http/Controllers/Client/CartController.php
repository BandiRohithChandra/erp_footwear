<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $cart = CartItem::with('product')->where('user_id', $userId)->get();

        return view('clients.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'items' => 'required|string', // JSON string of items
            'redirect_to' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $items = json_decode($request->items, true);

        if (!is_array($items) || count($items) === 0) {
            return redirect()->back()->with('error', 'Please select at least one size with quantity.');
        }

        foreach ($items as $item) {
            $productId = $item['product_id'] ?? null;
            $product = Product::findOrFail($productId);
            $color = $item['color'] ?? null;
            $size = $item['size'] ?? null;
            $qty = (int)($item['qty'] ?? 0);
            $price = $item['price'] ?? $product->price;
            $image = $item['image'] ?? $product->image;

            if ($qty <= 0) continue;

            $cartItem = CartItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('color', $color)
                ->where('size', $size)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $qty;
                $cartItem->price = $price;
                $cartItem->image = $image;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'color' => $color,
                    'size' => $size,
                    'quantity' => $qty,
                    'price' => $price,
                    'image' => $image,
                ]);
            }
        }

        $message = 'Products added to cart!';

        return match($request->redirect_to) {
            'products' => redirect()->route('client.products')->with('success', $message),
            'checkout' => redirect()->route('client.checkout')->with('success', $message),
            default => redirect()->back()->with('success', $message),
        };
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        if ($request->quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $userId = Auth::id();
        CartItem::where('user_id', $userId)->delete();

        return redirect()->back()->with('success', 'Cart cleared successfully.');
    }
}