<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'brand_name'  => '60 Grade Deformed Steel Bars',
                'owner_name'  => 'Ghulam Qadir',
                'cnic'        => '4520852625777',
                'mou_no'      => 'PNWHS/25/301/27',
                'mou_date'    => '2025-08-20',
                'name'        => 'Union Steel',
                'ntn'         => 'NTN-123',
                'address'     => 'D-36, SI.I.T.E Manghopir Road Karachi',
                'contact'     => '923359226611',
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'brand_name'  => '60 Grade Deformed Steel Bars',
                'owner_name'  => 'Faraz',
                'cnic'        => '4520828965788',
                'mou_no'      => 'ADCC (South)/25/04',
                'mou_date'    => '2025-05-27',
                'name'        => 'Naveena Steel (Pvt.) Ltd.',
                'ntn'         => 'NTN-124',
                'address'     => 'B-21, Block 7/8, Banglore Town, Main Shahrah-e-Faisal Karachi',
                'contact'     => '923330876661',
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'brand_name'  => 'OP Cement',
                'owner_name'  => 'Adnan',
                'cnic'        => '4526589562555',
                'mou_no'      => 'ADCC (South)/25/02',
                'mou_date'    => '2025-09-04',
                'name'        => 'DG Cement',
                'ntn'         => 'NTN-125',
                'address'     => 'DG Cement, Karachi',
                'contact'     => '923312317089',
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'brand_name'  => 'Solid Blocks',
                'owner_name'  => 'Naeem',
                'cnic'        => '2314563256222',
                'mou_no'      => 'ADCC/PNWHS/25/301/28',
                'mou_date'    => '2025-08-25',
                'name'        => 'Anchor Crete',
                'ntn'         => 'NTN-126',
                'address'     => 'PNWHS Bin Qasim, Karachi',
                'contact'     => '923002897735',
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'brand_name'  => '60 Grade Deformed Steel Bars',
                'owner_name'  => 'M Ali Hashmi',
                'cnic'        => '4563214589666',
                'mou_no'      => 'ADCC (South)/25/301/24',
                'mou_date'    => '2025-08-08',
                'name'        => 'HTC Steel',
                'ntn'         => 'NTN-127',
                'address'     => '320 & 321, 3rd Floor, Plot # B1/B9, S.P Chamber, S.I.TE Area Karachi',
                'contact'     => '923432110023',
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'brand_name'  => 'Binding Wire',
                'owner_name'  => 'Khuzaima',
                'cnic'        => '4521456325633',
                'mou_no'      => 'ADCC (South)/25/09',
                'mou_date'    => '2025-09-13',
                'name'        => 'Babjee Engineering',
                'ntn'         => 'NTN-128',
                'address'     => 'Karachi',
                'contact'     => '03458963214',
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
