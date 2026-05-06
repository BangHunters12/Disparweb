<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekomendasiSaw;
use App\Services\SawRecommendationService;
use Illuminate\Http\Request;

class SawController extends Controller
{
    public function index()
    {
        $weights = config('saw.weights');

        $rankings = RekomendasiSaw::whereNull('user_id')
            ->with(['tempat.kategori', 'tempat.kecamatan'])
            ->orderBy('peringkat')
            ->paginate(20);

        $lastCalculated = RekomendasiSaw::whereNull('user_id')
            ->latest('dihitung_at')
            ->first()?->dihitung_at;

        return view('admin.saw.index', compact('weights', 'rankings', 'lastCalculated'));
    }

    public function recalculate(Request $request, SawRecommendationService $service)
    {
        $weights = $request->validate([
            'w_rating'      => 'required|numeric|min:0|max:1',
            'w_sentimen'    => 'required|numeric|min:0|max:1',
            'w_harga'       => 'required|numeric|min:0|max:1',
            'w_popularitas' => 'required|numeric|min:0|max:1',
            'w_kebaruan'    => 'required|numeric|min:0|max:1',
        ]);

        $total = $weights['w_rating'] + $weights['w_sentimen'] + $weights['w_harga']
            + $weights['w_popularitas'] + $weights['w_kebaruan'];

        if (abs($total - 1.0) > 0.01) {
            return back()->with('error', 'Total bobot harus sama dengan 1.0 (100%).');
        }

        $newWeights = [
            'rating'      => (float) $weights['w_rating'],
            'sentimen'    => (float) $weights['w_sentimen'],
            'harga'       => (float) $weights['w_harga'],
            'popularitas' => (float) $weights['w_popularitas'],
            'kebaruan'    => (float) $weights['w_kebaruan'],
        ];

        // Update service instance
        $service->updateWeights($newWeights);

        // Simpan bobot ke config/saw.php agar permanen setelah redirect
        $this->persistWeights($newWeights);

        $count = $service->recalculateAll();

        return back()->with('success', "SAW berhasil dihitung ulang untuk {$count} tempat.");
    }

    /**
     * Tulis bobot baru ke file config/saw.php agar tersimpan permanen.
     */
    protected function persistWeights(array $weights): void
    {
        $configPath = config_path('saw.php');
        $current    = require $configPath;

        // Ganti hanya bagian weights
        $current['weights'] = $weights;

        $export = "<?php\n\nreturn " . $this->varExport($current) . ";\n";
        file_put_contents($configPath, $export);

        // Clear config cache agar nilai baru langsung terbaca
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($configPath, true);
        }
    }

    /**
     * var_export yang menghasilkan kode PHP rapi (pengganti var_export bawaan).
     */
    protected function varExport(mixed $value, int $indent = 0): string
    {
        $pad = str_repeat('    ', $indent);
        $pad1 = str_repeat('    ', $indent + 1);

        if (is_array($value)) {
            $isList = array_keys($value) === range(0, count($value) - 1);
            $items = [];
            foreach ($value as $k => $v) {
                $key = $isList ? '' : var_export($k, true) . ' => ';
                $items[] = $pad1 . $key . $this->varExport($v, $indent + 1);
            }
            return "[\n" . implode(",\n", $items) . ",\n{$pad}]";
        }

        if (is_float($value)) {
            // Pastikan float tampil dengan minimal 2 desimal agar PHP tidak baca sebagai int
            return number_format($value, 2, '.', '');
        }

        return var_export($value, true);
    }
}
