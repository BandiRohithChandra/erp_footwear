<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\AdminSalesController;
use App\Http\Controllers\SalaryAdvanceController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SupplierOrderController;
use App\Http\Controllers\WorkerPayrollController;
use App\Http\Controllers\DeliveryNoteController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductStageController;
use App\Http\Controllers\ClientNotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BatchFlowAssignmentController;
use App\Http\Controllers\Admin\OnlineDashboardController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmployeePortalController;
use App\Http\Controllers\ManagerPortalController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\WarningLetterController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\ExitEntryRequestController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SalesCartController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SalesProductController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LeaveManagementController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\BatchFlowController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\ProductionProcessController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});



Route::get('/chat', function () {
    return view('chat');
})->name('chat');




Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'hi', 'te'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');


Route::get('/terms', function () {
    return view('terms');
})->name('terms');  // remove ->middleware('auth')


Route::post('/chatbot-ask', [App\Http\Controllers\ChatbotController::class, 'ask'])->name('chatbot.ask');




Route::middleware(['auth'])->group(function () {
    Route::get('salary-advance', [SalaryAdvanceController::class, 'index'])->name('salary-advance.index');
    Route::get('salary-advance/create', [SalaryAdvanceController::class, 'create'])->name('salary-advance.create');
    Route::post('salary-advance', [SalaryAdvanceController::class, 'store'])->name('salary-advance.store');

    Route::get('salary-advance/{id}', [SalaryAdvanceController::class, 'show'])->name('salary-advance.show');
    Route::get('salary-advance/{id}/edit', [SalaryAdvanceController::class, 'edit'])->name('salary-advance.edit');
    Route::put('salary-advance/{id}', [SalaryAdvanceController::class, 'update'])->name('salary-advance.update');
    Route::delete('salary-advance/{id}', [SalaryAdvanceController::class, 'destroy'])->name('salary-advance.destroy'); // ✅ destroy route
});

Route::put('/settings/update-bank', [App\Http\Controllers\SettingsController::class, 'updateBank'])->name('settings.update-bank');


Route::patch('/sales/invoices/{invoice}/update-partial-payment', [InvoiceController::class, 'updatePartialPayment'])->name('invoices.updatePartialPayment');

Route::get('/soles/suggestions', [BatchflowController::class, 'suggestions']);


Route::post('/invoices/{id}/status', [App\Http\Controllers\InvoiceController::class, 'updateStatus'])
    ->name('invoices.updateStatus');


Route::middleware(['auth', 'force.password.change'])->group(function () {
    Route::get('/employee-portal', [EmployeePortalController::class, 'index'])->name('employee-portal.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['auth', 'can:view dashboard'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
        // Accept both PUT and POST
        Route::match(['put', 'post'], '/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');
        Route::delete('/dashboard/custom-card/{key}', [DashboardController::class, 'destroyCustomCard'])
            ->name('dashboard.customCard.destroy');


    });


    // Bulk Accept (NEW)



    Route::middleware('can:view inventory')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    });


    Route::get('/delivery-note/create/{batch}', [App\Http\Controllers\DeliveryNoteController::class, 'create'])
        ->name('delivery.note.create');

    Route::get('/delivery-note/{deliveryNote}', [App\Http\Controllers\DeliveryNoteController::class, 'show'])
        ->name('delivery.note.show');



    // Add this to your routes/web.php
    Route::post('/products/add-item', [App\Http\Controllers\ProductController::class, 'addItem'])->name('products.add-item');


    Route::post('/production-orders/bulk-accept', [ProductionOrderController::class, 'bulkAccept'])
        ->name('production-orders.bulk-accept');

    Route::get('/soles/colors/{productId}', [RawMaterialController::class, 'getColorsByProduct']);


    Route::post('suppliers/import', [SupplierController::class, 'import'])->name('suppliers.import');
    Route::get('suppliers/export', [SupplierController::class, 'export'])->name('suppliers.export');
    Route::resource('suppliers', SupplierController::class);



    // ================================
// RETURN ORDER ROUTES (PUT THESE FIRST)
// ================================
Route::get('/supplier-orders/returns', 
    [SupplierOrderController::class, 'returns'])
    ->name('supplier-orders.returns');

Route::get('/supplier-orders/{orderId}/return', 
    [SupplierOrderController::class, 'createReturn'])
    ->name('supplier-orders.return.create');

Route::post('/supplier-orders/return/store', 
    [SupplierOrderController::class, 'storeReturn'])
    ->name('supplier-orders.return.store');

Route::get('/supplier-orders/returns/{id}/edit', 
    [SupplierOrderController::class, 'editReturn'])
    ->name('supplier-orders.return.edit');

Route::post('/supplier-orders/returns/{id}/save', 
    [SupplierOrderController::class, 'updateReturn']
)->name('supplier-orders.return.save');


Route::get('/supplier-returns/{id}/bill', [SupplierOrderController::class, 'returnBill'])
     ->name('supplier-orders.returns.bill');

Route::get('/transactions/fetch-items', [TransactionController::class, 'fetchItems'])
    ->name('transactions.fetch-items');




Route::get('/supplier-orders/returns/{id}', 
    [SupplierOrderController::class, 'showReturn'])
    ->name('supplier-orders.return.show');

Route::post('/supplier-orders/returns/{id}/complete', 
    [SupplierOrderController::class, 'completeReturn'])
    ->name('supplier-orders.return.complete');



    Route::resource('supplier-orders', SupplierOrderController::class);


    Route::patch('/supplier-orders/{order}/status', [SupplierOrderController::class, 'updateStatus'])
        ->name('supplier-orders.updateStatus');

// List all return orders
Route::get('/supplier-orders/returns', 
    [SupplierOrderController::class, 'returns'])
    ->name('supplier-orders.returns');

// Show create return page for a specific order
Route::get('/supplier-orders/{orderId}/return', 
    [SupplierOrderController::class, 'createReturn'])
    ->name('supplier-orders.return.create');

// Store the return order
Route::post('/supplier-orders/return/store', 
    [SupplierOrderController::class, 'storeReturn'])
    ->name('supplier-orders.return.store');




    Route::middleware('can:manage inventory')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');

        Route::get('/products/import', [ProductController::class, 'importForm'])->name('products.import');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import.store');
    });

    Route::get('/admin/sales/details', [App\Http\Controllers\Admin\AdminSalesController::class, 'details'])->name('admin.sales.details');

    Route::post('admin/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('admin.orders.update_status');

    Route::prefix('admin')->middleware(['auth'])->group(function () {
        // Invoice view route
        Route::get('/invoice/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('admin.invoice.show');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/sales/invoices', [SalesOrderController::class, 'indexInvoices'])->name('sales.invoices.index');
    });





    // web.php
// web.php
    Route::post('/onboarding-seen', [OnboardingController::class, 'seen'])->name('onboarding.seen');


    // Export filtered orders as CSV
    Route::post('/admin/orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('admin.orders.export');

    // routes/web.php
    Route::get('/admin/dashboard/card-counts', [OnlineDashboardController::class, 'getCardCounts'])
        ->name('admin.dashboard.card-counts');

    Route::prefix('batch-flow')->name('batch.flow.')->group(function () {

        Route::get('/create', [BatchFlowController::class, 'create'])->name('create');
        Route::post('/store', [BatchFlowController::class, 'store'])->name('store');

        Route::get('/card', [BatchFlowController::class, 'card'])->name('card');

        // Static route must come before dynamic {id}
        Route::get('/update-status', [BatchFlowController::class, 'updateStatus'])->name('update_status');

        Route::get('/', [BatchFlowController::class, 'index'])->name('index');
        Route::get('/{id}', [BatchFlowController::class, 'show'])->name('show');
        Route::post('/{id}/update-stage', [BatchFlowController::class, 'updateStage'])->name('updateStage');
        // Save status POST route
        Route::post('/save-status', [BatchFlowController::class, 'saveStatus'])->name('save_status');
    });

    Route::get('/batch-flow/card', [BatchFlowController::class, 'card'])->name('batch.flow.card');

   Route::post('/delivery-note/partial', [DeliveryNoteController::class, 'storePartial'])
    ->name('delivery.note.storePartial');


    Route::get('/delivery-notes/batch/{batchId}', [DeliveryNoteController::class, 'byBatch'])
        ->name('delivery.notes.byBatch');
    // web.php
    Route::put('/delivery-notes/{id}/update', [DeliveryNoteController::class, 'updatePartial'])->name('delivery.note.updatePartial');




    Route::prefix('worker-payroll')->group(function () {
        Route::get('/', [WorkerPayrollController::class, 'index'])->name('payrolls.worker_payroll_index');
        Route::get('/create', [WorkerPayrollController::class, 'create'])->name('payrolls.worker_payroll_create');
        Route::post('/store', [WorkerPayrollController::class, 'store'])->name('payrolls.worker_payroll_store');

        // **Edit payroll for a specific employee in a batch**
        Route::get('/{batch}/{employee}/edit', [WorkerPayrollController::class, 'edit'])->name('payrolls.worker_payroll_edit');

        // Toggle labor status (pending / paid)
        Route::post('/{batch}/{employee}/toggle', [WorkerPayrollController::class, 'toggleStatus'])->name('payrolls.worker_payroll_toggle');
        Route::put('/{assignment}/update', [WorkerPayrollController::class, 'update'])->name('payrolls.worker_payroll_update'); // <--- add thi
    });

    Route::post('/worker-payroll/pay-batches', [WorkerPayrollController::class, 'payBatches'])
        ->name('payrolls.pay_batches');


    Route::get('/worker-payroll/{employee}', [WorkerPayrollController::class, 'show'])->name('payrolls.worker_show');

    Route::post('/chat/respond', [ChatController::class, 'respond'])->name('chat.respond');


    // Accept a single quotation
    Route::patch('/quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');



    Route::get('/stages', [ProductStageController::class, 'index'])->name('stages.index');
    Route::get('/stages/{id}', [ProductStageController::class, 'show'])->name('stages.show');
    Route::post('/stages/{id}', [ProductStageController::class, 'update'])->name('stages.update');

    Route::resource('batch-flow-assignments', BatchFlowAssignmentController::class);


    Route::resource('workers', WorkerController::class);



    Route::middleware(['auth'])->group(function () {
        Route::patch('/company/update', [CompanyController::class, 'update'])->name('company.update');
    });


    // Show the support form
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support.index');

    // Handle form submission
    Route::post('/support', [App\Http\Controllers\SupportController::class, 'store'])->name('support.store');


    Route::get('client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');

    // Route to show products by category
    Route::get('dashboard/category/{category}', [ClientDashboardController::class, 'category'])
        ->name('client.category.products');

    Route::post('/client/onboarding/seen', [ClientDashboardController::class, 'markSeen'])
        ->name('client.onboarding.seen')
        ->middleware('auth');




    Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {

        // Notifications
        Route::get('notifications', [ClientNotificationController::class, 'index'])->name('notifications');

        // Cart
        Route::get('cart', [CartController::class, 'index'])->name('cart');
        Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::get('cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
        Route::put('cart/update/{key}', [CartController::class, 'update'])->name('cart.update');

        // Products
        Route::get('products', [ClientDashboardController::class, 'products'])->name('products');

        Route::get('products/{product}', [ClientDashboardController::class, 'show'])->name('products.show');

        // Orders
        Route::get('orders', [ClientOrderController::class, 'index'])->name('orders');
        Route::get('orders/{id}/invoice', [ClientOrderController::class, 'invoice'])->name('orders.invoice'); // invoice must be first
        Route::get('orders/{id}', [ClientOrderController::class, 'show'])->name('orders.show'); // order details

        // Checkout
        Route::get('checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
        Route::post('checkout/pay', [\App\Http\Controllers\CheckoutController::class, 'pay'])->name('checkout.pay');

        // Dynamic client page (last)
        Route::get('{client}', [ClientController::class, 'show'])->name('show');
    });




    Route::get('/production-orders/{id}', [ProductionOrderController::class, 'show'])
        ->name('production-orders.show');




    Route::get('/thank-you', function () {
        return view('auth.thankyou');
    })->name('auth.thankyou');


    Route::post('/clients/{client}/approve', [RegisteredUserController::class, 'approve'])->name('clients.approve');
    Route::post('/clients/{client}/reject', [RegisteredUserController::class, 'reject'])->name('clients.reject');


    Route::get('/my-sales-orders', [SalesOrderController::class, 'myOrders'])->name('my-sales-orders');



    Route::prefix('admin')->middleware(['auth'])->group(function () {
        // Sales
        Route::get('/sales/total', [AdminSalesController::class, 'index'])->name('admin.sales.total');
    });

    Route::get('/orders', [ProductionOrderController::class, 'index'])->name('orders.index');


    // Route::prefix('admin')->name('admin.')->group(function() {
//     Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
// });

    Route::post('/sales/orders', [SalesOrderController::class, 'store'])
        ->name('sales.orders.store');

    Route::get('/sales/clients/{client}', [ClientController::class, 'show'])
        ->name('clients.show')
        ->middleware(['auth', 'can:manage sales']);


    Route::middleware(['auth'])->group(function () {
        Route::get('/production-orders/{order}/deliver', [ProductionOrderController::class, 'showDeliverForm'])
            ->name('production-orders.deliver.form');



        Route::post('/production-orders/{order}/deliver', [ProductionOrderController::class, 'processDelivery'])
            ->name('production-orders.deliver.process');
    });



    // web.php
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');


    Route::prefix('sales/cart')->name('sales.cart.')->group(function () {
        Route::get('/', [SalesCartController::class, 'index'])->name('index');
        Route::post('/add/{id}', [SalesCartController::class, 'add'])->name('add');

        // Update should match PUT method
        // Use where() to allow any characters, including slashes
        Route::match(['put', 'post'], '/update/{cartKey}', [SalesCartController::class, 'update'])
            ->where('cartKey', '.*')->name('update');

        // Remove can stay GET, but make sure cartKey accepts all characters
        Route::get('/remove/{cartKey}', [SalesCartController::class, 'remove'])
            ->where('cartKey', '.*')->name('remove');
    });


    Route::get('/sales/orders/{order}/invoice', [SalesOrderController::class, 'generateInvoice'])->name('sales.orders.invoice');



    // Route to handle multiple sizes Add to Cart via AJAX
    Route::post('/sales/cart/multiple-add', [SalesOrderController::class, 'multipleAdd'])
        ->name('sales.cart.multipleAdd');



    Route::group(['middleware' => ['auth']], function () {
        // List all products
        Route::get('/sales/products', [SalesProductController::class, 'index'])
            ->name('sales.products.index');

        // Show single product details
        Route::get('/sales/products/{product}', [SalesProductController::class, 'show'])
            ->name('sales.products.show');
    });
    Route::put('/client/cart/update/{key}', [CartController::class, 'update'])->name('client.cart.update');


    Route::get('/sales/orders/create/{product?}', [SalesOrderController::class, 'create'])
        ->name('sales.orders.create');



    // web.php
    Route::patch('/batch-flow-assignments/{assignment}/status', [BatchFlowAssignmentController::class, 'updateStatus'])
        ->name('batch-flow-assignments.updateStatus')
        ->middleware('auth');


    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::get('/orders/pending-payments', [App\Http\Controllers\Admin\OrderController::class, 'pendingPayments'])->name('admin.orders.pending_payments');
        Route::post('/orders/{id}/update-payment', [App\Http\Controllers\Admin\OrderController::class, 'updatePayment'])->name('orders.updatePayment');

    });





    Route::prefix('admin')->middleware(['auth'])->group(function () {

        // Dashboard
        Route::get('/online', [OnlineDashboardController::class, 'index'])->name('admin.online');
        Route::get('/dashboard/edit', [OnlineDashboardController::class, 'edit'])->name('admin.dashboard.edit');
        Route::post('/dashboard/store', [OnlineDashboardController::class, 'store'])->name('admin.dashboard.store');
        Route::delete('/dashboard/delete/{id}', [OnlineDashboardController::class, 'destroy'])->name('admin.dashboard.delete');

        // Orders


    });


    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/orders/pending', [AdminOrderController::class, 'pending'])
            ->name('orders.pending');

        Route::get('/orders/placed', [AdminOrderController::class, 'placed'])
            ->name('orders.placed');

        Route::get('/orders/completed', [AdminOrderController::class, 'completed'])
            ->name('orders.completed');

        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        Route::get('/orders/pending-payments', [AdminOrderController::class, 'pendingPayments'])
            ->name('orders.pending_payments'); // Separate route for pending payments
    });



    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');





    // Route::prefix('admin/payments')->name('admin.payments.')->middleware('auth')->group(function () {
//     // Route::get('/pending', [PaymentController::class, 'pendingPayments'])->name('pending');
//     Route::get('/view/{invoice}', [PaymentController::class, 'viewInvoice'])->name('view');
//     Route::post('/mark-paid/{invoice}', [PaymentController::class, 'markPaid'])->name('markPaid');
// });

    Route::put('/admin/payments/{order}/mark-paid', [PaymentController::class, 'markPaid'])
        ->name('admin.payments.markPaid');







    Route::patch('/production-orders/{order}/update-status', [ProductionOrderController::class, 'updateStatus'])
        ->name('production-orders.update-status')
        ->middleware('auth');

    Route::get('/production-orders/my-orders', [ProductionOrderController::class, 'myOrders'])
        ->name('production-orders.my-orders')
        ->middleware('auth');


    Route::post('/clients/import', [ClientController::class, 'import'])->name('clients.import');
    Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');


    Route::get('/inventory/dashboard', [InventoryController::class, 'dashboard'])->name('inventory.dashboard');
    Route::put('/inventory/update-image/{id}', [InventoryController::class, 'updateImage'])->name('inventory.updateImage');

    Route::get('/inventory/dashboard', [InventoryController::class, 'dashboard'])
        ->name('inventory.dashboard')
        ->middleware(['auth', 'can:view inventory']);

    Route::prefix('production')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\ProductionController::class, 'dashboard'])->name('production.dashboard');
        // Route::get('/batch', [App\Http\Controllers\ProductionController::class, 'batch'])->name('production.batch');
        Route::get('/flow', [App\Http\Controllers\ProductionController::class, 'flow'])->name('production.flow');
        Route::get('/process', [App\Http\Controllers\ProductionController::class, 'process'])->name('production.process');
    });


    Route::middleware(['auth'])->group(function () {
        // Process Management Page
        Route::get('/production/process', [ProductionController::class, 'process'])
            ->name('production.process');

        // Create new process (form page)
        Route::get('/production/process/create', [ProductionController::class, 'createProcess'])
            ->name('production.createProcess');

        // Store new process
        Route::post('/production/process/store', [ProductionController::class, 'storeProcess'])
            ->name('production.storeProcess');

        // Edit process
        Route::get('/production/process/{id}/edit', [ProductionController::class, 'editProcess'])
            ->name('production.editProcess');

        // Update process
        Route::put('/production/process/{id}', [ProductionController::class, 'updateProcess'])
            ->name('production.updateProcess');

        // Delete process
        Route::delete('/production/process/{id}', [ProductionController::class, 'deleteProcess'])
            ->name('production.deleteProcess');
    });

    // Supply Chain Routes
    Route::prefix('supply-chain')->name('supply-chain.')->group(function () {
        Route::get('/', [App\Http\Controllers\SupplyChainController::class, 'dashboard'])->name('dashboard');
        Route::get('/active', [App\Http\Controllers\SupplyChainController::class, 'active'])->name('active');
        Route::get('/ontime', [App\Http\Controllers\SupplyChainController::class, 'ontime'])->name('ontime');
    });


    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');


    Route::resource('processes', ProcessController::class);
    Route::get('/employees/earnings', [EmployeeController::class, 'earnings'])->name('employees.earnings');

    // Route::get('/employees/export', [EmployeeController::class, 'exportEmployeesCsv'])->name('employees.export');


    Route::get('/production/process', [ProductionProcessController::class, 'index'])->name('production.process');
    Route::post('/production/process/update/{id}', [ProductionProcessController::class, 'updateStatus'])->name('production.process.update');


    Route::prefix('inventory')->middleware('can:manage inventory')->group(function () {
        Route::get('/warehouses', [InventoryController::class, 'warehouses'])->name('inventory.warehouses');
        Route::get('/warehouses/create', [InventoryController::class, 'createWarehouse'])->name('inventory.warehouses.create');
        Route::post('/warehouses', [InventoryController::class, 'storeWarehouse'])->name('inventory.warehouses.store');
        Route::get('/warehouses/{warehouse}/edit', [InventoryController::class, 'editWarehouse'])->name('inventory.warehouses.edit');
        Route::put('/warehouses/{warehouse}', [InventoryController::class, 'updateWarehouse'])->name('inventory.warehouses.update');
        Route::delete('/warehouses/{warehouse}', [InventoryController::class, 'destroyWarehouse'])->name('inventory.warehouses.destroy');

        Route::get('/adjustments', [InventoryController::class, 'adjustments'])->name('inventory.adjustments');
        Route::get('/adjustments/create', [InventoryController::class, 'createAdjustment'])->name('inventory.adjustments.create');
        Route::post('/adjustments', [InventoryController::class, 'storeAdjustment'])->name('inventory.adjustments.store');

        Route::get('/transfers', [InventoryController::class, 'transfers'])->name('inventory.transfers');
        Route::get('/transfers/create', [InventoryController::class, 'createTransfer'])->name('inventory.transfers.create');
        Route::post('/transfers', [InventoryController::class, 'storeTransfer'])->name('inventory.transfers.store');
    });

    Route::middleware('can:view finance')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });

    Route::middleware('can:manage finance')->group(function () {
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
        Route::post('/transactions/bulk-delete', [TransactionController::class, 'bulkDelete'])->name('transactions.bulk-delete');
    });

    Route::middleware('can:approve transactions')->group(function () {
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    });

    Route::middleware('can:view hr')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    });

    Route::middleware('can:manage hr')->group(function () {
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::get('/employees/{employee}/payroll', [EmployeeController::class, 'payroll'])->name('employees.payroll');
        Route::post('/employees/{employee}/payroll', [EmployeeController::class, 'storePayroll'])->name('employees.payroll.store');
        // Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');
        Route::get('/employees/{employee}/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');
        Route::post('/employees/{employee}/attendance', [EmployeeController::class, 'storeAttendance'])->name('employees.attendance.store');
        Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');

        Route::prefix('hr')->group(function () {
            Route::get('/warning-letters', [WarningLetterController::class, 'index'])->name('warning-letters.index');
            Route::get('/warning-letters/create', [WarningLetterController::class, 'create'])->name('warning-letters.create');
            Route::post('/warning-letters', [WarningLetterController::class, 'store'])->name('warning-letters.store');
            Route::post('/warning-letters/{warningLetter}/upload', [WarningLetterController::class, 'upload'])->name('warning-letters.upload');
            Route::get('/warning-letters/{warningLetter}/print', [WarningLetterController::class, 'print'])->name('warning-letters.print');

            Route::prefix('leave-management')->group(function () {
                Route::get('/', [LeaveManagementController::class, 'index'])->name('leave-management.index');
                Route::post('/approve/{leaveRequest}', [LeaveManagementController::class, 'approveLeave'])->name('leave-management.approve');
                Route::post('/reject/{leaveRequest}', [LeaveManagementController::class, 'rejectLeave'])->name('leave-management.reject');
            });

            Route::prefix('performance-reviews')->group(function () {
                Route::get('/', [PerformanceReviewController::class, 'index'])->name('performance-reviews.index');
            });

            Route::prefix('attendance')->group(function () {
                Route::get('/print', [AttendanceController::class, 'print'])->name('attendance.print');
            });

            Route::prefix('exit-entry-requests')->group(function () {
                Route::get('/', [ManagerPortalController::class, 'index'])->name('exit-entry-requests.index');
                Route::post('/approve/{exitEntryRequest}', [ManagerPortalController::class, 'approveExitEntry'])->name('exit-entry-requests.approve');
                Route::post('/reject/{exitEntryRequest}', [ManagerPortalController::class, 'rejectExitEntry'])->name('exit-entry-requests.reject');
            });

            Route::prefix('payrolls')->middleware('can:manage payroll')->group(function () {
                Route::get('/', [PayrollController::class, 'index'])->name('payrolls.index');
                Route::get('/create', [PayrollController::class, 'create'])->name('payrolls.create');
                Route::post('/', [PayrollController::class, 'store'])->name('payrolls.store');
                Route::get('/create-bulk', [PayrollController::class, 'createBulk'])->name('payrolls.create-bulk');
                Route::post('/store-bulk', [PayrollController::class, 'storeBulk'])->name('payrolls.store-bulk');
                Route::post('/{payroll}/approve/manager', [PayrollController::class, 'approveByManager'])->name('payrolls.approve.manager');
                Route::post('/{payroll}/reject/manager', [PayrollController::class, 'rejectByManager'])->name('payrolls.reject.manager');
                Route::post('/{payroll}/approve/finance', [PayrollController::class, 'approveByFinance'])->name('payrolls.approve.finance');
                Route::post('/{payroll}/reject/finance', [PayrollController::class, 'rejectByFinance'])->name('payrolls.reject.finance');
                Route::post('/{payroll}/disburse', [PayrollController::class, 'disburse'])->name('payrolls.disburse');
            });
        });
    });

    Route::prefix('hr/users')->group(function () {
        Route::get('/{user}/edit', [UserProfileController::class, 'edit'])->name('hr.users.edit');
        Route::put('/{user}', [UserProfileController::class, 'update'])->name('hr.users.update');
    });

    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });


    Route::prefix('employee-portal')->middleware('can:access employee portal')->group(function () {
        Route::get('/', [EmployeePortalController::class, 'index'])->name('employee-portal.index');
        Route::post('/mark-attendance', [EmployeePortalController::class, 'markAttendance'])->name('employee-portal.mark-attendance');
        Route::post('/request-leave', [EmployeePortalController::class, 'requestLeave'])->name('employee-portal.request-leave');
        Route::post('/request-advance-salary', [EmployeePortalController::class, 'requestAdvanceSalary'])->name('employee-portal.request-advance-salary');
        Route::post('/request-expense-claim', [EmployeePortalController::class, 'requestExpenseClaim'])->name('employee-portal.request-expense-claim');
        Route::post('/request-training', [EmployeePortalController::class, 'requestTraining'])->name('employee-portal.request-training');

        Route::prefix('exit-entry-requests')->group(function () {
            Route::get('/create', [ExitEntryRequestController::class, 'create'])->name('exit-entry-requests.create');
            Route::post('/store', [ExitEntryRequestController::class, 'store'])->name('exit-entry-requests.store');
        });

        Route::prefix('leave-management')->group(function () {
            Route::get('/', [LeaveManagementController::class, 'index'])->name('leave-management.index');
            Route::post('/request', [LeaveManagementController::class, 'requestLeave'])->name('leave-management.request');
        });

        Route::prefix('performance-reviews')->group(function () {
            Route::get('/', [PerformanceReviewController::class, 'index'])->name('performance-reviews.index');
        });

        Route::get('/warning-letters/{warningLetter}', [EmployeePortalController::class, 'showWarningLetter'])->name('employee-portal.warning-letter.show');
    });

    




    Route::prefix('sales')->group(function () {
        Route::middleware('can:view sales')->group(function () {
            Route::get('/', [SalesController::class, 'index'])->name('sales.index');
        });

        
        // Add these specific routes for actions
        Route::patch('quotations/{quotation}/send', [QuotationController::class, 'send'])->name('quotations.send');
        Route::patch('quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');
        Route::post('/quotations/bulk-accept', [QuotationController::class, 'bulkAccept'])
     ->name('quotations.bulkAccept');

        
        Route::patch('quotations/{quotation}/reject', [QuotationController::class, 'reject'])->name('quotations.reject');
        Route::patch('quotations/{quotation}/expire', [QuotationController::class, 'expire'])->name('quotations.expire');
        Route::delete('quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');

        // Print and Download routes
        Route::get('quotations/{quotation}/print', [QuotationController::class, 'print'])->name('quotations.print');
        Route::get('quotations/{quotation}/download', [QuotationController::class, 'download'])->name('quotations.download');

        // Optional: Bulk actions and export
        Route::post('quotations/bulk-update', [QuotationController::class, 'bulkUpdate'])->name('quotations.bulk-update');
        Route::get('quotations/export', [QuotationController::class, 'export'])->name('quotations.export');
        Route::get('quotations/next-number', [QuotationController::class, 'getNextNumber'])->name('quotations.next-number');
        // Route::get('quotations/{quotation}/invoice', [QuotationController::class, 'createInvoice'])
//     ->name('invoices.create');
// Create invoice from quotation (POST)
        Route::post('sales/quotations/{quotation}/invoice', [QuotationController::class, 'createInvoice'])
            ->name('sales.quotations.createInvoice');

        // Show invoice (for both orders & quotations)
        Route::get('sales/invoices/{invoice}', [QuotationController::class, 'showInvoice'])
            ->name('sales.quotations.invoice');




        Route::get('/sales/invoices', [InvoiceController::class, 'index'])
            ->name('sales.invoices.index');



        // Client Quotations
        Route::middleware(['auth'])->group(function () {
            Route::get('/client/quotations', [ClientController::class, 'quotations'])->name('client.quotations');

            Route::patch('/client/quotations/{quotation}/accept', [ClientController::class, 'acceptQuotation'])->name('client.quotations.accept');
            Route::patch('/client/quotations/{quotation}/reject', [ClientController::class, 'rejectQuotation'])->name('client.quotations.reject');
        });

        Route::middleware(['auth'])->group(function () {
            Route::get('/client/quotations/{quotation}', [ClientController::class, 'showQuotation'])->name('client.quotations.show');

        });




        Route::middleware('can:manage sales')->group(function () {
            Route::prefix('quotations')->group(function () {
                Route::get('/', [QuotationController::class, 'index'])->name('quotations.index');
                Route::get('/create', [QuotationController::class, 'create'])->name('quotations.create');
                Route::post('/', [QuotationController::class, 'store'])->name('quotations.store');
                Route::get('/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
                Route::get('/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
                Route::put('/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
                Route::post('/{quotation}/approve', [QuotationController::class, 'approve'])->name('quotations.approve');
                Route::post('/{quotation}/cancel', [QuotationController::class, 'cancel'])->name('quotations.cancel');
                Route::post('/{quotation}/send', [QuotationController::class, 'send'])->name('quotations.send');
            });

            Route::prefix('invoices')->group(function () {
                Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
                Route::get('/create/{order}', [InvoiceController::class, 'create'])->name('sales.invoices.create');
                Route::post('/store/{order}', [InvoiceController::class, 'store'])->name('sales.invoices.store');
                Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('sales.invoices.show');
                Route::post('/{invoice}/record-payment', [InvoiceController::class, 'recordPayment'])->name('sales.invoices.record-payment');
                Route::get('/{invoice}/download-pdf', [InvoiceController::class, 'downloadPDF'])->name('sales.invoices.download-pdf');
            });

            Route::get('/sales/invoices/pending-payments', [SalesController::class, 'pendingPayments'])
                ->name('invoices.pending-payments')
                ->middleware(['auth', 'can:manage sales']);

            Route::prefix('sales')->group(function () {
                Route::middleware('can:manage sales')->group(function () {
                    Route::prefix('clients')->group(function () {
                        Route::get('/', [ClientController::class, 'index'])->name('clients.index');
                        Route::get('/create', [ClientController::class, 'create'])->name('clients.create');
                        Route::post('/', [ClientController::class, 'store'])->name('clients.store');
                        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
                        Route::put('/{client}', [ClientController::class, 'update'])->name('clients.update');
                        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
                    });
                });
            });

            Route::get('/dashboard', [SalesController::class, 'dashboard'])->name('sales.dashboard');
            Route::get('/product-details', [SalesController::class, 'getProductDetails'])->name('sales.getProductDetails');
            Route::get('/create', [SalesController::class, 'create'])->name('sales.create');
            Route::post('/', [SalesController::class, 'store'])->name('sales.store');
            Route::get('/{sale}', [SalesController::class, 'show'])->name('sales.show');
            Route::get('/{sale}/edit', [SalesController::class, 'edit'])->name('sales.edit');
            Route::put('/{sale}', [SalesController::class, 'update'])->name('sales.update');
            Route::delete('/{sale}', [SalesController::class, 'destroy'])->name('sales.destroy');
            Route::get('/export', [SalesController::class, 'export'])->name('sales.export');
        });
    });

    Route::middleware('can:view production')->group(function () {
        Route::prefix('production-orders')->group(function () {
            Route::get('/', [ProductionOrderController::class, 'index'])->name('production-orders.index');
            // Route::post('/{order}/process', [ProductionOrderController::class, 'process'])->name('production-orders.process');
            // Route::post('/{order}/deliver', [ProductionOrderController::class, 'deliver'])->name('production-orders.deliver');
        });
    });

    Route::prefix('settings')->middleware('can:manage settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/logo', [SettingsController::class, 'updateLogo'])->name('settings.update-logo');

        Route::get('/roles', [SettingsController::class, 'roles'])->name('settings.roles');
        Route::put('/users/{user}/assign-role', [SettingsController::class, 'assignRole'])->name('settings.assign-role');
        Route::put('/roles/{role}/permissions', [SettingsController::class, 'updateRolePermissions'])->name('settings.update-role-permissions');
        Route::post('/users/create', [SettingsController::class, 'createUser'])->name('settings.users.create');
        Route::get('/users/{user}/edit', [SettingsController::class, 'editUser'])->name('settings.users.edit');
        Route::put('/users/{user}', [SettingsController::class, 'updateUser'])->name('settings.users.update');
        Route::delete('/users/{user}', [SettingsController::class, 'deleteUser'])->name('settings.users.delete');

        Route::get('/activity', [SettingsController::class, 'activity'])->name('settings.activity');

        Route::get('/backup', [SettingsController::class, 'backup'])->name('settings.backup');
        Route::post('/backup/create', [SettingsController::class, 'createBackup'])->name('settings.backup.create');
        Route::get('/backup/{backup}/download', [SettingsController::class, 'downloadBackup'])->name('settings.backup.download');
        Route::post('/backup/restore', [SettingsController::class, 'restoreBackup'])->name('settings.backup.restore');
    });

    Route::middleware('can:view notifications')->group(function () {
        Route::post('/logout', function () {
            auth()->logout();
            return redirect()->route('dashboard');
        })->name('logout');

        // routes/web.php
        Route::post('/products/sync', [ProductController::class, 'syncToOnline'])
            ->name('products.sync')
            ->middleware(['auth']);

        Route::middleware(['auth'])->group(function () {
            Route::get('/raw-materials', [App\Http\Controllers\RawMaterialController::class, 'index'])
                ->name('raw-materials.index');



            Route::delete('/raw-materials/{id}', [App\Http\Controllers\RawMaterialController::class, 'destroy'])
                ->name('raw-materials.destroy');
        });

        // web.php
        Route::post('payrolls/employee/{employeeId}/paynow', [WorkerPayrollController::class, 'payNow'])->name('payrolls.worker_pay_now');

        Route::post('/settings/reset-data', [SettingsController::class, 'resetData'])
            ->name('settings.reset-data')
            ->middleware('auth'); // Make sure only authorized users can access


        Route::post('/raw-materials/{id}/restock', [RawMaterialController::class, 'restock'])->name('raw_materials.restock');


        Route::patch('quotations/{quotation}/update-status', [QuotationController::class, 'updateStatus'])->name('quotations.updateStatus');

        // Update your routes file
        Route::put('/raw-materials/{id}', [RawMaterialController::class, 'update'])->name('raw-materials.update');
        Route::delete('/raw-materials/{id}', [RawMaterialController::class, 'destroy'])->name('raw-materials.destroy');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('clients', ClientController::class);
        });

        // web.php
        Route::get('/batch-flow/{article}/assign-labor', [ProductionController::class, 'assignLabor'])
            ->name('batch-flow.assign-labor');

        Route::get('/batch-flow/labor-assignment/{batch}', [BatchFlowController::class, 'laborAssignment'])
            ->name('batch.flow.labor_assignment');

        Route::get('/batch/{batch}/print', [BatchFlowController::class, 'print'])->name('batch.print');


        Route::post('/raw-materials/store', [RawMaterialController::class, 'store'])->name('raw-materials.store');


        // Batch status update
        Route::patch('/batch/flow/{batch}/status', [\App\Http\Controllers\ProductionController::class, 'updateStatus'])
            ->name('batch.flow.updateStatus');

        Route::patch('/batch/{batch}/worker/{worker}/process/update-status', [BatchflowController::class, 'updateWorkerProcessStatus'])
            ->name('batch.worker.process.updateStatus');


        Route::post('/stock-arrival/{arrivalId}/receive', [RawMaterialController::class, 'markStockReceived'])
            ->name('stock-arrival.receive');





        Route::post('/batch-flow/store-labor', [ProductionController::class, 'storeLabor'])
            ->name('batch.flow.storeLabor');

        Route::get('/products/{id}/details', [ProductController::class, 'details']);

        Route::get('/batch-flow/{batch}/edit', [BatchFlowController::class, 'edit'])->name('batch.flow.edit');
        Route::delete('/batch-flow/{batch}', [BatchFlowController::class, 'destroy'])->name('batch.flow.destroy');
        Route::put('batch-flow/{id}', [BatchFlowController::class, 'update'])->name('batch.flow.update');






        Route::resource('production_processes', ProductionProcessController::class);





        Route::prefix('api')->group(function () {
            Route::get('/dashboard-stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
            Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('api.notifications.unread-count');
        });
    });

    Route::get('/orders/{order}/download', [ClientOrderController::class, 'download'])->name('orders.download');



    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
        Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.markAllAsRead');
    });

    Route::post('/client/mark-seen', [ClientDashboardController::class, 'markSeen'])->name('client.markSeen');

    Route::patch('/production-orders/{order}/update-payment', [ProductionOrderController::class, 'updatePayment'])
        ->name('production-orders.update-payment')
        ->middleware(['auth']);



    Route::prefix('manager-portal')->middleware('can:manage hr')->group(function () {
        Route::get('/', [ManagerPortalController::class, 'index'])->name('manager-portal.index');
        Route::post('/notifications/{notification}/mark-as-read', [ManagerPortalController::class, 'markNotificationAsRead'])->name('manager-portal.mark-notification-as-read');
        Route::post('/leave-requests/{leaveRequest}/approve', [ManagerPortalController::class, 'approveLeave'])->name('manager-portal.approve-leave');
        Route::post('/leave-requests/{leaveRequest}/reject', [ManagerPortalController::class, 'rejectLeave'])->name('manager-portal.reject-leave');
        Route::post('/advance-salary-requests/{salaryAdvanceRequest}/approve', [ManagerPortalController::class, 'approveAdvanceSalary'])->name('manager-portal.approve-advance-salary');
        Route::post('/advance-salary-requests/{salaryAdvanceRequest}/reject', [ManagerPortalController::class, 'rejectAdvanceSalary'])->name('manager-portal.reject-advance-salary');
    });

    Route::prefix('reports')->middleware('can:view reports')->group(function () {
        Route::get('/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/sales/export', [ReportController::class, 'exportSalesReport'])->name('reports.sales.export');
        Route::get('/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
        Route::get('/inventory/export', [ReportController::class, 'exportInventoryReport'])->name('reports.inventory.export');
        Route::get('/finance', [ReportController::class, 'financeReport'])->name('reports.finance');
        Route::get('/finance/export', [ReportController::class, 'exportFinanceReport'])->name('reports.finance.export');
        Route::get('/employee-performance', [ReportController::class, 'employeePerformanceReport'])->name('reports.employee-performance');
        Route::get('/employee-performance/export', [ReportController::class, 'exportEmployeePerformanceReport'])->name('reports.employee-performance.export');
        Route::get('/payroll', [ReportController::class, 'payrollReport'])->name('reports.payroll');
        Route::get('/payroll/export', [ReportController::class, 'exportPayrollReport'])->name('reports.payroll.export');
    });

    Route::prefix('users')->middleware('can:manage users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/finance/dashboard', [FinanceController::class, 'dashboard'])
        ->name('finance.dashboard')
        ->middleware(['auth', 'can:manage finance']);
    Route::get('/finance/report', [FinanceController::class, 'generateReport'])
        ->name('finance.report')
        ->middleware(['auth', 'can:manage finance']);
    Route::get('/finance/export', [FinanceController::class, 'exportTransactions'])
        ->name('finance.export')
        ->middleware(['auth', 'can:manage finance']);

    Route::get('/password/change', [App\Http\Controllers\PasswordController::class, 'change'])->name('password.change');
    Route::post('/password/update', [App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');

    Route::prefix('api')->group(function () {
        Route::get('/dashboard-stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
        Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('api.notifications.unread-count');
        Route::get('/search', [SearchController::class, 'search'])->name('api.search');
    });
});

require __DIR__ . '/auth.php';