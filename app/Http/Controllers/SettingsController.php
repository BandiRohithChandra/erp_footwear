<?php

namespace App\Http\Controllers;

use App\Events\RolePermissionsUpdated;
use App\Models\Settings;
use App\Models\User;
use App\Models\BankDetail; // ✅ already imported
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $defaultRegion = Settings::get('default_region', 'in'); // Default region India
        $defaultCurrency = Settings::get('default_currency', 'INR'); // Default currency INR

        // Ensure regions array has 'name' key for each
        $rawRegions = config('taxes.regions', []);
        $regions = [];
        foreach ($rawRegions as $code => $region) {
            $regions[$code] = [
                'name' => $region['name'] ?? strtoupper($code),
                'currency' => $region['currency'] ?? ($code === 'sa' ? 'SAR' : 'INR')
            ];
        }

        $logoPath = Settings::get('logo_path');
        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;

        // ✅ Fetch bank details (if any)
        $bankDetails = BankDetail::first();

        return view('settings.index', compact('defaultRegion', 'defaultCurrency', 'regions', 'logoUrl', 'bankDetails'));
    }

   public function update(Request $request)
{
    $request->validate([
        'company_name'      => 'nullable|string|max:255',
        'company_address'   => 'nullable|string|max:500',
        'company_gst'       => 'nullable|string|max:50',
        'company_phone'     => 'nullable|string|max:20',
        'company_email'     => 'nullable|email|max:255',
        'company_website'   => 'nullable|string|max:255',
        'company_logo'      => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        'default_region'    => 'required|in:sa,in',
    ]);

    // Read values
    $region = $request->default_region;
    $currency = $region === 'sa' ? 'SAR' : 'INR';

    // Save normal fields
    $fields = [
        'company_name',
        'company_address',
        'company_gst',
        'company_phone',
        'company_email',
        'company_website',
        'default_region',
        'default_currency'
    ];

    foreach ($fields as $field) {
        if ($request->has($field)) {
            Settings::set($field, $request->$field);
        }
    }

    // Save Logo
    if ($request->hasFile('company_logo')) {

        // Remove old logo
        $oldLogo = Settings::get('company_logo');
        if ($oldLogo && file_exists(public_path($oldLogo))) {
            unlink(public_path($oldLogo));
        }

        // Upload new logo
        $file = $request->file('company_logo');
        $name = 'company_logo_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/settings/' . $name;
        $file->move(public_path('uploads/settings'), $name);

        // Save logo path
        Settings::set('company_logo', $path);
    }

    return redirect()
        ->route('settings.index')
        ->with('success', __('Settings updated successfully!'));
}




    // ✅ NEW METHOD: Update or Create Bank Details
    public function updateBank(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
            'upi_id' => 'nullable|string|max:100',
        ]);

        // Create or update a single record
        BankDetail::updateOrCreate(['id' => 1], $validated);

        return redirect()->route('settings.index')->with('success', 'Bank details updated successfully!');
    }

    public function roles()
    {
        $users = User::with('roles')->get();
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('settings.roles', compact('users', 'roles', 'permissions'));
    }

    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles($validated['role']);
        return redirect()->route('settings.roles')->with('success', __('Role assigned successfully!'));
    }

    public function updateRolePermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        if ($role->name === 'employee') {
            event(new RolePermissionsUpdated($role));
        }

        return redirect()->route('settings.roles')->with('success', __('Role permissions updated successfully!'));
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'country' => 'nullable|string|in:' . implode(',', array_keys(config('countries.countries'))),
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'country' => $validated['country'],
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('settings.roles')->with('success', __('User created and role assigned successfully!'));
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        $countries = config('countries.countries');
        return view('settings.users.edit', compact('user', 'roles', 'countries'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'country' => 'nullable|string|in:' . implode(',', array_keys(config('countries.countries'))),
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'manager_id' => 'nullable|exists:users,id',
            'is_remote' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'manager_id' => $validated['manager_id'],
            'is_remote' => $validated['is_remote'] ?? false,
        ]);

        if ($validated['password']) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles($validated['roles']);

        return redirect()->route('settings.roles')->with('success', __('User updated successfully!'));
    }

    public function resetData(Request $request)
    {
        $request->validate([
            'confirm' => 'required|in:RESET'
        ], [
            'confirm.required' => 'You must type RESET to confirm.',
            'confirm.in' => 'Confirmation text is incorrect.'
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disable foreign keys

        try {
            // 🔹 Core ERP data
            DB::table('invoice_product')->truncate();
            DB::table('invoices')->truncate();
            DB::table('orders')->truncate();
            DB::table('quotations')->truncate();
            DB::table('batch_flows')->truncate();
            DB::table('raw_materials')->truncate();
            DB::table('employee_batch')->truncate();
            DB::table('employees')->truncate();
            DB::table('clients')->truncate();
            DB::table('products')->truncate();
            DB::table('batches')->truncate();
            DB::table('production_processes')->truncate();
            DB::table('production_orders')->truncate();
            DB::table('stock_arrivals')->truncate();
            DB::table('supplier_returns')->truncate();
            

            // 🔹 Payrolls and expenses
            DB::table('payrolls')->truncate();
            DB::table('expense_claims')->truncate();
            DB::table('worker_payrolls')->truncate();
            DB::table('salary_advances')->truncate();

            // 🔹 Suppliers
            DB::table('supplier_orders')->truncate();
            DB::table('suppliers')->truncate();

            // 🔹 Inventory-related tables
            DB::table('soles')->truncate();
            DB::table('liquid_materials')->truncate();
            DB::table('stocks')->truncate();
            DB::table('stock_movements')->truncate();

            // 🔹 Users (preserve current user)
            DB::table('users')->where('id', '!=', auth()->id())->delete();

        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Re-enable
        }

        return redirect()->route('settings.index')
            ->with('success', 'All ERP data has been reset successfully!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('settings.roles')->with('error', __('You cannot delete your own account!'));
        }

        $user->delete();
        return redirect()->route('settings.roles')->with('success', __('User deleted successfully!'));
    }

    public function activity()
    {
        $activities = Activity::latest()->paginate(15);
        return view('settings.activity', compact('activities'));
    }

    public function backup()
    {
        $backups = Storage::disk('local')->files('backups');
        return view('settings.backup', compact('backups'));
    }

    public function createBackup()
    {
        Artisan::call('backup:run', ['--only-db' => true]);
        return redirect()->route('settings.backup')->with('success', __('Backup created successfully!'));
    }

    public function downloadBackup($backup)
    {
        return Storage::disk('local')->download($backup);
    }

    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup' => 'required|string',
        ]);

        if (!Storage::disk('local')->exists($request->backup)) {
            return redirect()->route('settings.backup')->with('error', __('Backup file does not exist!'));
        }

        Artisan::call('backup:restore', ['--backup' => $request->backup]);
        return redirect()->route('settings.backup')->with('success', __('Backup restored successfully!'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $path = $request->file('logo')->store('logos', 'public');
        Settings::set('logo_path', $path);

        return redirect()->route('settings.index')->with('success', __('Logo updated successfully.'));
    }
}
