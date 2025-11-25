<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function respond(Request $request)
    {
        $query = strtolower($request->message);
        $response = "Sorry, I didn't understand that. Try something like 'total invoices' or 'low stock'.";

        if(str_contains($query, 'total invoices')) {
            $count = DB::table('invoices')->count();
            $response = "Total invoices: $count";
        }
        elseif(str_contains($query, 'total sales')) {
            $count = Employee::where('employee_type', 'sales')->count();
            $response = "Total sales employees: $count";
        }
        elseif(str_contains($query, 'total labors')) {
            $count = Employee::where('role', 'Labor')->count();
            $response = "Total labors: $count";
        }
        elseif(str_contains($query, 'total batches')) {
            $count = DB::table('batches')->count();
            $response = "Total production batches: $count";
        }
        elseif(str_contains($query, 'low stock')) {
            $items = DB::table('products')->where('quantity', '<', 10)->pluck('name')->toArray();
            $response = $items ? "Low stock items: ".implode(', ', $items) : "No low stock items.";
        }

        return response()->json(['message' => $response]);
    }
}
