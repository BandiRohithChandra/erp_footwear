<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardCard;
use App\Models\Order;
use App\Models\User;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class OnlineDashboardController extends Controller
{
    // Display dashboard
   public function index()
{
    $admin = Auth::user();

    // Fetch cards
    $cards = DashboardCard::where('admin_id', $admin->id)
        ->orderBy('position')
        ->get();

    if ($cards->isEmpty()) {
        $cards = $this->createDefaultCards($admin->id);
    }

    // Add dynamic counts directly to each card
    $cards->each(function ($card) {
        switch ($card->count_type) {
            case 'orders_new':
                $card->count = Order::whereIn('status', ['pending', 'placed', 'paid', 'partially_paid'])
                                    ->latest('created_at')
                                    ->take(10)
                                    ->count();
                break;
            case 'orders_pending': // now represents partially paid
    $card->count = Order::whereRaw('total - IFNULL(paid_amount,0) > 0')->count();
    break;

            case 'orders_placed':
                $card->count = Order::where('status', 'placed')->count();
                break;
            case 'orders_completed':
                $card->count = Order::whereIn('status', ['delivered', 'paid'])
                                    ->latest('created_at')
                                    ->take(10)
                                    ->count();
                break;

           case 'Total_clients':
            $card->count = User::whereIn('category', ['wholesale', 'retail'])->count();
            $card->link  = route('admin.clients.index'); // 👈 new line
            break;




            case 'articles':
                $card->count = Product::count();
                break;
            case 'total_sales':
    $card->count = Order::sum('total'); 
    break;

            case 'pending_payments':
                // Include both pending and partially paid orders
                $card->count = Order::whereRaw('total - IFNULL(paid_amount,0) > 0')->sum('total');
                break;
            default:
                $card->count = 0;
        }
    });

    return view('admin.online', compact('cards', 'admin'));
}

    // Create default cards
    private function createDefaultCards($adminId)
{
    $defaultCards = [
        ['title' => 'New Orders', 'count_type' => 'orders_new', 'link' => '/orders', 'icon' => 'icons/order.png', 'position' => 1],
        ['title' => 'Pending Orders', 'count_type' => 'orders_pending', 'link' => '/orders/pending', 'icon' => 'icons/pending.png', 'position' => 2],
        ['title' => 'Articles', 'count_type' => 'articles', 'link' => '/products', 'icon' => 'icons/product.png', 'position' => 3],
        ['title' => 'Total Sales', 'count_type' => 'total_sales', 'link' => route('admin.sales.total'), 'icon' => 'icons/sales.png', 'position' => 4],
        ['title' => 'Pending Payments', 'count_type' => 'pending_payments', 'link' => route('admin.orders.pending_payments'), 'icon' => 'icons/payments.png', 'position' => 5],
        ['title' => 'Total Clients', 'count_type' => 'Total_clients', 'link' => route('admin.clients.index'), 'icon' => 'icons/clients.png', 'position' => 6],

    ];

    $cards = collect();
    foreach ($defaultCards as $card) {
        $cards->push(DashboardCard::create(array_merge(['admin_id' => $adminId], $card)));
    }

    return $cards;
}


    // Edit dashboard
    public function edit()
    {
        $admin = Auth::user();
        $cards = DashboardCard::where('admin_id', $admin->id)
            ->orderBy('position')
            ->get();

        return view('admin.dashboard_edit', compact('cards'));
    }

    // Store new cards
    public function store(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'title' => 'required|string|max:255',
            'count_type' => 'nullable|array',
            'custom_metrics' => 'nullable|array',
        ]);

        // Predefined metrics
        if ($request->filled('count_type')) {
            foreach ($request->count_type as $metric) {
                if ($metric === 'on') continue;

                if (!DashboardCard::where('admin_id', $admin->id)->where('count_type', $metric)->exists()) {
                    DashboardCard::create([
                        'admin_id' => $admin->id,
                        'title' => ucfirst(str_replace('_', ' ', $metric)),
                        'count_type' => $metric,
                    ]);
                }
            }
        }

        // Custom metrics
        if ($request->filled('custom_metrics')) {
            foreach ($request->custom_metrics as $custom) {
                if (!empty($custom)) {
                    $slug = Str::slug($custom, '_');

                    if (!DashboardCard::where('admin_id', $admin->id)->where('count_type', $slug)->doesntExist()) {
                        DashboardCard::create([
                            'admin_id' => $admin->id,
                            'title' => ucfirst($custom),
                            'count_type' => $slug,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.online')->with('success', 'Dashboard updated successfully!');
    }

    // Update order status and return updated counts
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:accepted,processing,pending,placed,completed'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        // Return updated counts dynamically
        return $this->getCardCounts();
    }

    // Return JSON with current card counts
    public function getCardCounts()
{
    $admin = Auth::user();
    $cards = DashboardCard::where('admin_id', $admin->id)->get();

    // Compute counts dynamically
    $cards = $cards->map(function ($card) {
        switch ($card->count_type) {
            case 'orders_new':
                $card->count = Order::whereIn('status', ['pending', 'placed', 'paid', 'partially_paid'])
                                    ->latest('created_at')
                                    ->take(10)
                                    ->count();
                break;
           case 'orders_pending': // now represents partially paid
    $card->count = Order::whereRaw('total - IFNULL(paid_amount,0) > 0')->count();
    break;

            case 'orders_placed':
                $card->count = Order::where('status', 'placed')->count();
                break;
            case 'orders_completed':
                $card->count = Order::whereIn('status', ['delivered', 'paid'])
                                    ->latest('created_at')
                                    ->take(10)
                                    ->count();
                break;
            case 'articles':
                $card->count = Product::count();
                break;
            case 'total_sales':
                $card->count = Order::where('status', 'completed')->sum('total');
                break;
            case 'pending_payments':
                // Include both pending and partially paid orders
                $card->count = Order::whereRaw('total - IFNULL(paid_amount,0) > 0')->sum('total');
                break;
            default:
                $card->count = 0;
        }
        return $card;
    });

    return response()->json($cards);
}

    // Delete a card
    public function destroy($id)
    {
        DashboardCard::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard.edit')->with('success', 'Card deleted successfully');
    }
}
