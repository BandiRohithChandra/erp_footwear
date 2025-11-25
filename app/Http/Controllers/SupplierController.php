<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'name'           => 'required|string|max:255',
            'business_name'  => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
            'gst_number'     => 'nullable|string|max:50',
            'material_types' => 'required|string|max:255', // ✅ new required field
        ]);

        // ✅ Create supplier
        Supplier::create([
            'business_name'  => $request->business_name,
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'gst_number'     => $request->gst_number,
            'material_types' => $request->material_types,
        ]);

        return redirect()->route('suppliers.index')
                         ->with('success', '✅ Supplier added successfully.');
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // ✅ Validation
        $request->validate([
            'name'           => 'required|string|max:255',
            'business_name'  => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
            'gst_number'     => 'nullable|string|max:50',
            'material_types' => 'required|string|max:255', // ✅ also validated on update
        ]);

        // ✅ Update supplier
        $supplier->update([
            'business_name'  => $request->business_name,
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'gst_number'     => $request->gst_number,
            'material_types' => $request->material_types,
        ]);

        return redirect()->route('suppliers.index')
                         ->with('success', '✅ Supplier updated successfully.');
    }

    /**
     * Display a single supplier.
     */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Export suppliers to CSV.
     */
    public function export()
    {
        $suppliers = Supplier::all();

        $filename = "suppliers_" . now()->format('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Business Name', 'Name', 'Email', 'Phone', 'GST Number', 'Address', 'Material Types', 'Created At'];

        $callback = function() use ($suppliers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($suppliers as $supplier) {
                fputcsv($file, [
                    $supplier->id,
                    $supplier->business_name,
                    $supplier->name,
                    $supplier->email,
                    $supplier->phone,
                    $supplier->gst_number,
                    $supplier->address,
                    $supplier->material_types,
                    $supplier->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import suppliers from CSV.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file'), 'r');
        $header = fgetcsv($file);

        $count = 0;
        DB::beginTransaction();

        try {
            while (($row = fgetcsv($file)) !== false) {
                if (empty($row[1]) && empty($row[2])) continue;

                Supplier::updateOrCreate(
                    [
                        'business_name' => trim($row[1] ?? ''),
                    ],
                    [
                        'name'           => $row[2] ?? null,
                        'email'          => $row[3] ?? null,
                        'phone'          => $row[4] ?? null,
                        'gst_number'     => $row[5] ?? null,
                        'address'        => $row[6] ?? null,
                        'material_types' => $row[7] ?? null,
                    ]
                );
                $count++;
            }

            fclose($file);
            DB::commit();

            return redirect()->back()->with('success', "✅ $count suppliers imported successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '⚠️ Error importing suppliers: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
                         ->with('success', '✅ Supplier deleted successfully.');
    }
}
