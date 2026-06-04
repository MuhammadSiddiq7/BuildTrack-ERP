<?php

namespace App\Imports;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ItemsImport implements ToCollection
{
    private $currentCategory = null;
    private $type;
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index == 0) continue;

            $sno  = $row[0];
            $item = $row[1];
            $size = $row[2];
            $deno = $row[3];
            $perhouse = $row[4];

            if (!empty($sno) && empty($item) && empty($size) && empty($deno)) {
                $this->currentCategory = trim($sno);
                continue;
            }

            if (empty($item) && empty($size) && empty($deno)) {
                continue;
            }

            if (empty($item) && $this->currentCategory) {
                $item = $this->currentCategory;
            }

            Item::create([
                'item' => $item ?? null,
                'size' => $size ?? null,
                'deno' => $deno ?? null,
                'per_house_qty' => $perhouse ?? null,
                'date' => Carbon::now()->toDateString(),
                'specification' => null,
                'status' => 'active',
                'items_type' => $this->type,
            ]);
        }
    }
}
