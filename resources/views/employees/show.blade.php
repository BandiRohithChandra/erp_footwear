@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">{{ __('Employee Profile: ') }}{{ $employee->name }}</h1>
                <div class="mt-2">
                    <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                        {{ __('Back') }}
                    </a>
                </div>
            </div>
            <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                {{ __('Edit') }}
            </a>
        </div>

        <div class="mb-6">
            <ul class="flex space-x-4 border-b">
                <li class="pb-2"><a href="#overview" class="text-blue-600 hover:text-blue-800 border-b-2 border-transparent hover:border-blue-600">{{ __('Overview') }}</a></li>
                <li class="pb-2"><a href="#salary" class="text-blue-600 hover:text-blue-800 border-b-2 border-transparent hover:border-blue-600">{{ __('Salary Details') }}</a></li>
                <li class="pb-2"><a href="#payslips" class="text-blue-600 hover:text-blue-800 border-b-2 border-transparent hover:border-blue-600">{{ __('Payslips') }}</a></li>
                <li class="pb-2"><a href="#loans" class="text-blue-600 hover:text-blue-800 border-b-2 border-transparent hover:border-blue-600">{{ __('Loans') }}</a></li>
                <li class="pb-2"><a href="#documents" class="text-blue-600 hover:text-blue-800 border-b-2 border-transparent hover:border-blue-600">{{ __('Documents') }}</a></li>
            </ul>

           <div id="overview" class="mt-6">
    <h2 class="text-xl font-semibold mb-4">{{ __('Overview') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 bg-gray-50 rounded-lg">
            <p><strong>{{ __('Email:') }}</strong> {{ $employee->email ?? 'N/A' }}</p>
            <p><strong>{{ __('Position:') }}</strong> {{ $employee->position }}</p>
            <p><strong>{{ __('Department:') }}</strong> {{ $employee->department }}</p>
            <p><strong>{{ __('Hire Date:') }}</strong> {{ $employee->hire_date }}</p>
            <p><strong>{{ __('Role:') }}</strong> {{ $employee->role }}</p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg">
            <p><strong>{{ __('Phone:') }}</strong> {{ $employee->phone ?? 'N/A' }}</p>
            <p><strong>{{ __('Emergency Contact:') }}</strong> {{ $employee->emergency_contact ?? 'N/A' }}</p>
            <p><strong>{{ __('Date of Birth:') }}</strong> {{ $employee->date_of_birth }}</p>
            <p><strong>{{ __('Age:') }}</strong> {{ $employee->age }}</p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg">
            <!-- <p><strong>{{ __('Igama/National ID:') }}</strong> {{ $employee->igama_national_id ?? 'N/A' }}</p> -->
            <p><strong>{{ __('Personal Email:') }}</strong> {{ $employee->personal_email ?? 'N/A' }}</p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg">
            <p><strong>{{ __('Present Address:') }}</strong> {{ $employee->present_address_line1 }}, {{ $employee->present_city }}</p>
            <p><strong>{{ __('Permanent Address:') }}</strong> {{ $employee->permanent_address_line1 }}, {{ $employee->permanent_state }}</p>
        </div>

        @if($employee->role === 'Labor')
        <div class="p-4 bg-gray-50 rounded-lg">
            <p><strong>{{ __('Labor Type:') }}</strong> {{ $employee->labor_type }}</p>
            <p><strong>{{ __('Salary Basis:') }}</strong> {{ $employee->salary_basis }}</p>
            <p><strong>{{ __('Labor Amount:') }}</strong> {{ $employee->labor_amount }} {{ $employee->currency }}</p>
        </div>
        @elseif(in_array($employee->role, ['Employee','Sales']))
        <div class="p-4 bg-gray-50 rounded-lg">
            <p><strong>{{ __('Employee Type:') }}</strong> {{ $employee->employee_type ?? 'N/A' }}</p>
            <p><strong>{{ __('Salary:') }}</strong> {{ $employee->salary }} {{ $employee->currency }}</p>
            <p><strong>{{ __('Commission:') }}</strong> {{ $employee->commission }} {{ $employee->currency }}</p>
        </div>
        @else
        <div class="p-4 bg-gray-50 rounded-lg">
            <p><strong>{{ __('Salary:') }}</strong> {{ $employee->salary }} {{ $employee->currency }}</p>
        </div>
        @endif
    </div>
</div>


            <div id="salary" class="mt-6 hidden">
                <h2 class="text-xl font-semibold mb-4">{{ __('Salary Details') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium mb-2">{{ __('Add - Salary Details') }}</h3>
                        <p><strong>{{ __('Annual Income:') }}</strong> {{ number_format($annualIncome, 2) }} {{ $employee->currency }} {{ __('per year') }}</p>
                        <p><strong>{{ __('Monthly Income:') }}</strong> {{ number_format($monthlyIncome, 2) }} {{ $employee->currency }} {{ __('per month') }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-md font-medium mb-2">{{ __('Salary Components') }}</h4>
                        <ul class="list-disc pl-5">
                            @if (is_array($salaryComponents))
                                @foreach ($salaryComponents as $category => $components)
                                    @if (is_array($components))
                                        <li><strong>{{ $category }}</strong>
                                            <ul class="list-disc pl-5">
                                                @foreach ($components as $name => $amount)
                                                    <li>{{ $name }}: {{ number_format($amount, 2) }} {{ $employee->currency }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li>{{ __('No salary components available.') }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-md font-medium mb-2">{{ __('Other Deductions') }}</h4>
                        <ul class="list-disc pl-5">
                            @if (is_array($deductions))
                                @foreach ($deductions as $name => $details)
                                    @if (is_array($details))
                                        <li><strong>{{ $name }}</strong>
                                            <ul class="list-disc pl-5">
                                                @foreach ($details as $detail => $value)
                                                    <li>{{ $detail }}: {{ $value }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li>{{ __('No deductions available.') }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div id="payslips" class="mt-6 hidden">
                <h2 class="text-xl font-semibold mb-4">{{ __('Payslips') }}</h2>
                @forelse ($payrolls as $payroll)
                    <div class="p-4 bg-gray-50 rounded-lg mb-2">
                        <p><strong>{{ __('Date:') }}</strong> {{ $payroll->payment_date }} | <strong>{{ __('Amount:') }}</strong> {{ number_format($payroll->total_amount, 2) }} {{ $employee->currency }}</p>
                    </div>
                @empty
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p>{{ __('No payslips available.') }}</p>
                    </div>
                @endforelse
            </div>

            <div id="loans" class="mt-6 hidden">
                <h2 class="text-xl font-semibold mb-4">{{ __('Loans') }}</h2>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p>{{ __('Loan functionality not implemented yet.') }}</p>
                </div>
            </div>

           <div id="documents" class="mt-6 hidden">
    <h2 class="text-xl font-semibold mb-4">{{ __('Documents') }}</h2>
    <div class="p-4 bg-gray-50 rounded-lg">
        @if(count($documents) > 0)
            <ul class="list-disc pl-5">
                @foreach($documents as $doc)
                    <li>
                        <strong>{{ $doc['name'] }}:</strong> 
                        <a href="{{ asset('storage/'.$doc['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ __('View') }}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>{{ __('No document uploaded.') }}</p>
        @endif
    </div>
</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all tabs (sidebar HR dropdown and top nav) and content divs
            const tabs = document.querySelectorAll('a[href^="#"]');

// Only your tab content sections
const contents = document.querySelectorAll('#overview, #salary, #payslips, #loans, #documents');


            // Function to show the selected tab content
            function showTab(hash) {
                contents.forEach(content => {
                    content.classList.add('hidden');
                    if (content.id === hash.substring(1)) {
                        content.classList.remove('hidden');
                    }
                });
                tabs.forEach(tab => {
                    tab.classList.remove('border-blue-600');
                    if (tab.getAttribute('href') === hash) {
                        tab.classList.add('border-blue-600');
                    }
                });
                // Highlight HR dropdown in sidebar
                const hrDropdown = document.getElementById('hr-dropdown');
                if (hrDropdown && window.location.pathname.startsWith('/employees')) {
                    hrDropdown.classList.remove('hidden');
                    hrDropdown.previousElementSibling.classList.add('bg-gray-700', 'border-l-4', 'border-blue-500');
                    hrDropdown.previousElementSibling.querySelector('svg:last-child').classList.add('rotate-180');
                }
            }

            // Show the tab based on the URL hash or default to 'overview'
            let hash = window.location.hash || '#overview';
            showTab(hash);

            // Add click event listeners to tabs
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const hash = this.getAttribute('href');
                    window.location.hash = hash;
                    showTab(hash);
                });
            });

            // Handle hash change
            window.addEventListener('hashchange', function() {
                showTab(window.location.hash);
            });
        });
    </script>
@endsection