<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Warehouses
        $warehouse1 = DB::table('warehouses')->insertGetId([
            'name' => 'Warehouse NHS',
            'address' => 'Maripur',
            'description' => 'Maripur',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $warehouse2 = DB::table('warehouses')->insertGetId([
            'name' => 'Warehouse PNWHS',
            'address' => 'Bin Qasim',
            'description' => 'Bin Qasim',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Brands
        $brand1 = DB::table('brands')->insertGetId([
            'name' => 'Brand 1',
            'owner_name' => 'Owner name 1',
            'cnic' => '12345678901234',
            'contact_number' => '+91 5555555555',
            'description' => 'description 1',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $brand2 = DB::table('brands')->insertGetId([
            'name' => 'Brand 2',
            'owner_name' => 'Owner name 2',
            'cnic' => '12345678901234',
            'contact_number' => '+91 6666666666',
            'description' => 'description 2',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Items
        // $item1 = DB::table('items')->insertGetId([
        //     'item' => 'Steel',
        //     'size' => '12x12',
        //     'deno' => 'deno 1',
        //     'qty' => '0',
        //     'available_qty' => '0',
        //     'rate' => '12000',
        //     'total_amount_per_house' => '400000',
        //     'specification' => 'specification 1',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // $item2 = DB::table('items')->insertGetId([
        //     'item' => 'Cement',
        //     'size' => '24x24',
        //     'deno' => 'deno 2',
        //     'qty' => '0',
        //     'available_qty' => '0',
        //     'rate' => '10000',
        //     'total_amount_per_house' => '350000',
        //     'specification' => 'specification 2',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Suppliers
        // $supplier1 = DB::table('suppliers')->insertGetId([
        //     'name' => 'Supplier 1',
        //     'address' => 'supplier address 1',
        //     'contact' => '+91 3333333333',
        //     'ntn' => 'NTN-123',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // $supplier2 = DB::table('suppliers')->insertGetId([
        //     'name' => 'Supplier 2',
        //     'address' => 'supplier address 2',
        //     'contact' => '+91 4444444444',
        //     'ntn' => 'NTN-456',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Contractors
        $contractor1 = DB::table('contractors')->insertGetId([
            'name' => 'M/s Imam Baksh Builders',
            'contact_number' => '03002584189',
            'description' => 'AWARD OF 70 X DTH',
            'no_of_houses' => '3',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contractor2 = DB::table('contractors')->insertGetId([
            'name' => 'M/s Super Strong Construction',
            'contact_number' => '03212793034',
            'description' => 'AWARD OF 70 X DTH',
            'no_of_houses' => '5',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contractor3 = DB::table('contractors')->insertGetId([
            'name' => 'M/s Lajpal Construction Company',
            'contact_number' => '03005299768',
            'description' => 'AWARD OF 70 X DTH',
            'no_of_houses' => '5',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contractor4 = DB::table('contractors')->insertGetId([
            'name' => 'M/s Bapjee Enterprises',
            'contact_number' => '0123456778',
            'description' => 'AWARD OF 70 X DTH',
            'no_of_houses' => '5',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contractor5 = DB::table('contractors')->insertGetId([
            'name' => 'M/s AH Global',
            'contact_number' => '0123456778',
            'description' => 'AWARD OF 70 X DTH',
            'no_of_houses' => '5',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contractor6 = DB::table('contractors')->insertGetId([
            'name' => 'M/s Ghanni Engineering',
            'contact_number' => '0123456778',
            'description' => 'AWARD OF 70 X DTH',
            'no_of_houses' => '5',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Stocks
        // DB::table('stocks')->insert([
        //     [
        //         'item_id' => $item1,
        //         'warehouse_id' => $warehouse1,
        //         'contractor_id' => $contractor1,
        //         'quantity' => '500',
        //         'price' => '1500',
        //         'type' => 'in',
        //         'date' => now(),
        //         'created_by' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'item_id' => $item2,
        //         'warehouse_id' => $warehouse2,
        //         'contractor_id' => $contractor2,
        //         'quantity' => '800',
        //         'price' => '1700',
        //         'type' => 'in',
        //         'date' => now(),
        //         'created_by' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // ]);
        // Items Suppliers
        // DB::table('item_supplier')->insert([
        //     [
        //         'item_id' => $item1,
        //         'supplier_id' => $supplier1,
        //         'purchase_price' => '1500',
        //         'date' => now(),
        //         'remarks' => 'Good Supplier1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'item_id' => $item2,
        //         'supplier_id' => $supplier2,
        //         'purchase_price' => '3300',
        //         'date' => now(),
        //         'remarks' => 'Good Supplier2',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // ]);
        $house_type1 = DB::table('house_types')->insertGetId([
            'name' => 'DTH',
            'description' => '350 SQYD HOUSE',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // $house_type2 = DB::table('house_types')->insertGetId([
        //     'name' => 'type B1',
        //     'description' => 'description 2',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

            $employeedepartment1 = DB::table('employee_departments')->insertGetId([
                'name' => 'HR',
                'code' => 'HR-23',
                'description' => 'HR Department 1 Description',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $employeedepartment2 = DB::table('employee_departments')->insertGetId([
                'name' => 'Test',
                'code' => 'TEST-23',
                'description' => 'Test Department 2 Description',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        DB::table('designations')->insert([
            [
                'name' => 'Designation 1',
                'employee_department_id' => $employeedepartment1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Designation 2',
                'employee_department_id' => $employeedepartment2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        // $companyBank1 = DB::table('company_banks')->insertGetId([
        //         'name' => 'Company Bank 1',
        //         'branch' => 'Branch 1',
        //         'account_number' => '123456789',
        //         'iban' => '326862736823687',
        //         'status' => 'active',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);

        // $companyBank2 = DB::table('company_banks')->insertGetId([
        //     'name' => 'Company Bank 2',
        //     'branch' => 'Branch 2',
        //     'account_number' => '12345678229',
        //     'iban' => 'ST326862736823687',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        DB::table('company_banks')->insert([

            [
                'name' => 'Meezan Bank Limited-Anchor Crete',
                'branch' => 'Malir Cantt',
                'account_number' => '9967-0103945452',
                'iban' => 'PK69 MEZN 0099 6701 0394 5452',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited- Construction of 60 X DTH Houses-NHS Mauripur Karachi',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AM (HR)',
                'branch' => NULL,
                'account_number' => '9967-0103945452544545',
                'iban' => '9967-0103945452544545',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited-Infrastructure Works of Main Boulevard at Anchorage Karachi',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited-Construction of Entry Gate at Anchorage, Karachi',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited- Construction of 70 X DTH Houses-NHS Mauripur Karachi',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited- Construction of 40 X DTH Houses-NHS Mauripur Karachi',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited- Construction of 100 X DTH Houses-NHS Mauripur Karachi',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited-Infrastructure Work Sector 3 at PNWHS Bin Qasim Gharo',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited-Infrastructure Work Sector 4 at PNWHS Bin Qasim Gharo',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meezan Bank Limited-Construction of 935 Houses Project at PNWHS Bin Qasim Site Karachi.',
                'branch' => 'Malir Cantt',
                'account_number' => '9967-0103945452',
                'iban' => 'PK69 MEZN 0099 6701 0394 5452',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited-Construction of 301 Houses at PNWHS Bin Qasim, Karachi.',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meezan Bank Limited-Infrastructure Work Sector 1C & 2 at PNWHS Bin Qasim Gharo',
                'branch' => 'Malir Cantt',
                'account_number' => '9967-0103945452',
                'iban' => 'PK69 MEZN 0099 6701 0394 5452',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Askari Bank Limited-Installation of Street Light in Sector 1C & 2 at PNWHS Bin Qasim, Karachi',
                'branch' => 'Main Shahrah-e-Faisal',
                'account_number' => '7473890000068',
                'iban' => 'PK04 ASCM 0007 4738 9000 0068',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // Projects
        // $project1 = DB::table('projects')->insertGetId([
        //     'company_bank_id' => $companyBank1,
        //     'project_name' => 'Project 1',
        //     // 'project_name' => 'Project 1',
        //     'project_number' => '+91 1111111111',
        //     'project_location' => 'Abc location 1',
        //     'number_of_houses' => '101',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // $project2 = DB::table('projects')->insertGetId([
        //     'company_bank_id' => $companyBank2,
        //     'project_name' => 'Project 2',
        //     'project_number' => '+91 222222222',
        //     'project_location' => 'XYZ location 2',
        //     'number_of_houses' => '101',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // $houseproject1 = DB::table('house_projects')->insertGetId([
        //     'project_id' => $project1,
        //     // 'number_of_houses' => '101',
        //     'house_type_id' => $house_type1,
        //     'site_square_yard' => '1000',
        //     'warehouse_id' => $warehouse1,
        //     'description' => 'description 1',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // $houseproject2 = DB::table('house_projects')->insertGetId([
        //     'project_id' => $project2,
        //     // 'number_of_houses' => '102',
        //     'house_type_id' => $house_type2,
        //     'site_square_yard' => '1500',
        //     'warehouse_id' => $warehouse2,
        //     'description' => 'description 2',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // DB::table('contractor_project')->insert([
        //     [
        //         'project_id' => $project1,
        //         'house_project_id' => $houseproject1,
        //         'contractor_id' => $contractor1,
        //         'contract_number' => 'CN-72839',
        //         'status' => 'active',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'project_id' => $project2,
        //         'house_project_id' => $houseproject2,
        //         'contractor_id' => $contractor2,
        //         'contract_number' => 'CN-72840',
        //         'status' => 'active',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // ]);
        //    $employees1 = DB::table('employees')->insertGetId([
        //         'employee_department_id' => $employeedepartment1,
        //         'designation_id' => '1',
        //         'name' => 'Employee 1',
        //         'joining_date' => now(),
        //         'per_day_salary' => '1000',
        //         'number_of_working_days' => '30',
        //         'mobile_number' => '1234567890',
        //         'email' => 'VY8Tt@example.com',
        //         'employee_id' => 'EMP-1',
        //         'nationality' => 'Indian',
        //         'date_of_birth' => now(),
        //         'paid_leave' => '0',
        //         'unpaid_leave' => '3',
        //         'employee_status' => 'active',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //    ]);
        //     $employees2 = DB::table('employees')->insertGetId([
        //         'employee_department_id' => $employeedepartment2,
        //         'designation_id' => '2',
        //         'name' => 'Employee 2',
        //         'joining_date' => now(),
        //         'per_day_salary' => '2000',
        //         'number_of_working_days' => '28',
        //         'mobile_number' => '12345678904323',
        //         'email' => 'VY8Ttsd@example.com',
        //         'employee_id' => 'EMP-2',
        //         'nationality' => 'Pakistan',
        //         'date_of_birth' => now(),
        //         'paid_leave' => '1',
        //         'unpaid_leave' => '0',
        //         'employee_status' => 'active',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // DB::table('employee_banks')->insert([
        //     [
        //         'employee_id' => $employees1,
        //         'bank_name' => 'Bank 1',
        //         'account_title' => 'Account Title 1',
        //         'account_number' => '123456789',
        //         'iban' => '326862736823687',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'employee_id' => $employees2,
        //         'bank_name' => 'Bank 2',
        //         'account_title' => 'Account Title 2',
        //         'account_number' => '123456789',
        //         'iban' => '326862736823687',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // ]);
    }
}
