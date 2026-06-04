<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            ['name' => 'dashboard_view', 'guard_name' => 'web', 'group_name' => 'Dashboard'],

            ['name' => 'user_view', 'guard_name' => 'web', 'group_name' => 'User'],
            ['name' => 'user_create', 'guard_name' => 'web', 'group_name' => 'User'],
            ['name' => 'user_edit', 'guard_name' => 'web', 'group_name' => 'User'],
            ['name' => 'user_trash', 'guard_name' => 'web', 'group_name' => 'User'],
            ['name' => 'user_trash_view', 'guard_name' => 'web', 'group_name' => 'User'],
            ['name' => 'user_restore', 'guard_name' => 'web', 'group_name' => 'User'],

            ['name' => 'role_view', 'guard_name' => 'web', 'group_name' => 'Role'],
            ['name' => 'role_create', 'guard_name' => 'web', 'group_name' => 'Role'],
            ['name' => 'role_edit', 'guard_name' => 'web', 'group_name' => 'Role'],
            ['name' => 'role_trash', 'guard_name' => 'web', 'group_name' => 'Role'],
            ['name' => 'role_trash_view', 'guard_name' => 'web', 'group_name' => 'Role'],
            ['name' => 'role_restore', 'guard_name' => 'web', 'group_name' => 'Role'],

            ['name' => 'user_activity_view', 'guard_name' => 'web', 'group_name' => 'User Activity'],

            ['name' => 'project_view', 'guard_name' => 'web', 'group_name' => 'Project'],
            ['name' => 'project_create', 'guard_name' => 'web', 'group_name' => 'Project'],
            ['name' => 'project_edit', 'guard_name' => 'web', 'group_name' => 'Project'],
            ['name' => 'project_trash', 'guard_name' => 'web', 'group_name' => 'Project'],
            ['name' => 'project_trash_view', 'guard_name' => 'web', 'group_name' => 'Project'],
            ['name' => 'project_restore', 'guard_name' => 'web', 'group_name' => 'Project'],
            ['name' => 'project_assign_item', 'guard_name' => 'web', 'group_name' => 'Project'],

            ['name' => 'item_view', 'guard_name' => 'web', 'group_name' => 'Item A Type'],
            ['name' => 'item_create', 'guard_name' => 'web', 'group_name' => 'Item A Type'],
            ['name' => 'item_edit', 'guard_name' => 'web', 'group_name' => 'Item A Type'],
            ['name' => 'item_trash', 'guard_name' => 'web', 'group_name' => 'Item A Type'],
            ['name' => 'item_trash_view', 'guard_name' => 'web', 'group_name' => 'Item A Type'],
            ['name' => 'item_restore', 'guard_name' => 'web', 'group_name' => 'Item A Type'],
            ['name' => 'item_import', 'guard_name' => 'web', 'group_name' => 'Item A Type'],

            ['name' => 'item_b_view', 'guard_name' => 'web', 'group_name' => 'Item B Type'],
            ['name' => 'item_b_create', 'guard_name' => 'web', 'group_name' => 'Item B Type'],
            ['name' => 'item_b_edit', 'guard_name' => 'web', 'group_name' => 'Item B Type'],
            ['name' => 'item_b_trash', 'guard_name' => 'web', 'group_name' => 'Item B Type'],
            ['name' => 'item_b_trash_view', 'guard_name' => 'web', 'group_name' => 'Item B Type'],
            ['name' => 'item_b_restore', 'guard_name' => 'web', 'group_name' => 'Item B Type'],
            ['name' => 'item_b_import', 'guard_name' => 'web', 'group_name' => 'Item B Type'],

            ['name' => 'item_c_view', 'guard_name' => 'web', 'group_name' => 'Item C Type'],
            ['name' => 'item_c_create', 'guard_name' => 'web', 'group_name' => 'Item C Type'],
            ['name' => 'item_c_edit', 'guard_name' => 'web', 'group_name' => 'Item C Type'],
            ['name' => 'item_c_trash', 'guard_name' => 'web', 'group_name' => 'Item C Type'],
            ['name' => 'item_c_trash_view', 'guard_name' => 'web', 'group_name' => 'Item C Type'],
            ['name' => 'item_c_restore', 'guard_name' => 'web', 'group_name' => 'Item C Type'],
            ['name' => 'item_c_import', 'guard_name' => 'web', 'group_name' => 'Item C Type'],

            ['name' => 'item_d_view', 'guard_name' => 'web', 'group_name' => 'Item D Type'],
            ['name' => 'item_d_create', 'guard_name' => 'web', 'group_name' => 'Item D Type'],
            ['name' => 'item_d_edit', 'guard_name' => 'web', 'group_name' => 'Item D Type'],
            ['name' => 'item_d_trash', 'guard_name' => 'web', 'group_name' => 'Item D Type'],
            ['name' => 'item_d_trash_view', 'guard_name' => 'web', 'group_name' => 'Item D Type'],
            ['name' => 'item_d_restore', 'guard_name' => 'web', 'group_name' => 'Item D Type'],
            ['name' => 'item_d_import', 'guard_name' => 'web', 'group_name' => 'Item D Type'],

            ['name' => 'supplier_view', 'guard_name' => 'web', 'group_name' => 'Supplier'],
            ['name' => 'supplier_create', 'guard_name' => 'web', 'group_name' => 'Supplier'],
            ['name' => 'supplier_edit', 'guard_name' => 'web', 'group_name' => 'Supplier'],
            ['name' => 'supplier_trash', 'guard_name' => 'web', 'group_name' => 'Supplier'],
            ['name' => 'supplier_trash_view', 'guard_name' => 'web', 'group_name' => 'Supplier'],
            ['name' => 'supplier_restore', 'guard_name' => 'web', 'group_name' => 'Supplier'],

            ['name' => 'quotation_view', 'guard_name' => 'web', 'group_name' => 'Quotation'],
            ['name' => 'quotation_create', 'guard_name' => 'web', 'group_name' => 'Quotation'],
            ['name' => 'quotation_edit', 'guard_name' => 'web', 'group_name' => 'Quotation'],
            ['name' => 'quotation_trash', 'guard_name' => 'web', 'group_name' => 'Quotation'],
            ['name' => 'quotation_trash_view', 'guard_name' => 'web', 'group_name' => 'Quotation'],
            ['name' => 'quotation_restore', 'guard_name' => 'web', 'group_name' => 'Quotation'],

            ['name' => 'contractor_view', 'guard_name' => 'web', 'group_name' => 'Contractor'],
            ['name' => 'contractor_create', 'guard_name' => 'web', 'group_name' => 'Contractor'],
            ['name' => 'contractor_edit', 'guard_name' => 'web', 'group_name' => 'Contractor'],
            ['name' => 'contractor_trash', 'guard_name' => 'web', 'group_name' => 'Contractor'],
            ['name' => 'contractor_trash_view', 'guard_name' => 'web', 'group_name' => 'Contractor'],
            ['name' => 'contractor_restore', 'guard_name' => 'web', 'group_name' => 'Contractor'],

            ['name' => 'warehouse_view', 'guard_name' => 'web', 'group_name' => 'Warehouse'],
            ['name' => 'warehouse_create', 'guard_name' => 'web', 'group_name' => 'Warehouse'],
            ['name' => 'warehouse_edit', 'guard_name' => 'web', 'group_name' => 'Warehouse'],
            ['name' => 'warehouse_trash', 'guard_name' => 'web', 'group_name' => 'Warehouse'],
            ['name' => 'warehouse_trash_view', 'guard_name' => 'web', 'group_name' => 'Warehouse'],
            ['name' => 'warehouse_restore', 'guard_name' => 'web', 'group_name' => 'Warehouse'],

            ['name' => 'stock_view', 'guard_name' => 'web', 'group_name' => 'StockIn'],
            ['name' => 'stock_create', 'guard_name' => 'web', 'group_name' => 'StockIn'],
            ['name' => 'stock_edit', 'guard_name' => 'web', 'group_name' => 'StockIn'],
            ['name' => 'stock_trash', 'guard_name' => 'web', 'group_name' => 'StockIn'],
            ['name' => 'stock_trash_view', 'guard_name' => 'web', 'group_name' => 'StockIn'],
            ['name' => 'stock_restore', 'guard_name' => 'web', 'group_name' => 'StockIn'],

            ['name' => 'stockOut_view', 'guard_name' => 'web', 'group_name' => 'StockOut'],
            ['name' => 'stockOut_create', 'guard_name' => 'web', 'group_name' => 'StockOut'],
            ['name' => 'stockOut_edit', 'guard_name' => 'web', 'group_name' => 'StockOut'],
            ['name' => 'stockOut_trash', 'guard_name' => 'web', 'group_name' => 'StockOut'],
            ['name' => 'stockOut_trash_view', 'guard_name' => 'web', 'group_name' => 'StockOut'],
            ['name' => 'stockOut_restore', 'guard_name' => 'web', 'group_name' => 'StockOut'],

            ['name' => 'stockCheck_view', 'guard_name' => 'web', 'group_name' => 'stockCheck'],

            ['name' => 'itemDemand_view', 'guard_name' => 'web', 'group_name' => 'ItemDemand'],
            ['name' => 'itemDemand_view_button', 'guard_name' => 'web', 'group_name' => 'ItemDemand'],
            ['name' => 'itemDemand_issue', 'guard_name' => 'web', 'group_name' => 'ItemDemand'],
            ['name' => 'itemDemand_create', 'guard_name' => 'web', 'group_name' => 'ItemDemand'],
            ['name' => 'itemDemand_edit', 'guard_name' => 'web', 'group_name' => 'ItemDemand'],
            ['name' => 'itemDemand_trash', 'guard_name' => 'web', 'group_name' => 'ItemDemand'],
            ['name' => 'itemDemand_trash_view', 'guard_name' => 'web', 'group_name' => 'ItemDemand'],
            ['name' => 'itemDemand_restore', 'guard_name' => 'web', 'group_name' => 'ItemDemand'],

            ['name' => 'purchaseOrder_view', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_view_button', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_create', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_edit', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_trash', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_trash_view', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_restore', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_list', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_items_list', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_view_pdf', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_upload_pdf', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],
            ['name' => 'purchaseOrder_receive_item', 'guard_name' => 'web', 'group_name' => 'PurchaseOrder'],

            ['name' => 'comparativeStatement_view', 'guard_name' => 'web', 'group_name' => 'Comparative Statement'],
            ['name' => 'comparativeStatement_view_button', 'guard_name' => 'web', 'group_name' => 'Comparative Statement'],
            ['name' => 'comparativeStatement_create', 'guard_name' => 'web', 'group_name' => 'Comparative Statement'],
            ['name' => 'comparativeStatement_invoice', 'guard_name' => 'web', 'group_name' => 'Comparative Statement'],

            // ['name' => 'houseType_view', 'guard_name' => 'web', 'group_name' => 'HouseType'],
            // ['name' => 'houseType_create', 'guard_name' => 'web', 'group_name' => 'HouseType'],
            // ['name' => 'houseType_edit', 'guard_name' => 'web', 'group_name' => 'HouseType'],
            // ['name' => 'houseType_trash', 'guard_name' => 'web', 'group_name' => 'HouseType'],
            // ['name' => 'houseType_trash_view', 'guard_name' => 'web', 'group_name' => 'HouseType'],
            // ['name' => 'houseType_restore', 'guard_name' => 'web', 'group_name' => 'HouseType'],

            // ['name' => 'contractorProject_view', 'guard_name' => 'web', 'group_name' => 'Contractor Project'],
            // ['name' => 'contractorProject_status', 'guard_name' => 'web', 'group_name' => 'Contractor Project'],

            ['name' => 'bank_view', 'guard_name' => 'web', 'group_name' => 'Bank'],
            ['name' => 'bank_create', 'guard_name' => 'web', 'group_name' => 'Bank'],
            ['name' => 'bank_edit', 'guard_name' => 'web', 'group_name' => 'Bank'],
            ['name' => 'bank_trash', 'guard_name' => 'web', 'group_name' => 'Bank'],
            ['name' => 'bank_trash_view', 'guard_name' => 'web', 'group_name' => 'Bank'],
            ['name' => 'bank_restore', 'guard_name' => 'web', 'group_name' => 'Bank'],

            ['name' => 'employee_department_view', 'guard_name' => 'web', 'group_name' => 'Department'],
            ['name' => 'employee_department_create', 'guard_name' => 'web', 'group_name' => 'Department'],
            ['name' => 'employee_department_edit', 'guard_name' => 'web', 'group_name' => 'Department'],
            ['name' => 'employee_department_trash', 'guard_name' => 'web', 'group_name' => 'Department'],
            ['name' => 'employee_department_trash_view', 'guard_name' => 'web', 'group_name' => 'Department'],
            ['name' => 'employee_department_restore', 'guard_name' => 'web', 'group_name' => 'Department'],

            ['name' => 'designation_view', 'guard_name' => 'web', 'group_name' => 'Designation'],
            ['name' => 'designation_create', 'guard_name' => 'web', 'group_name' => 'Designation'],
            ['name' => 'designation_edit', 'guard_name' => 'web', 'group_name' => 'Designation'],
            ['name' => 'designation_trash', 'guard_name' => 'web', 'group_name' => 'Designation'],
            ['name' => 'designation_trash_view', 'guard_name' => 'web', 'group_name' => 'Designation'],
            ['name' => 'designation_restore', 'guard_name' => 'web', 'group_name' => 'Designation'],

            ['name' => 'employee_view', 'guard_name' => 'web', 'group_name' => 'Employee'],
            ['name' => 'employee_create', 'guard_name' => 'web', 'group_name' => 'Employee'],
            ['name' => 'employee_show', 'guard_name' => 'web', 'group_name' => 'Employee'],
            ['name' => 'employee_edit', 'guard_name' => 'web', 'group_name' => 'Employee'],
            ['name' => 'employee_trash', 'guard_name' => 'web', 'group_name' => 'Employee'],
            ['name' => 'employee_trash_view', 'guard_name' => 'web', 'group_name' => 'Employee'],
            ['name' => 'employee_restore', 'guard_name' => 'web', 'group_name' => 'Employee'],

            ['name' => 'payroll_view', 'guard_name' => 'web', 'group_name' => 'Payroll'],
            ['name' => 'payroll_create', 'guard_name' => 'web', 'group_name' => 'Payroll'],
            ['name' => 'payroll_edit', 'guard_name' => 'web', 'group_name' => 'Payroll'],
            ['name' => 'payroll_trash', 'guard_name' => 'web', 'group_name' => 'Payroll'],
            ['name' => 'payroll_trash_view', 'guard_name' => 'web', 'group_name' => 'Payroll'],
            ['name' => 'payroll_show', 'guard_name' => 'web', 'group_name' => 'Payroll'],
            ['name' => 'payroll_restore', 'guard_name' => 'web', 'group_name' => 'Payroll'],

            ['name' => 'application_view', 'guard_name' => 'web', 'group_name' => 'Application'],
            ['name' => 'application_create', 'guard_name' => 'web', 'group_name' => 'Application'],
            ['name' => 'application_edit', 'guard_name' => 'web', 'group_name' => 'Application'],
            ['name' => 'application_trash', 'guard_name' => 'web', 'group_name' => 'Application'],
            ['name' => 'application_details', 'guard_name' => 'web', 'group_name' => 'Application'],
            ['name' => 'application_trash_view', 'guard_name' => 'web', 'group_name' => 'Application'],
            ['name' => 'application_restore', 'guard_name' => 'web', 'group_name' => 'Application'],

            ['name' => 'attendance_view', 'guard_name' => 'web', 'group_name' => 'Attendance'],
            ['name' => 'attendance_create', 'guard_name' => 'web', 'group_name' => 'Attendance'],
            ['name' => 'attendance_edit', 'guard_name' => 'web', 'group_name' => 'Attendance'],
            ['name' => 'attendance_trash', 'guard_name' => 'web', 'group_name' => 'Attendance'],
            ['name' => 'attendance_trash_view', 'guard_name' => 'web', 'group_name' => 'Attendance'],
            ['name' => 'attendance_restore', 'guard_name' => 'web', 'group_name' => 'Attendance'],

            ['name' => 'leave_view', 'guard_name' => 'web', 'group_name' => 'Leave'],
            ['name' => 'leave_create', 'guard_name' => 'web', 'group_name' => 'Leave'],
            ['name' => 'leave_edit', 'guard_name' => 'web', 'group_name' => 'Leave'],
            ['name' => 'leave_trash', 'guard_name' => 'web', 'group_name' => 'Leave'],
            ['name' => 'leave_trash_view', 'guard_name' => 'web', 'group_name' => 'Leave'],
            ['name' => 'leave_restore', 'guard_name' => 'web', 'group_name' => 'Leave'],

            ['name' => 'activity_view', 'guard_name' => 'web', 'group_name' => 'Activity'],
            ['name' => 'activity_create', 'guard_name' => 'web', 'group_name' => 'Activity'],
            ['name' => 'activity_edit', 'guard_name' => 'web', 'group_name' => 'Activity'],
            ['name' => 'activity_trash', 'guard_name' => 'web', 'group_name' => 'Activity'],
            ['name' => 'activity_trash_view', 'guard_name' => 'web', 'group_name' => 'Activity'],
            ['name' => 'activity_restore', 'guard_name' => 'web', 'group_name' => 'Activity'],

            ['name' => 'planning_view', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_assign_activity_combine', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_create', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_edit', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_show', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_trash', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_trash_view', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_restore', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_assign_activity_view', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_graph_view', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'ir_report_view', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'quality_supervisor_progress_update', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'project_manager_progress_update', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_engineer_progress_update', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'ceo_progress_update', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_billing_request', 'guard_name' => 'web', 'group_name' => 'Planning'],
            ['name' => 'planning_billing_view', 'guard_name' => 'web', 'group_name' => 'Planning'],

            ['name' => 'billing_view', 'guard_name' => 'web', 'group_name' => 'Billing'],
            ['name' => 'billing_approve', 'guard_name' => 'web', 'group_name' => 'Billing'],
            ['name' => 'billing_reject', 'guard_name' => 'web', 'group_name' => 'Billing'],

            ['name' => 'holiday_view', 'guard_name' => 'web', 'group_name' => 'Holiday'],
            ['name' => 'holiday_create', 'guard_name' => 'web', 'group_name' => 'Holiday'],
            ['name' => 'holiday_edit', 'guard_name' => 'web', 'group_name' => 'Holiday'],
            ['name' => 'holiday_trash', 'guard_name' => 'web', 'group_name' => 'Holiday'],
            ['name' => 'holiday_trash_view', 'guard_name' => 'web', 'group_name' => 'Holiday'],
            ['name' => 'holiday_restore', 'guard_name' => 'web', 'group_name' => 'Holiday'],

            ['name' => 'all_notifications_view', 'guard_name' => 'web', 'group_name' => 'Notifications'],

            

            // ['name' => 'brand_view', 'guard_name' => 'web', 'group_name' => 'Brand'],
            // ['name' => 'brand_create', 'guard_name' => 'web', 'group_name' => 'Brand'],
            // ['name' => 'brand_edit', 'guard_name' => 'web', 'group_name' => 'Brand'],
            // ['name' => 'brand_trash', 'guard_name' => 'web', 'group_name' => 'Brand'],
            // ['name' => 'brand_trash_view', 'guard_name' => 'web', 'group_name' => 'Brand'],
            // ['name' => 'brand_restore', 'guard_name' => 'web', 'group_name' => 'Brand'],

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
}
