<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SalesCartController extends Controller
{
    // Show sales cart
    public function index()
    {
        $cart = session()->get('sales_cart', []);
        return view('sales.cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('sales_cart', []);
        $variations = $product->variations ?? [];

        $items = $request->input('items', []);

        if (empty($items)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        foreach ($items as $color => $itemData) {
            $color = $itemData['color'] ?? 'N/A';
            $quantities = $itemData['quantities'] ?? [];
            $price = $itemData['price'] ?? $product->price;

            if (!is_array($quantities) || empty($quantities)) {
                continue;
            }

            foreach ($quantities as $size => $quantity) {
                $quantity = (int) $quantity;
                if ($quantity <= 0) continue;

                $cartKey = $product->id . '_' . $color . '_' . $size;

                $variationImage = null;
                foreach ($variations as $var) {
                    if (($var['color'] ?? '') === $color && in_array($size, $var['sizes'] ?? []) && !empty($var['image'])) {
                        $variationImage = $var['image'];
                        break;
                    }
                }

                if (isset($cart[$cartKey])) {
                    $cart[$cartKey]['quantity'] += $quantity;
                } else {
                    $cart[$cartKey] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'color' => $color,
                        'size' => $size,
                        'quantity' => $quantity,
                        'price' => $price,
                        'image' => $variationImage ?? $product->image,
                    ];
                }
            }
        }

        session()->put('sales_cart', $cart);

        $message = "{$product->name} has been added to your cart.";

        return match($request->redirect_to) {
            'products' => redirect()->route('sales.products.index')->with('success', $message),
            'orders' => redirect()->route('sales.orders.create')->with('success', $message),
            default => redirect()->back()->with('success', $message),
        };
    }

    // Update quantity
    public function update(Request $request, $cartKey)
    {
        $cart = session()->get('sales_cart', []);
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = max(1, (int)$request->quantity);
            $cart[$cartKey]['color'] = $request->color ?? $cart[$cartKey]['color'];
            $cart[$cartKey]['size'] = $request->size ?? $cart[$cartKey]['size'];
            session(['sales_cart' => $cart]);
        }
        return redirect()->back()->with('success', 'Cart updated!');
    }

    // Remove product from cart
    public function remove($cartKey)
    {
        $cart = session()->get('sales_cart', []);
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session(['sales_cart' => $cart]);
        }
        return redirect()->back()->with('success', 'Product removed from cart!');
    }
}