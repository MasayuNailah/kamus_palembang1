<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TransaksiValidasi;
use App\Models\Kata;
use Illuminate\Support\Carbon;

echo "Running repair for transaksi_validasi rows with id_objek = 0 or empty\n";

$rows = TransaksiValidasi::whereIn('id_objek', ['0', '', null])->get();
echo "Found " . $rows->count() . " candidate rows.\n";
if ($rows->isEmpty()) exit(0);

foreach ($rows as $r) {
    echo "\nProcessing transaksi id={$r->id_transaksi} (kontributor={$r->id_kontributor}, tgl_kontribusi={$r->tgl_kontribusi})\n";

    if (!$r->tgl_kontribusi) {
        echo "  - No tgl_kontribusi available, skipping.\n";
        continue;
    }

    $t = Carbon::parse($r->tgl_kontribusi);
    // search kata by same contributor with created_at within +-10 seconds
    $candidates = Kata::where('id_kontributor', $r->id_kontributor)
        ->whereBetween('created_at', [$t->copy()->subSeconds(10), $t->copy()->addSeconds(10)])
        ->get();

    if ($candidates->count() === 1) {
        $k = $candidates->first();
        echo "  - Match found: kata id={$k->id_kata}, kata='{$k->kata}', created_at={$k->created_at}\n";
        $r->id_objek = $k->id_kata;
        $r->save();
        echo "  - Updated transaksi.id_objek to {$k->id_kata}\n";
    } elseif ($candidates->count() > 1) {
        echo "  - Multiple candidates found (" . $candidates->count() . "), listing: \n";
        foreach ($candidates as $k) {
            echo "      - {$k->id_kata} | {$k->kata} | {$k->created_at}\n";
        }
        echo "  - Manual inspection required.\n";
    } else {
        // try looser match: any kata by contributor within +-60 seconds
        $candidates = Kata::where('id_kontributor', $r->id_kontributor)
            ->whereBetween('created_at', [$t->copy()->subSeconds(60), $t->copy()->addSeconds(60)])
            ->get();
        if ($candidates->count() === 1) {
            $k = $candidates->first();
            echo "  - Loose match found: kata id={$k->id_kata}, kata='{$k->kata}', created_at={$k->created_at}\n";
            $r->id_objek = $k->id_kata;
            $r->save();
            echo "  - Updated transaksi.id_objek to {$k->id_kata}\n";
        } else {
            echo "  - No candidates found in +-60s window. Skipping.\n";
        }
    }
}

echo "Repair run complete. Review changes in the database.\n";

echo "Note: ALWAYS backup the database before running this on production.\n";
