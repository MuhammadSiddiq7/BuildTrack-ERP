<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CompanyBankController;
use App\Http\Controllers\ComparativeStatementController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\ContractorProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeBankController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDepartmentController;
use App\Http\Controllers\FuelReportController;
use App\Http\Controllers\HouseTypeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemDemandController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AddCategoriesController;
use App\Http\Controllers\BillingRequestController;
use App\Http\Controllers\ContractorDemand;
use App\Http\Controllers\ContractorDemandController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ContractorProgressController;
use App\Models\Attendance;
use App\Models\Employee;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class,)->group(function () {
    Route::prefix('/')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::get('login', 'showLoginForm')->name('login.form');
        Route::post('/login', 'login')->name('login');
        Route::post('/logout', 'logout')->name('logout');
    });
});
// Route::controller(AuthController::class)->group(function () {
//     Route::get('/', 'showLoginForm')->name('login.form');
//     Route::get('login', 'showLoginForm')->name('login.form');
//     Route::post('login', 'login')->name('login.submit');
//     Route::post('logout', 'logout')->name('logout');
// });
Route::middleware('auth')->group(function () {

    Route::controller(RoleController::class)->prefix('role')->group(function () {
        Route::get('/', 'index')->name('role.index');
        Route::get('create', 'create')->name('role.create');
        Route::post('store', 'store')->name('role.store');
        Route::get('edit/{id}', 'edit')->name('role.edit');
        Route::delete('delete/{id}', 'destroy')->name('role.delete');
        Route::put('update/{id}', 'update')->name('role.update');
        Route::get('trash', 'trash')->name('role.trash');
        Route::get('restore/{id}', 'restore')->name('role.restore');
    });
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('/', 'index')->name('user.index');
        Route::get('create', 'create')->name('user.create');
        Route::post('store', 'store')->name('user.store');
        Route::get('edit/{id}', 'edit')->name('user.edit');
        Route::delete('delete/{id}', 'destroy')->name('user.delete');
        Route::put('update/{id}', 'update')->name('user.update');
        Route::get('trash', 'trash')->name('user.trash');
        Route::get('restore/{id}', 'restore')->name('user.restore');
    });
    Route::controller(ProjectController::class)->prefix('project')->group(function () {
        Route::get('/', 'index')->name('project.index');
        Route::get('create', 'create')->name('project.create');
        Route::post('store', 'store')->name('project.store');
        Route::post('store', 'categories_store')->name('project.store');
        // Route::get('edit/{id}', 'edit')->name('project.edit');
        Route::get('edit/{id}', 'edit')->name('project.edit');
        Route::get('view/{id}', 'view')->name('project.view');
        Route::put('update/{id}', 'update')->name('project.update');
        Route::delete('delete/{id}', 'destroy')->name('project.delete');
        // Route::put('update/{id}', 'update')->name('project.update');
        Route::get('trash', 'trash')->name('project.trash');
        Route::get('restore/{id}', 'restore')->name('project.restore');
        Route::get('assign-item/{id}',  'assignItem')->name('project.assignItem');
        Route::post('assign-item-store/{id}', 'storeAssignedItems')->name('project.storeAssignedItems');
        Route::post('unassign-items/{id}', 'unassignItems')->name('project.unassignItems');
        Route::post('/project-contractor/status', 'contractorStatus')->name('project.contractor.status');
        Route::get('/get-contractor-house-projects/{contractorId}', 'getContractorHouseProjects');
        Route::get('/projects/list', 'projectsList')->name('projects.list');
        Route::get('project-dashboard/{id}', 'projectDashboard')->name('project.dashboard');
        Route::get('contractor/{code}/demands', 'contractorDemandDashboard')->name('contractor.demand.dashboard');
    });

    Route::controller(ProjectController::class)->prefix('project')->group(function () {
        Route::get('/', 'index')->name('project.index');
        Route::get('create', 'create')->name('project.create');
        Route::post('store', 'store')->name('project.store');

        // ✅ new route for categories
        Route::get('categories-create/{projectId}', [ProjectController::class, 'categories_create'])
            ->name('project.categories.create');

        Route::post('categories-store/{projectId}', [ProjectController::class, 'categories_store'])
            ->name('project.categories.store');
    });
    Route::controller(WarehouseController::class)->prefix('warehouse')->group(function () {
        Route::get('/', 'index')->name('warehouse.index');
        Route::get('create', 'create')->name('warehouse.create');
        Route::post('store', 'store')->name('warehouse.store');
        Route::get('edit/{id}', 'edit')->name('warehouse.edit');
        Route::delete('delete/{id}', 'destroy')->name('warehouse.delete');
        Route::put('update/{id}', 'update')->name('warehouse.update');
        Route::get('trash', 'trash')->name('warehouse.trash');
        Route::get('restore/{id}', 'restore')->name('warehouse.restore');
        Route::get('assign-item/{id}',  'assignItem')->name('warehouse.assignItem');
        Route::post('assign-item-store/{id}', 'storeAssignedItems')->name('warehouse.storeAssignedItems');
        Route::post('unassign-items/{id}', 'unassignItems')->name('warehouse.unassignItems');
    });
    Route::controller(ItemController::class)->prefix('item')->group(function () {
        Route::get('/', 'index')->name('item.index');
        Route::get('create', 'create')->name('item.create');
        Route::post('store', 'store')->name('item.store');
        Route::get('edit/{id}', 'edit')->name('item.edit');
        Route::put('update/{id}', 'update')->name('item.update');
        Route::delete('delete/{id}', 'destroy')->name('item.delete');
        Route::get('trash', 'trash')->name('item.trash');
        Route::get('restore/{id}', 'restore')->name('item.restore');
        Route::post('/import', 'import')->name('items.import');
        // Items B
        Route::get('/type-b', 'indexB')->name('item.index.b');
        Route::get('edit/{id}', 'edit')->name('item.edit');
        Route::put('update/{id}', 'update')->name('item.update');
        Route::get('trash-b', 'trashB')->name('item.trash.b');
        Route::get('restore-b/{id}', 'restoreB')->name('item.restore.b');
        Route::post('/import-b', 'importB')->name('items.import.b');
        // Items C
        Route::get('/type-c', 'indexC')->name('item.index.c');
        Route::get('edit/{id}', 'edit')->name('item.edit');
        Route::put('update/{id}', 'update')->name('item.update');
        Route::delete('delete-c/{id}', 'destroyC')->name('item.delete.c');
        Route::get('trash-c', 'trashC')->name('item.trash.c');
        Route::get('restore-c/{id}', 'restoreC')->name('item.restore.c');
        Route::post('/import-c', 'importC')->name('items.import.c');
        // Items D
        Route::get('/type-d', 'indexD')->name('item.index.d');
        Route::get('edit/{id}', 'edit')->name('item.edit');
        Route::put('update/{id}', 'update')->name('item.update');
        Route::delete('delete-d/{id}', 'destroyD')->name('item.delete.d');
        Route::get('trash-d', 'trashD')->name('item.trash.d');
        Route::get('restore-d/{id}', 'restoreD')->name('item.restore.d');
        Route::post('/import-d', 'importD')->name('items.import.d');
    });

    Route::controller(SupplierController::class)->prefix('supplier')->group(function () {
        Route::get('/', 'index')->name('supplier.index');
        Route::get('create', 'create')->name('supplier.create');
        Route::post('store', 'store')->name('supplier.store');
        Route::get('edit/{id}', 'edit')->name('supplier.edit');
        Route::delete('delete/{id}', 'destroy')->name('supplier.delete');
        Route::put('update/{id}', 'update')->name('supplier.update');
        Route::get('trash', 'trash')->name('supplier.trash');
        Route::get('restore/{id}', 'restore')->name('supplier.restore');
        Route::get('assign-item/{id}',  'assignItem')->name('supplier.assignItem');
        Route::post('assign-item-store/{id}', 'storeAssignedItems')->name('supplier.storeAssignedItems');
        Route::post('unassign-items/{id}', 'unassignItems')->name('supplier.unassignItems');

        Route::get('item/{item}/supplier-rates', 'showSupplierRatesForm')->name('item.supplier.form');
        Route::post('item/{item}/supplier-rates', 'storeItemSupplierRates')->name('item.supplier.store');
    });
    Route::controller(BrandController::class)->prefix('brand')->group(function () {
        Route::get('/', 'index')->name('brand.index');
        Route::get('create', 'create')->name('brand.create');
        Route::post('store', 'store')->name('brand.store');
        Route::get('edit/{id}', 'edit')->name('brand.edit');
        Route::delete('delete/{id}', 'destroy')->name('brand.delete');
        Route::put('update/{id}', 'update')->name('brand.update');
        Route::get('trash', 'trash')->name('brand.trash');
        Route::get('restore/{id}', 'restore')->name('brand.restore');
    });
    Route::controller(ContractorController::class)->prefix('contractor')->group(function () {
        Route::get('/', 'index')->name('contractor.index');
        Route::get('create', 'create')->name('contractor.create');
        Route::post('store', 'store')->name('contractor.store');
        Route::get('edit/{id}', 'edit')->name('contractor.edit');
        Route::delete('delete/{id}', 'destroy')->name('contractor.delete');
        Route::put('update/{id}', 'update')->name('contractor.update');
        Route::get('trash', 'trash')->name('contractor.trash');
        Route::get('restore/{id}', 'restore')->name('contractor.restore');
    });
    Route::controller(ContractorProjectController::class)->prefix('contractorProject')->group(function () {
        Route::get('/', 'index')->name('contractor.project.index');
        Route::post('/toggle-status', 'toggleStatus')->name('contractor.toggleStatus');
    });
    Route::controller(HouseTypeController::class)->prefix('houseType')->group(function () {
        Route::get('/', 'index')->name('houseType.index');
        Route::get('create', 'create')->name('houseType.create');
        Route::post('store', 'store')->name('houseType.store');
        Route::get('edit/{id}', 'edit')->name('houseType.edit');
        Route::delete('delete/{id}', 'destroy')->name('houseType.delete');
        Route::put('update/{id}', 'update')->name('houseType.update');
        Route::get('trash', 'trash')->name('houseType.trash');
        Route::get('restore/{id}', 'restore')->name('houseType.restore');
    });
    Route::controller(StockController::class)->prefix('stock')->group(function () {
        // Stock In
        Route::get('/', 'index')->name('stock.index');
        Route::post('store', 'store')->name('stock.store');
        // Route::get('edit/{id}', 'edit')->name('stock.edit');
        Route::put('update/{id}', 'update')->name('stock.update');
        Route::delete('delete/{id}', 'destroy')->name('stock.delete');
        Route::get('trash', 'trash')->name('stock.trash');
        Route::get('restore/{id}', 'restore')->name('stock.restore');
        // Stock Out
        Route::get('/stockOut', 'stockOut')->name('stock.out.index');
        Route::post('stockOut-store', 'storeStockOut')->name('stock.out.store');
        Route::put('stockOut-update/{id}', 'updateStockOut')->name('stock.out.update');
        Route::delete('stockOut-delete/{id}', 'destroyStockOut')->name('stock.out.delete');
        Route::get('stockOut-trash', 'trashStockOut')->name('stock.out.trash');
        Route::get('stockOut-restore/{id}', 'restoreStockOut')->name('stock.out.restore');
        // Stock Check
        Route::get('/stock-check', 'stockCheck')->name('stock.check');
        Route::get('/get-items/{houseType}', 'getItems')->name('stock.getItems');
    });
    Route::controller(ItemDemandController::class)->prefix('itemDemand')->group(function () {
        Route::get('/', 'index')->name('itemDemand.index');
        Route::get('create', 'create')->name('itemDemand.create');
        Route::post('store', 'store')->name('itemDemand.store');
        Route::get('edit/{id}', 'edit')->name('itemDemand.edit');
        Route::get('show/{id}', 'show')->name('itemDemand.show');
        Route::get('view/{id}', 'view')->name('itemDemand.view');
        Route::get('issue/{id}', 'issue')->name('itemDemand.issue');
        Route::post('issued/{id}', 'issued')->name('itemDemand.issued');
        Route::put('update/{id}', 'update')->name('itemDemand.update');
        Route::delete('delete/{id}', 'destroy')->name('itemDemand.delete');
        Route::get('trash', 'trash')->name('itemDemand.trash');
        Route::get('restore/{id}', 'restore')->name('itemDemand.restore');
        Route::post('/approve/{id}', 'approve')->name('demand.approve');
        Route::get('project/details', 'getProjectDetails')->name('itemDemand.project.details');
        // ✅ for contractor demands
        Route::get('/get-contractor/{code}/demand', 'contractorViewDemand')->name('contractor.view.demand');
        Route::get('contractor/{code}/demands', 'contractorDemands')->name('itemDemand.contractor.demands');
        // AJAX-related routes
        // routes/web.php
        Route::get('get-items/{projectId}/{contractorId}', 'getItems')->name('getItems');
        Route::get('/project/{id}/houses', 'getProjectHouses')->name('project.houses');
        // Route::get('/house/{id}/contractors','getHouseContractors')->name('house.contractors');
        Route::get('/project/{id}/contractors', 'getProjectContractors')->name('project.contractors');
        Route::get('/project/{project}/items', 'getProjectItems')->name('project.items');
        Route::get('/house/{id}/contractors', 'getHouseContractors')->name('itemDemand.house.contractors');
        Route::get('/house/{id}/items', 'getItemsByHouse')->name('house.items');
    });


    // Route::get('/project/{id}/houses', 'getProjectHouses')->name('project.houses');
    // // Route::get('/house/{id}/contractors','getHouseContractors')->name('house.contractors');
    // Route::get('/project/{id}/contractors', 'getProjectContractors')->name('project.contractors');
    // Route::get('/project/{project}/items', 'getProjectItems')->name('project.items');
    // Route::get('/house/{id}/items', 'getItemsByHouse')->name('house.items');
    // Route::get('houseProject/{id}/series', 'getHouseSeries')->name('houseProject.series');
    // });
    Route::controller(PurchaseOrderController::class)->prefix('purchaseOrder')->group(function () {
        Route::get('/', 'index')->name('purchaseOrder.index');
        Route::get('create/{id}', 'create')->name('purchaseOrder.create');
        Route::get('create/po/{id}', 'directCreate')->name('direct.purchaseOrder.create');
        Route::get('/merge-list/po', 'listForMergePo')->name('purchaseOrder.listForMerge');
        Route::post('/merge-selected-po', 'mergeSelectedPo')->name('purchaseOrder.mergeSelected');
        Route::get('invoice-marge/{id}', 'margeInvoice')->name('marge.po.invoice');
        Route::post('store', 'store')->name('purchaseOrder.store');
        Route::get('edit/{id}', 'edit')->name('purchaseOrder.edit');
        Route::put('update/{id}', 'update')->name('purchaseOrder.update');
        Route::delete('delete/{id}', 'destroy')->name('purchaseOrder.delete');
        Route::get('trash', 'trash')->name('purchaseOrder.trash');
        Route::get('restore/{id}', 'restore')->name('purchaseOrder.restore');
        Route::post('/generate-po/{id}', 'generatePO')->name('purchaseOrder.generate');
        Route::get('generateInvoice/{id}', 'generateInvoice')->name('po.invoice');
        Route::get('generateInvoiceSingle/{id}', 'generateInvoiceSingle')->name('po.invoice.single');
        Route::get('po-list', 'poList')->name('po.list');
        Route::get('po-cancel/{id}', 'poCancel')->name('po.cancel');
        Route::get('po-view-list/{id}', 'poViewList')->name('po.view.list');
        Route::post('/po-items/{id}/receive', 'receiveItem')->name('po.items.receive');
        Route::get('/get-item-suppliers/{itemId}/{demandId}', 'getItemSuppliers')->name('getItemSuppliers');
    });
    Route::controller(ComparativeStatementController::class)->prefix('comparativeStatement')->group(function () {
        Route::get('/', 'index')->name('comparativeStatement.index');
        Route::get('/view/po', 'viewPo')->name('comparativeStatement.view.po');
        Route::get('create/{id}', 'create')->name('comparativeStatement.create');
        Route::post('store', 'store')->name('comparativeStatement.store');
        Route::get('edit/{id}', 'edit')->name('comparativeStatement.edit');
        Route::put('update/{id}', 'update')->name('comparativeStatement.update');
        Route::get('view/{id}', 'view')->name('comparativeStatement.view');
        Route::delete('delete/{id}', 'destroy')->name('comparativeStatement.delete');
        Route::get('trash', 'trash')->name('comparativeStatement.trash');
        Route::get('restore/{id}', 'restore')->name('comparativeStatement.restore');
        Route::post('/generate', 'generateCS')->name('purchaseOrder.generateSingle');
        Route::get('generateInvoice', 'generateInvoice')->name('purchaseOrder.invoice');
        Route::post('/approve/{id}', 'approveStatus')->name('comparativeStatement.approve');
    });
    Route::controller(QuotationController::class)->prefix('quotation')->group(function () {
        Route::get('/', 'index')->name('quotation.index');
        Route::get('create/{id}', 'create')->name('quotation.create');
        Route::post('store', 'store')->name('quotation.store');
        Route::get('edit/{id}', 'edit')->name('quotation.edit');
        Route::put('update/{id}', 'update')->name('quotation.update');
        Route::get('view/{id}', 'view')->name('quotation.view');
        Route::delete('delete/{id}', 'destroy')->name('quotation.delete');
        Route::get('trash', 'trash')->name('quotation.trash');
        Route::get('restore/{id}', 'restore')->name('quotation.restore');
        Route::get('show/{id}', 'show')->name('quotation.show');
    });
    // Planing Module
    Route::controller(ActivityController::class)->prefix('activity')->group(function () {
        Route::get('/', 'index')->name('activity.index');
        Route::get('create', 'create')->name('activity.create');
        Route::post('store', 'store')->name('activity.store');
        Route::get('edit/{id}', 'edit')->name('activity.edit');
        Route::put('update/{id}', 'update')->name('activity.update');
        Route::get('view/{id}', 'view')->name('activity.view');
        Route::delete('delete/{id}', 'destroy')->name('activity.delete');
        Route::get('trash', 'trash')->name('activity.trash');
        Route::get('restore/{id}', 'restore')->name('activity.restore');
        Route::get('show/{id}', 'show')->name('activity.show');
        Route::post('/activities/import', 'importActivities')->name('activities.import');
        // Route::get('/house/{id}/activities-chart','houseActivitiesChart');
    });
    Route::controller(HolidayController::class)->prefix('holiday')->group(function () {
        Route::get('/', 'index')->name('holiday.index');
        Route::get('create', 'create')->name('holiday.create');
        Route::post('store', 'store')->name('holiday.store');
        Route::get('edit/{id}', 'edit')->name('holiday.edit');
        Route::put('update/{id}', 'update')->name('holiday.update');
        Route::get('view/{id}', 'view')->name('holiday.view');
        Route::delete('delete/{id}', 'destroy')->name('holiday.delete');
        Route::get('trash', 'trash')->name('holiday.trash');
        Route::get('restore/{id}', 'restore')->name('holiday.restore');
        Route::get('show/{id}', 'show')->name('holiday.show');
    });
    Route::controller(PlanController::class)->prefix('plan')->group(function () {
        Route::get('/', 'index')->name('plan.index');
        // Route::get('/contractor-progress', 'contractor_index')->name('plan.contractor.progress');
        Route::get('/contractor-progress', 'contractor_progress')->name('contractor.progress.show');
        Route::get('/contractor-progress/view/{id}', 'contractor_progress_detail')->name('plan.contractor.progress.detail');
        Route::get('create', 'create')->name('plan.create');
        Route::post('store', 'store')->name('plan.store');
        Route::get('edit/{id}', 'edit')->name('plan.edit');
        Route::put('update/{id}', 'update')->name('plan.update');
        Route::get('view/{id}', 'view')->name('plan.view');
        Route::delete('delete/{id}', 'destroy')->name('plan.delete');
        Route::get('trash', 'trash')->name('plan.trash');
        Route::get('restore/{id}', 'restore')->name('plan.restore');
        Route::get('show/{id}', 'show')->name('plan.show');
        Route::get('/get-contractors-by-project/{projectId}', 'getContractorsByProject');
        Route::get('/get-house-types-by-contractor/{projectId}/{contractorId}',  'getHouseTypesByContractor');
        Route::get('/get-houses/{houseProjectId}', 'getHouses')->name('plan.getHouses');
        Route::get('assign-activity/{id}',  'assignActivity')->name('plan.assignActivity');
        Route::get('assign-activity-combine',  'assignActivityCombine')->name('plan.assignActivity.combine');
        Route::get('/get-types',  'getTypesByProject')->name('get.types');
        Route::get('/get-houses',  'getHousesByProjectAndType')->name('get.houses');
        Route::post('assign-activity-store', 'storeAssignedActivity')->name('plan.storeAssignedActivity');
        Route::post('assign-activity-combine-store', 'storeAssignedActivityCombine')->name('plan.storeAssignedActivityCombine');
        Route::post('/plan/unassign-activity', 'unassignActivity')->name('plan.unassignActivity');
        Route::get('check/{id}', 'check')->name('plan.check');
        Route::get('/house/{id}/activities-gantt', 'houseActivitiesChart');

    });


    Route::controller(BillingRequestController::class)->prefix('billing')->group(function () {
        Route::get('/', 'index')->name('billing.index');
        Route::post('/request/{planActivity}', 'store')->name('billing.request');
        Route::patch('/approve/{billingRequest}', 'approve')->name('billing.approve');
        Route::patch('/reject/{billingRequest}', 'reject')->name('billing.reject');
        // Route::post('inspection-report/{planActivityId}', 'requestIR')->name('contractor.ir.request');
        Route::get('inspection-report/{planActivityId}','irReport')->name('inspection.report.show');
        Route::post('inspection-report/{planActivity}', 'requestIR')->name('contractor.ir.request');
        Route::post('/inspection-reports/{id}/contractor-approve', 'contractorApprove')->name('inspection_reports.contractor.approve');
        Route::post('/inspection-reports/{id}/contractor-reject', 'contractorReject')->name('inspection_reports.contractor.reject');
        Route::post('/inspection-reports/{id}/consultant-approve', 'consultantApprove')->name('inspection_reports.consultant.approve');
        Route::post('/inspection-reports/{id}/consultant-reject', 'consultantReject')->name('inspection_reports.consultant.reject');
        Route::post('/inspection-reports/{id}/adcc-approve', 'adccApprove')->name('inspection_reports.adcc.approve');
        Route::post('/inspection-reports/{id}/adcc-reject', 'adccReject')->name('inspection_reports.adcc.reject');
        Route::post('/inspection-reports/{id}/pm-approve', 'pmApprove')->name('inspection_reports.pm.approve');
        Route::post('/inspection-reports/{id}/pm-reject', 'pmReject')->name('inspection_reports.pm.reject');
        Route::patch('inspection-reports/{id}/update-remarks','updateGeneralRemarks')->name('inspection_reports.updateGeneralRemarks');


    });

    Route::patch('/plan-activities/{planActivity}/manager-approve', [ActivityController::class, 'managerApprove'])->name('planActivities.manager.approve');
    Route::patch('/plan-activities/{planActivity}/manager-reject', [ActivityController::class, 'managerReject'])->name('planActivities.manager.reject');

    Route::patch('/plan-activities/{planActivity}/consultant-approve', [ActivityController::class, 'consultantApprove'])->name('planActivities.consultant.approve');
    Route::patch('/plan-activities/{planActivity}/consultant-reject', [ActivityController::class, 'consultantReject'])->name('planActivities.consultant.reject');

    Route::patch('/plan-activities/{planActivity}/client-approve', [ActivityController::class, 'clientApprove'])->name('planActivities.client.approve');
    Route::patch('/plan-activities/{planActivity}/client-reject', [ActivityController::class, 'clientReject'])->name('planActivities.client.reject');

    Route::patch('/plan-activities/{planActivity}/contractor-approve', [ActivityController::class, 'contractorApprove'])->name('planActivities.contractor.approve');
    Route::patch('/plan-activities/{planActivity}/contractor-reject', [ActivityController::class, 'contractorReject'])->name('planActivities.contractor.reject');

    Route::patch('/plan-activities/{planActivity}/progress', [ActivityController::class, 'updateProgress'])
        ->name('planActivities.updateProgress');

    Route::get('/plan/{id}', [PlanController::class, 'show'])->name('plan.show');
    Route::post('plan/ir/{planActivityId}', [PlanController::class, 'saveIrReport'])->name('ir.save');

    // HR
    Route::controller(EmployeeController::class)->prefix('employees')->group(function () {
        Route::get('/', 'index')->name('employee.index');
        Route::get('create', 'create')->name('employee.create');
        Route::post('store', 'store')->name('employee.store');
        Route::get('edit/{id}', 'edit')->name('employee.edit');
        Route::delete('delete/{id}', 'destroy')->name('employee.delete');
        Route::put('update/{id}', 'update')->name('employee.update');
        Route::get('trash', 'trash')->name('employee.trash');
        Route::get('restore/{id}', 'restore')->name('employee.restore');
        Route::get('show/{id}', 'show')->name('employee.show');
        Route::post('/employees/import', 'import')->name('employees.import');
        Route::post('/employees/import-uk', 'importUkEmployees')->name('employees.import.uk');
        Route::delete('/employees/bulk-delete', 'bulkDelete')->name('employee.bulk-delete');
    });
    Route::post('employees/import', [\App\Http\Controllers\EmployeeController::class, 'import'])->name('employees.import');

    Route::controller(CompanyBankController::class)->prefix('companyBank')->group(function () {
        Route::get('/', 'index')->name('company.bank.index');
        Route::get('/create', 'create')->name('company.bank.create');
        Route::post('/store', 'store')->name('company.bank.store');
        Route::get('/edit/{id}', 'edit')->name('company.bank.edit');
        Route::put('/update/{id}', 'update')->name('company.bank.update');
        Route::delete('/destroy/{id}', 'destroy')->name('company.bank.destroy');
        Route::get('/trash', 'trash')->name('company.bank.trash');
        Route::get('/restore/{id}', 'restore')->name('company.bank.restore');
    });
    Route::controller(EmployeeBankController::class)->prefix('employeeBanks')->group(function () {
        Route::get('/', 'index')->name('employee.bank.index');
        Route::get('/create', 'create')->name('employee.bank.create');
        Route::post('/store', 'store')->name('employee.bank.store');
        Route::get('/edit/{id}', 'edit')->name('employee.bank.edit');
        Route::put('/update/{id}', 'update')->name('employee.bank.update');
        Route::delete('/destroy/{id}', 'destroy')->name('employee.bank.destroy');
        Route::get('/trash', 'trash')->name('employee.bank.trash');
        Route::get('/restore/{id}', 'restore')->name('employee.bank.restore');
    });
    Route::controller(EmployeeDepartmentController::class)->prefix('departments')->group(function () {
        Route::get('/', 'index')->name('employee_departments.index');
        Route::get('/create', 'create')->name('employee_departments.create');
        Route::post('/store', 'store')->name('employee_departments.store');
        Route::get('/edit/{id}', 'edit')->name('employee_departments.edit');
        Route::post('/update/{id}', 'update')->name('employee_departments.update');
        Route::post('/{id}/delete', 'delete')->name('employee_departments.delete');
        Route::get('/trash', 'trash')->name('employee_departments.trash');
        Route::post('/restore/{id}', 'restore')->name('employee_departments.restore');
    });
    Route::controller(DesignationController::class)->prefix('designations')->group(function () {
        Route::get('/', 'index')->name('designations.index');
        Route::get('create', 'create')->name('designations.create');
        Route::post('store', 'store')->name('designations.store');
        Route::get('edit/{designation}', 'edit')->name('designations.edit');
        Route::put('update/{designation}', 'update')->name('designations.update');
        Route::delete('delete/{designation}', 'destroy')->name('designations.destroy');
        Route::get('trash', 'trash')->name('designations.trash');
        Route::get('restore/{id}', 'restore')->name('designations.restore');
    });
    Route::controller(ApplicationController::class)->prefix('application')->group(function () {
        Route::get('/', 'index')->name('application.index');
        Route::get('create', 'create')->name('application.create');
        Route::post('store', 'store')->name('application.store');
        Route::get('edit/{id}', 'edit')->name('application.edit');
        Route::put('update/{id}', 'update')->name('application.update');
        Route::get('details/{id}', 'details')->name('application.details');
        Route::delete('delete/{id}', 'destroy')->name('application.destroy');
        Route::get('trash', 'trash')->name('application.trash');
        Route::get('restore/{id}', 'restore')->name('application.restore');
    });
    Route::controller(PayrollController::class)->prefix('payroll')->group(function () {
        Route::get('/', 'index')->name('payrolls.index');
        Route::get('/get-attendance-salary/{id}', 'getEmployeeAttendanceSalary');
        Route::get('create', 'create')->name('payrolls.create');
        Route::post('store', 'store')->name('payrolls.store');
        Route::get('edit/{id}', 'edit')->name('payrolls.edit');
        Route::put('update/{id}', 'update')->name('payrolls.update');
        Route::delete('delete/{id}', 'destroy')->name('payrolls.delete');
        Route::get('trash', 'trash')->name('payrolls.trash');
        Route::get('restore/{id}', 'restore')->name('payrolls.restore');
        Route::get('show/{id}', 'show')->name('payrolls.show');
        Route::get('/download-by-month', 'downloadByMonth')->name('payrolls.download-by-month');
        Route::get('/download-by-year', 'downloadByYear')->name('payrolls.download-by-year');
    });
    Route::controller(FuelReportController::class)->prefix('fuel')->group(function () {
        Route::get('/', 'index')->name('fuel.index');
        Route::get('create', 'create')->name('fuel.create');
        Route::post('store', 'store')->name('fuel.store');
        Route::get('edit/{id}', 'edit')->name('fuel.edit');
        Route::put('update/{id}', 'update')->name('fuel.update');
        Route::delete('delete/{id}', 'destroy')->name('fuel.delete');
        Route::get('trash', 'trash')->name('fuel.trash');
        Route::get('restore/{id}', 'restore')->name('fuel.restore');
        Route::get('show/{id}', 'show')->name('fuel.show');
        Route::get('/download-by-month', 'monthFuel')->name('month.fuel');
        Route::get('/download-by-year', 'downloadByYear')->name('fuel.download-by-year');
    });
    Route::get('/get-employee-details/{id}', [PayrollController::class, 'getEmployeeDetails']);

    Route::resource('tax', TaxController::class)->parameters([
        'tax' => 'tax'
    ]);

    Route::controller(AttendanceController::class)->prefix('attendance')->group(function () {
        Route::get('/', 'index')->name('attendances.index');
        Route::get('/create', 'create')->name('attendances.create');
        // Route::post('/attendance','store')->name('attendance.store');

    });

    Route::get('/attendances/report', [AttendanceController::class, 'downloadReport'])->name('attendances.report');

    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');


    Route::get('/leave/index', [LeaveController::class, 'show'])->name('leave.show');
    Route::get('/leave/detail/{id}', [LeaveController::class, 'detail'])->name('leave.detail');
    Route::post('/leave/{id}/approve', [LeaveController::class, 'approve'])->name('leave.approve')->middleware('auth');

    Route::controller(UserActivityController::class)->prefix('user_activity')->group(function () {
        Route::get('/', 'index')->name('user_activity.index');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', function () {
            Auth::logout();
            return redirect('/');
        })->name('logout');
    });
});

    Route::middleware(['auth'])
        ->prefix('admin')
        ->group(function () {});
    Route::get('check-in', [AttendanceController::class, 'publicForm'])->name('attendance.publicForm');
    Route::post('check-in/post', [AttendanceController::class, 'publicMark'])->name('attendance.publicMark');

    Route::get('/leave', [LeaveController::class, 'create'])->name('leave.index');
    Route::get('/leave/index', [LeaveController::class, 'show'])->name('leave.show');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/get-employee/{employee_id}', [LeaveController::class, 'getEmployeeId']);
    Route::get('/payrolls/{id}/pdf', [PayrollController::class, 'previewHtml'])->name('payrolls.pdf');
    Route::get('/payrolls/{id}/pdf/download', [PayrollController::class, 'downloadPdf'])->name('payrolls.pdf.download');


    Route::controller(ContractorDemandController::class)->prefix('contractorDemand')->group(function () {
        Route::get('/', 'demand')->name('demand');
        Route::post('store', 'store')->name('demand.store');
        Route::get('get-items/{projectId}/{contractorId}', 'getItems')->name('getItems');
        Route::get('/project/{id}/houses', 'getProjectHouses')->name('project.houses');
        Route::get('/project/{id}/contractors', 'getProjectContractors')->name('project.contractors');
        Route::get('/project/{project}/items', 'getProjectItems')->name('project.items');
        Route::get('/house/{id}/contractors', 'getHouseContractors')->name('demand.house.contractors');
        Route::get('/house/{id}/items', 'getItemsByHouse')->name('house.items');
        // Route::get('/get-house-types/{projectId}/{contractorId}','getHouseTypes');
        Route::get('receive/{token}', 'receive')->name('demand.receive');
        Route::post('received/{token}', 'received')->name('demand.received');
    });
    Route::get('receive', [ContractorDemandController::class, 'receiveView'])->name('receive.view');
    Route::get('receive/demands/{code}', [ContractorDemandController::class, 'getContractorDemands'])->name('receive.demands');
    Route::post('/demand/{token}/item/{itemId}/stockout', [ContractorDemandController::class, 'receiveAndStockOut'])
        ->name('demand.item.stockout');

    // Contractor Demands Open Link Routes
    Route::get('contractors', [ContractorDemandController::class, 'contractorCodeForm'])->name('contractor.receive.form');
    Route::get('contractor/receive/{code}', [ContractorDemandController::class, 'showDemands'])->name('contractor.receive.demands');


    Route::get('/job-apply', [ApplicationController::class, 'applicationcreate'])->name('application.applicationcreate');

    // Contractor Billing Routes
    Route::get('contractor/billing', [BillingRequestController::class, 'contractorCodeForm'])
        ->name('contractor.billing.form');

    Route::get('contractor/billing/{code}', [BillingRequestController::class, 'showBilling'])
        ->name('contractor.billing.view');

    Route::post('contractor/billing/request/{planActivity}', [BillingRequestController::class, 'requestBilling'])
        ->name('contractor.billing.request');



    Route::post('/mark-notification-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
