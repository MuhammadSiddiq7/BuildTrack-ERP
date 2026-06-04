<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class taxSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // min, max, fixed, percent (applied to amount exceeding min_income)
            ['min_income'=>0,         'max_income'=>600000,   'fixed_tax'=>0,     'percentage'=>0],
            ['min_income'=>600000,    'max_income'=>1200000,  'fixed_tax'=>0,     'percentage'=>1],
            ['min_income'=>1200000,   'max_income'=>2200000,  'fixed_tax'=>6000,  'percentage'=>11],
            ['min_income'=>2200000,   'max_income'=>3200000,  'fixed_tax'=>116000,'percentage'=>23],
            ['min_income'=>3200000,   'max_income'=>4100000,  'fixed_tax'=>346000,'percentage'=>30],
            ['min_income'=>4100000,   'max_income'=>null,     'fixed_tax'=>616000,'percentage'=>35],
        ];

        foreach ($rows as $r) {
            Tax::updateOrCreate(
                ['min_income'=>$r['min_income'], 'max_income'=>$r['max_income']],
                ['fixed_tax'=>$r['fixed_tax'], 'percentage'=>$r['percentage']]
            );
        }
    }
}
