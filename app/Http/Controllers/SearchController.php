<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Log the search request for debugging
        Log::info('Search request received', ['query' => $request->input('query')]);

        $query = $request->input('query');
        $results = [];

        try {
            // Search Invoices with safer query binding
            $invoices = Invoice::where('id', 'like', '%' . $query . '%')
                ->orWhereHas('order.quotation.client', function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                }, '>=', 0) // Allow zero matches without failing
                ->take(5)
                ->get()
                ->map(function ($invoice) {
                    try {
                        $clientName = $invoice->order && $invoice->order->quotation && $invoice->order->quotation->client
                            ? $invoice->order->quotation->client->name
                            : 'N/A';
                    } catch (\Exception $e) {
                        Log::error('Error accessing invoice relationship', [
                            'invoice_id' => $invoice->id,
                            'error' => $e->getMessage(),
                        ]);
                        $clientName = 'N/A';
                    }

                    return [
                        'type' => 'Invoice',
                        'title' => "Invoice #{$invoice->id}",
                        'description' => $clientName,
                        'url' => route('sales.invoices.show', $invoice),
                    ];
                });

            // Search Clients
            $clients = Client::where('name', 'like', '%' . $query . '%')
                ->take(5)
                ->get()
                ->map(function ($client) {
                    return [
                        'type' => 'Client',
                        'title' => $client->name,
                        'description' => "Client ID: {$client->id}",
                        'url' => route('clients.edit', $client),
                    ];
                });

            // Search Products
            $products = Product::where('name', 'like', '%' . $query . '%')
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return [
                        'type' => 'Product',
                        'title' => $product->name,
                        'description' => "Product ID: {$product->id}",
                        'url' => route('products.edit', $product),
                    ];
                });

            // Search Employees
            $employees = Employee::where('name', 'like', '%' . $query . '%')
                ->take(5)
                ->get()
                ->map(function ($employee) {
                    return [
                        'type' => 'Employee',
                        'title' => $employee->name,
                        'description' => "Employee ID: {$employee->id}",
                        'url' => route('employees.edit', $employee),
                    ];
                });

            // Combine and sort results
            $results = collect([...$invoices, ...$clients, ...$products, ...$employees])
                ->sortBy('title')
                ->take(10)
                ->values()
                ->all();

            Log::info('Search results', ['results' => $results]);
        } catch (\Exception $e) {
            Log::error('Search failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Search failed due to an internal error'], 500);
        }

        return response()->json($results);
    }
}