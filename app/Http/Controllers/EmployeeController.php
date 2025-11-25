<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use League\Csv\Writer;
use League\Csv\Reader;

class EmployeeController extends Controller
{
    public function index(Request $request)
{
    $search = $request->query('search');

    $employees = Employee::query()
        ->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })
        ->with('user')
        ->orderBy('id', 'DESC')   // 🔥 Latest employees first
        ->paginate(10);

    return view('employees.index', compact('employees'));
}


    public function create()
    {
        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->get();
        return view('employees.create', compact('supervisors'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'name'               => 'required|string|max:255',
        'email'              => 'nullable|email|unique:users,email',
        'position'           => 'required|string|max:255',
        'department'         => 'nullable|string|max:255',
        'salary'             => 'nullable|numeric|min:0',
        'currency'           => 'nullable|string|max:3',
        'hire_date'          => 'nullable|date',
        'manager_id'         => 'nullable|exists:users,id',
        'phone'              => 'nullable|string|max:20',
        'emergency_contact'  => 'nullable|string|max:20',
        'date_of_birth'      => 'nullable|date',
        'aadhar_no'          => 'nullable|string|max:20',
        'present_address_line1' => 'nullable|string|max:255',
        'present_city'       => 'nullable|string|max:100',
        'payment_method'     => 'nullable|in:manual_bank_transfer,cash',
        'role'               => 'required|in:Employee,Manager,HR Manager,Labor,Sales',
        'salary_basis'       => 'nullable|string|max:255',
        'labor_type'         => 'nullable|string|max:255',
        'employee_type'      => ['nullable', Rule::in(['Sales', 'Others'])],
        'employee_commission'=> 'nullable|numeric|min:0',
        'personal_documents.*'=> 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // === Create User (only for non-Labor with email) ===
    $user_id = null;
    if ($validated['role'] !== 'Labor' && !empty($validated['email'])) {
        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => bcrypt('password'),
            'manager_id' => $validated['manager_id'] ?? null,
        ]);
        $user->assignRole($validated['role']);
        $user_id = $user->id;
    }

    // === Handle File Uploads ===
    $personalDocuments = [];
    if ($request->hasFile('personal_documents')) {
        foreach ($request->file('personal_documents') as $file) {
            $path = $file->storeAs(
                'personal_documents',
                time() . '_' . $file->getClientOriginalName(),
                'public'
            );
            $personalDocuments[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
            ];
        }
    }

    // === Calculate Age ===
    $age = $validated['date_of_birth']
        ? Carbon::parse($validated['date_of_birth'])->age
        : null;

    // === Base Employee Data ===
    $employeeData = [
        'user_id'               => $user_id,
        'name'                  => $validated['name'],
        'email'                 => $validated['role'] === 'Labor' ? null : ($validated['email'] ?? null),
        'position'              => $validated['position'],
        'department'            => $validated['department'] ?? null,
        'phone'                 => $validated['phone'] ?? null,
        'emergency_contact'     => $validated['emergency_contact'] ?? null,
        'date_of_birth'         => $validated['date_of_birth'] ?? null,
        'age'                   => $age,
        'currency'              => $validated['currency'] ?? 'INR',
        'aadhar_no'             => $validated['aadhar_no'] ?? null,
        'present_address_line1' => $validated['present_address_line1'] ?? null,
        'present_city'          => $validated['present_city'] ?? null,
        'payment_method'        => $validated['payment_method'] ?? null,
        'personal_documents'    => !empty($personalDocuments) ? json_encode($personalDocuments) : null,
        'role'                  => $validated['role'],
    ];

    // === Role-Specific Logic ===
    switch ($validated['role']) {
        case 'Labor':
            $employeeData['salary_basis'] = $validated['salary_basis'] ?? null;
            $employeeData['labor_type']   = $validated['labor_type'] ?? null;
            $employeeData['salary']       = null;
            $employeeData['employee_type']= null;
            $employeeData['commission']   = null;
            break;

        case 'Sales':
            $employeeData['employee_type'] = 'Sales';
            $employeeData['salary']        = $validated['salary'] ?? 0;
            $employeeData['commission']    = $validated['employee_commission'] ?? 0;
            break;

        case 'Employee':
            $employeeData['employee_type'] = $validated['employee_type'] ?? 'Others';
            $employeeData['salary']        = $validated['salary'] ?? 0;
            $employeeData['commission']    = $validated['employee_commission'] ?? 0;
            break;

        default: // Manager, HR Manager
            $employeeData['salary']        = $validated['salary'] ?? 0;
            $employeeData['employee_type']= null;
            $employeeData['commission']    = null;
            break;
    }

    Employee::create($employeeData);

    return redirect()
        ->route('employees.index')
        ->with('success', 'Employee added successfully!');
}


// app/Http/Controllers/EmployeeController.php
public function earnings()
{
    $employees = \DB::table('employees as e')
        ->leftJoin('sales_commissions as sc', 'e.id', '=', 'sc.employee_id')
        ->select(
            'e.id',
            'e.name',
            'e.salary',
            \DB::raw('COALESCE(SUM(sc.commission_amount), 0) as total_commission'),
            \DB::raw('e.salary + COALESCE(SUM(sc.commission_amount), 0) as total_earnings')
        )
        ->groupBy('e.id', 'e.name', 'e.salary')
        ->get();

    return view('employees.earnings', compact('employees'));
}








    public function edit(Employee $employee)
{
    $managers = User::whereHas('roles', function ($query) {
        $query->where('name', 'manager');
    })->get(['id', 'name']); // optional: only select id & name

    return view('employees.edit', compact('employee', 'managers'));
}


public function update(Request $request, Employee $employee)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . ($employee->user_id ?? 'null'),
        'position' => 'required|string|max:255',
        'department' => 'nullable|string|max:255',
        'salary' => 'nullable|numeric|min:0',
        'currency' => 'nullable|string|max:3',
        'hire_date' => 'nullable|date',
        'manager_id' => 'nullable|exists:users,id',
        'is_remote' => 'boolean',
        'phone' => 'nullable|string|max:20',
        'emergency_contact' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'aadhar_no' => 'nullable|string|max:20',
        'personal_email' => 'nullable|email|unique:employees,personal_email,' . $employee->id,
        'present_address_line1' => 'nullable|string|max:255',
        'present_address_line2' => 'nullable|string|max:255',
        'present_city' => 'nullable|string|max:100',
        'permanent_address_line1' => 'nullable|string|max:255',
        'permanent_state' => 'nullable|string|max:100',
        'permanent_pin_code' => 'nullable|string|max:10',
        'payment_method' => 'nullable|in:manual_bank_transfer,cash',
        'role' => 'required|string|in:Employee,Manager,HR Manager,Labor,Sales',
        'salary_basis' => 'nullable|string|max:255',
        'labor_amount' => 'nullable|numeric|min:0',
        'labor_type' => 'nullable|string|max:255',
        'employee_type' => 'nullable|string|in:Sales,Others,Permanent,Contract',
        'employee_salary' => 'nullable|numeric|min:0',
        'employee_commission' => 'nullable|numeric|min:0',
        'personal_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // Handle User for non-Labor roles
    $user_id = $employee->user_id;
    if ($validated['role'] !== 'Labor') {
        if ($employee->user) {
            $employee->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'manager_id' => $validated['manager_id'] ?? null,
                'is_remote' => $validated['is_remote'] ?? false,
            ]);
            $employee->user->syncRoles([$validated['role']]);
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt('password'),
                'manager_id' => $validated['manager_id'] ?? null,
                'is_remote' => $validated['is_remote'] ?? false,
            ]);
            $user->assignRole($validated['role']);
            $user_id = $user->id;
        }
    } else {
        if ($employee->user) {
            $employee->user->delete();
            $user_id = null;
        }
    }

    // Handle file uploads
    $personalDocuments = $employee->personal_documents ? json_decode($employee->personal_documents, true) : [];
    if ($request->hasFile('personal_documents')) {
        foreach ((array)$request->file('personal_documents') as $file) {
            $personalDocuments[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $file->storeAs(
                    'personal_documents',
                    time() . '_' . $file->getClientOriginalName(),
                    'public'
                )
            ];
        }
    }

    // Calculate age if date_of_birth is provided
    $age = $validated['date_of_birth'] ? Carbon::parse($validated['date_of_birth'])->age : $employee->age;

    // Prepare employee data
    $employeeData = [
        'user_id' => $user_id,
        'name' => $validated['name'],
        'email' => $validated['role'] === 'Labor' ? null : $validated['email'] ?? null,
        'position' => $validated['position'],
        'department' => $validated['department'],
        'phone' => $validated['phone'] ?? null,
        'emergency_contact' => $validated['emergency_contact'] ?? null,
        'date_of_birth' => $validated['date_of_birth'] ?? null,
        'age' => $age,
        'currency' => $validated['currency'] ?? 'INR',
        'aadhar_no' => $validated['aadhar_no'] ?? null,
        'personal_email' => $validated['personal_email'] ?? null,
        'present_address_line1' => $validated['present_address_line1'] ?? null,
        'present_address_line2' => $validated['present_address_line2'] ?? null,
        'present_city' => $validated['present_city'] ?? null,
        'permanent_address_line1' => $validated['permanent_address_line1'] ?? null,
        'permanent_state' => $validated['permanent_state'] ?? null,
        'permanent_pin_code' => $validated['permanent_pin_code'] ?? null,
        'payment_method' => $validated['payment_method'] ?? null,
        'personal_documents' => !empty($personalDocuments) ? json_encode($personalDocuments) : null,
        'role' => $validated['role'],
    ];

   // Role-specific fields
if ($validated['role'] === 'Labor') {
    $employeeData['salary_basis'] = $validated['salary_basis'] ?? null;
    $employeeData['labor_amount'] = $validated['labor_amount'] ?? null;
    $employeeData['labor_type'] = $validated['labor_type'] ?? null;
    $employeeData['salary'] = null;
    $employeeData['employee_type'] = null;
    $employeeData['employee_commission'] = null;
} elseif (in_array($validated['role'], ['Employee', 'Sales'])) {
    $employeeData['employee_type'] = $validated['employee_type'] ?? null;
    $employeeData['salary'] = $validated['employee_salary'] ?? null;
    $employeeData['employee_commission'] = $validated['employee_commission'] ?? 0;
    $employeeData['salary_basis'] = null;
    $employeeData['labor_amount'] = null;
    $employeeData['labor_type'] = null;
} else {
    $employeeData['salary'] = $validated['salary'] ?? null;
}


    $employee->update($employeeData);

    return redirect()->route('employees.index')->with('success', __('Employee updated successfully!'));
}


    public function destroy(Employee $employee)
    {
        if ($employee->user) {
            $employee->user->delete();
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success', __('Employee deleted successfully!'));
    }

  

    public function payroll(Employee $employee)
    {
        $payrolls = Payroll::where('employee_id', $employee->id)->get();
        return view('employees.payroll', compact('employee', 'payrolls'));
    }

    public function storePayroll(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'tax_rate_select' => 'required|in:0,5,15,18,20,custom',
            'tax_rate' => 'required_if:tax_rate_select,custom|nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $taxRate = $validated['tax_rate_select'] === 'custom' ? $validated['tax_rate'] : $validated['tax_rate_select'];
        $taxAmount = $validated['amount'] * ($taxRate / 100);
        $totalAmount = $validated['amount'] + $taxAmount;

        Payroll::create([
            'employee_id' => $employee->id,
            'amount' => $validated['amount'],
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_date' => $validated['payment_date'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('employees.payroll', $employee)->with('success', __('Payroll added successfully!'));
    }

    public function attendance(Employee $employee)
    {
        $attendances = Attendance::where('employee_id', $employee->id)->orderBy('date', 'desc')->get();
        return view('employees.attendance', compact('employee', 'attendances'));
    }

    public function storeAttendance(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:attendances,date,' . $employee->id . ',employee_id',
            'status' => 'required|in:present,absent,leave',
            'check_in' => 'nullable|required_if:status,present|date_format:H:i',
            'check_out' => 'nullable|required_if:status,present|date_format:H:i|after:check_in',
            'notes' => 'nullable|string|max:1000',
        ]);

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $validated['date'],
            'status' => $validated['status'],
            'check_in' => $validated['status'] === 'present' ? $validated['check_in'] : null,
            'check_out' => $validated['status'] === 'present' ? $validated['check_out'] : null,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('employees.attendance', $employee)->with('success', __('Attendance recorded successfully!'));
    }

   public function show(Employee $employee)
{
    $payrolls = $employee->payrolls()->get();

    // Calculate annual & monthly salary if available
    $monthlyIncome = $employee->salary ?? 0;
    $annualIncome = $monthlyIncome * 12;

    // Salary Components (if Employee or Manager)
    $salaryComponents = [];
    if ($employee->role !== 'Labor') {
        $salaryComponents = [
            'Earnings' => [
                'Basic' => $monthlyIncome * 0.5,
                'Housing Allowance' => $monthlyIncome * 0.3,
                'Cost of Living Allowance' => $monthlyIncome * 0.15,
                'Other Allowance' => $monthlyIncome * 0.05,
            ],
            'Total Gross Pay' => $monthlyIncome,
        ];
    }

    // Deductions placeholder
    $deductions = [
        'GOSI Occupational Hazards' => [
            'Employers Contribution' => 0.00,
            'Employees Contribution' => 0.00,
            'Calculation Type' => '0.00% of Contributory Wages',
        ],
    ];

    // Decode multiple personal documents if present
    $documents = [];
    if ($employee->personal_documents) {
        $documents = json_decode($employee->personal_documents, true);
    }

    return view('employees.show', compact(
        'employee',
        'payrolls',
        'annualIncome',
        'monthlyIncome',
        'salaryComponents',
        'deductions',
        'documents'
    ));
}



public function export()
{
    $employees = Employee::all();

    $csv = Writer::createFromString("\xEF\xBB\xBF"); // UTF-8 BOM for Excel

    // ✅ Added Role & Labor Type in header
    $csv->insertOne([
        'Employee ID', 'Name', 'Email', 'Position', 'Department', 'Salary',
        'Phone', 'Hire Date', 'DOB', 'Age', 'National ID',
        'Personal Email', 'Present City', 'Permanent State',
        'Payment Method', 'Emergency Contact', 'Role', 'Labor Type', 'Created At', 'Updated At'
    ]);

    foreach ($employees as $employee) {
        $csv->insertOne([
            $employee->employee_id ?? 'N/A',
            $employee->name ?? 'N/A',
            $employee->email ?? 'N/A',
            $employee->position ?? 'N/A',
            $employee->department ?? 'N/A',
            $employee->salary ?? 'N/A',
            $employee->phone ?? 'N/A',
            $employee->hire_date ?? 'N/A',
            $employee->date_of_birth ?? 'N/A',
            $employee->age ?? 'N/A',
            $employee->igama_national_id ?? 'N/A', // ✅ corrected field name to match your model
            $employee->personal_email ?? 'N/A',
            $employee->present_city ?? 'N/A',
            $employee->permanent_state ?? 'N/A',
            $employee->payment_method ?? 'N/A',
            $employee->emergency_contact ?? 'N/A',
            $employee->role ?? 'N/A',          // ✅ new
            $employee->labor_type ?? 'N/A',    // ✅ new
            $employee->created_at ? $employee->created_at->format('Y-m-d H:i:s') : 'N/A',
            $employee->updated_at ? $employee->updated_at->format('Y-m-d H:i:s') : 'N/A',
        ]);
    }

    return response((string) $csv, 200, [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="employees.csv"',
    ]);
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt',
    ]);

    $file = $request->file('file');
    $handle = fopen($file->getRealPath(), 'r');

    if (!$handle) {
        return back()->with('error', 'Unable to open the file.');
    }

    $rawHeader = fgetcsv($handle);
    $header = array_map(function ($h) {
        return strtolower(str_replace([' ', '-', '/'], '_', trim($h)));
    }, $rawHeader);

    $imported = 0;
    $skipped = 0;

    while (($row = fgetcsv($handle)) !== false) {
        $data = @array_combine($header, $row);

        if (!$data || empty($data['employee_id']) || empty($data['name'])) {
            $skipped++;
            continue;
        }

        // 🧹 Clean helper
        $clean = function ($value) {
            return (!empty($value) && strtolower($value) !== 'n/a') ? trim($value) : null;
        };

        // 🗓 Convert and sanitize dates
        $hireDate = $clean($data['hire_date'] ?? null);
        $dob = $clean($data['dob'] ?? null);

        if ($hireDate) {
            $hireDate = date('Y-m-d', strtotime(str_replace('/', '-', $hireDate)));
        }
        if ($dob) {
            $dob = date('Y-m-d', strtotime(str_replace('/', '-', $dob)));
        }

        // 👷 Role & Labor Type Handling
        $role = $clean($data['role'] ?? null);
        $laborType = $clean($data['labor_type'] ?? null);

        // Default to Labor if role not specified
        if (empty($role)) {
            $role = 'Labor';
        }

        // If labor_type missing, use position as fallback
        if (empty($laborType)) {
            $laborType = $clean($data['position'] ?? null);
        }

        \App\Models\Employee::updateOrCreate(
            ['employee_id' => $clean($data['employee_id'])],
            [
                'name' => $clean($data['name']),
                'email' => $clean($data['email'] ?? null),
                'position' => $clean($data['position'] ?? null),
                'department' => $clean($data['department'] ?? null),
                'salary' => is_numeric($data['salary'] ?? null) ? $data['salary'] : 0,
                'phone' => $clean($data['phone'] ?? null),
                'hire_date' => $hireDate,
                'date_of_birth' => $dob,
                'age' => $clean($data['age'] ?? null),
                'igama_national_id' => $clean($data['national_id'] ?? null),
                'personal_email' => $clean($data['personal_email'] ?? null),
                'present_city' => $clean($data['present_city'] ?? null),
                'permanent_state' => $clean($data['permanent_state'] ?? null),
                'payment_method' => $clean($data['payment_method'] ?? null),
                'emergency_contact' => $clean($data['emergency_contact'] ?? null),
                'status' => 'active',
                'role' => $role,              // ✅ new
                'labor_type' => $laborType,   // ✅ new
            ]
        );

        $imported++;
    }

    fclose($handle);

    return redirect()->route('employees.index')->with(
        'success',
        "✅ Import complete — Imported: {$imported}, Skipped: {$skipped}"
    );
}



}