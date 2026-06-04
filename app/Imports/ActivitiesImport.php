<?php

namespace App\Imports;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ActivitiesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $parentId = null;

        foreach ($rows as $row) {
            $description = trim($row[1] ?? ''); // Description col
            $yardstick   = trim($row[2] ?? '');
            $amount      = trim($row[3] ?? '');

            // Skip blank rows
            if (empty($description)) {
                continue;
            }

            // Detect parent / child
            if (!is_numeric($row[0]) && strlen($row[0]) > 0) {
                // Means "A, B, C" type => Main Activity
                $activity = Activity::create([
                    'activity_code' => null,
                    'name'          => $description,
                    'yardstick'     => null,
                    'amount'        => null,
                    'parent_id'     => null,
                    'status'        => 'active',
                ]);
                $parentId = $activity->id;
            } elseif (is_numeric($row[0])) {
                // Means numeric => Child Activity
                $activity = Activity::create([
                    'activity_code' => null,
                    'name'          => $description,
                    'yardstick'     => null,
                    'amount'        => null,
                    'parent_id'     => $parentId,
                    'status'        => 'active',
                ]);

                $lastChildId = $activity->id; // For nested a,b,c
            } elseif (preg_match('/^[a-z]$/i', $row[0])) {
                // a,b,c => Sub-child activity
                Activity::create([
                    'activity_code' => null,
                    'name'          => $description,
                    'yardstick'     => null,
                    'amount'        => null,
                    'parent_id'     => $lastChildId ?? $parentId,
                    'status'        => 'active',
                ]);
            }
        }
    }
}
