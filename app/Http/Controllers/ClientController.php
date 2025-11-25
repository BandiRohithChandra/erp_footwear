<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use App\Notifications\NewClientRegistered;
use App\Models\User;
use App\Models\Quotation;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ClientController extends Controller
{
    public function index(Request $request)
{
    // Base query: fetch users who are clients (category wholesale/retail)
    $query = User::whereIn('category', ['wholesale', 'retail'])
                 ->with(['salesRep', 'salesRep.commissions']); // eager load sales rep info

    // Search filter
    if ($request->filled('search')) {
        $search = $request->get('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('business_name', 'like', "%{$search}%")
              ->orWhere('gst_no', 'like', "%{$search}%");
        });
    }

    // Pagination (10 per page)
    $clients = $query->orderBy('created_at', 'desc')->paginate(10);

    // Preserve search query in pagination links
    $clients->appends($request->only('search'));

    // Return view
    return view('sales.clients.index', compact('clients'));
}


    public function create()
    {
        // Fetch all sales reps
        $salesReps = User::whereHas('employee', function($q){
            $q->where('employee_type', 'sales');
        })->get();

        return view('sales.clients.create', compact('salesReps'));
    }


public function store(Request $request)
{
    $isOfflineAdmin = auth()->user()->is_remote === 0;
    Log::info('🔹 Step 1: Add client initiated', [
        'user_id' => auth()->id(),
        'isOfflineAdmin' => $isOfflineAdmin
    ]);

    $rules = [
        'business_name'          => 'required|string|max:255',
        'category'               => 'required|in:wholesale,retail',
        'gst_no'                 => 'required|string|max:50',
        'gst_certificate'        => 'required|file|mimes:jpeg,png,jpg,pdf',
        'name'                   => 'nullable|string|max:255',
        'email'                  => 'nullable|email|unique:users,email',
        'sales_rep_id'           => 'nullable|exists:users,id',
        'phone'                  => 'nullable|string|max:20',
        'address'                => 'nullable|string',
        'company_document'       => 'nullable|file|mimes:jpeg,png,jpg,pdf',
        'aadhar_number'          => 'nullable|string|max:20',
        'aadhar_certificate'     => 'nullable|file|mimes:jpeg,png,jpg,pdf',
        'electricity_certificate'=> 'nullable|file|mimes:jpeg,png,jpg,pdf',
        'password'               => 'nullable|confirmed|min:6',
    ];

    try {
        Log::info('🔹 Step 2: Starting validation');
        $validatedData = $request->validate($rules);
        Log::info('✅ Step 2 Passed: Validation successful', ['validatedData' => $validatedData]);

        // Hash password or generate random one
        $validatedData['password'] = !empty($request->password)
            ? bcrypt($request->password)
            : bcrypt(\Illuminate\Support\Str::random(8));

        Log::info('🔹 Step 3: Password hashed');

        // Handle file uploads
        foreach (['company_document','aadhar_certificate','gst_certificate','electricity_certificate'] as $fileField) {
            if ($request->hasFile($fileField)) {
                Log::info("🔹 Step 4: File upload detected for {$fileField}");
                $file = $request->file($fileField);
                $path = $file->store('client_documents', 'public');
                $validatedData[$fileField] = $path;
                Log::info("✅ Step 4 Success: {$fileField} stored", ['path' => $path]);
            } else {
                Log::info("ℹ️ Step 4: No file uploaded for {$fileField}");
            }
        }

        // Create the user (client)
        Log::info('🔹 Step 5: Creating user/client record');
        $user = \App\Models\User::create([
            'name'                   => $validatedData['name'] ?? $validatedData['business_name'],
            'email'                  => $validatedData['email'] ?? null,
            'phone'                  => $validatedData['phone'] ?? null,
            'address'                => $validatedData['address'] ?? null,
            'business_name'          => $validatedData['business_name'],
            'category'               => $validatedData['category'],
            'gst_no'                 => $validatedData['gst_no'],
            'company_document'       => $validatedData['company_document'] ?? null,
            'aadhar_number'          => $validatedData['aadhar_number'] ?? null,
            'aadhar_certificate'     => $validatedData['aadhar_certificate'] ?? null,
            'gst_certificate'        => $validatedData['gst_certificate'],
            'electricity_certificate'=> $validatedData['electricity_certificate'] ?? null,
            'password'               => $validatedData['password'],
            'status'                 => 'pending',
            'sales_rep_id'           => $validatedData['sales_rep_id'] ?? null,
        ]);

        Log::info('✅ Step 6 Success: User created', ['user_id' => $user->id]);

        // Return JSON for AJAX (modal) requests
        if ($request->expectsJson()) {
            Log::info('🔹 Step 7: Returning JSON response');
            return response()->json([
                'status'  => 'success',
                'message' => 'Client added successfully!',
                'client'  => $user,
            ]);
        }

        Log::info('✅ Step 8: Redirecting back with success');
        return redirect()->route('clients.index')->with('success', 'Client added successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('⚠️ Validation failed', ['errors' => $e->errors()]);
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        }
        throw $e;
    } catch (\Exception $e) {
        Log::error('❌ Exception occurred while creating client', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create client. ' . $e->getMessage(),
            ], 500);
        }
        return back()->with('error', 'Failed to create client. Check logs.');
    }
}



   public function show($id)
{
    $client = User::whereIn('category', ['Wholesale', 'Retail'])
                  ->with(['orders.products', 'salesRep'])
                  ->findOrFail($id);

    return view('sales.clients.show', compact('client'));
}


    public function edit(User $client)
    {
        $salesReps = User::whereHas('employee', function($q){
            $q->where('employee_type', 'sales');
        })->get();

        return view('sales.clients.edit', compact('client', 'salesReps'));
    }

    public function update(Request $request, User $client)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $client->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'company_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'gst_no' => 'nullable|string|max:20',
            'category' => 'required|in:wholesale,retail',
            'sales_rep_id' => 'required|exists:users,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->hasFile('company_document')) {
            if ($client->company_document && \Storage::disk('public')->exists($client->company_document)) {
                \Storage::disk('public')->delete($client->company_document);
            }
            $filePath = $request->file('company_document')->store('clients/documents', 'public');
        } else {
            $filePath = $client->company_document;
        }

        $updateData = [
            'business_name' => $validated['business_name'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'company_document' => $filePath,
            'gst_no' => $validated['gst_no'] ?? null,
            'category' => $validated['category'],
            'sales_rep_id' => $validated['sales_rep_id'], // update sales rep
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $client->update($updateData);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(User $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

public function quotations(Request $request)
{
    $clientId = auth()->id();

    $query = \App\Models\Quotation::with('salesperson', 'client')
        ->where('client_id', $clientId)
        ->orderBy('created_at', 'desc');

    if ($request->filled('search')) {
        $search = $request->get('search');
        $query->where(function ($q) use ($search) {
            $q->where('quotation_no', 'like', "%{$search}%")
              ->orWhereHas('salesperson', function($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    $quotations = $query->paginate(10);

    return view('clients.quotations.index', compact('quotations'));

}

public function showQuotation($id)
{
    $quotation = \App\Models\Quotation::with('products')
        ->where('id', $id)
        ->where('client_id', auth()->id())
        ->firstOrFail();

    return view('clients.quotations.show', compact('quotation'));
}

public function import(Request $request)
{
    if (!$request->hasFile('file')) {
        return back()->with('error', 'Please select a CSV file to upload.');
    }

    $file = $request->file('file');
    $rows = array_map('str_getcsv', file($file->getRealPath()));

    if (empty($rows) || count($rows) < 2) {
        return back()->with('error', 'CSV file is empty or invalid.');
    }

    // Normalize headers: lowercase + underscores
    $header = array_map(function ($h) {
        return strtolower(str_replace(' ', '_', trim($h)));
    }, $rows[0]);

    unset($rows[0]);

    $inserted = 0;

    foreach ($rows as $row) {
        $data = @array_combine($header, $row);
        if (!$data) continue;

        // Fix Excel scientific notation for phone
        if (!empty($data['phone']) && is_numeric($data['phone'])) {
            $data['phone'] = preg_replace('/[^\d]/', '', number_format($data['phone'], 0, '', ''));
        }

        // Fallback for missing email → generate a unique dummy email
        if (empty($data['email'])) {
            $data['email'] = strtolower(str_replace(' ', '_', $data['contact_name'] ?? 'client')) . '_' . uniqid() . '@example.com';
        }

        // Parse date safely
        $createdAt = null;
        if (!empty($data['created_at']) && strtotime($data['created_at']) !== false) {
            try {
                $createdAt = Carbon::parse($data['created_at'])->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $createdAt = now();
            }
        } else {
            $createdAt = now();
        }

        User::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['contact_name'] ?? null,
                'business_name' => $data['business_name'] ?? null,
                'phone' => $data['phone'] ?? null,
                'gst_no' => $data['gst_no'] ?? null,
                'category' => strtolower(trim($data['category'] ?? 'retail')),
                'status' => strtolower(trim($data['status'] ?? 'pending')),
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'pincode' => $data['pincode'] ?? null,
                'created_at' => $createdAt,
                'updated_at' => now(),
                'password' => bcrypt('default123'),
                'role' => 'Client',
            ]
        );

        $inserted++;
    }

    return back()->with('success', "✅ $inserted parties imported successfully!");
}

public function export()
{
    $fileName = 'clients_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

    $clients = \App\Models\User::whereIn('category', ['retail', 'wholesale'])->get([
        'id',
        'business_name',
        'name',
        'email',
        'phone',
        'gst_no',
        'category',
        'status',
        'city',
        'state',
        'pincode',
        'created_at',
    ]);

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $columns = [
        'ID',
        'Business Name',
        'Contact Name',
        'Email',
        'Phone',
        'GST No',
        'Category',
        'Status',
        'City',
        'State',
        'Pincode',
        'Created At',
    ];

    $callback = function () use ($clients, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($clients as $client) {
            fputcsv($file, [
                $client->id,
                $client->business_name,
                $client->name,
                $client->email,
                $client->phone,
                $client->gst_no,
                $client->category,
                ucfirst($client->status),
                $client->city,
                $client->state,
                $client->pincode,
                $client->created_at?->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);
    };

    return new StreamedResponse($callback, 200, $headers);
}


    /**
     * Accept a quotation
     */
    public function acceptQuotation(Quotation $quotation)
    {
        if ($quotation->client_id !== auth()->id()) {
            abort(403);
        }

        $quotation->status = 'accepted';
        $quotation->save();

        return back()->with('success', 'Quotation accepted successfully.');
    }

    /**
     * Reject a quotation
     */
    public function rejectQuotation(Quotation $quotation)
    {
        if ($quotation->client_id !== auth()->id()) {
            abort(403);
        }

        $quotation->status = 'rejected';
        $quotation->save();

        return back()->with('success', 'Quotation rejected successfully.');
    }


}
