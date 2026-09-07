<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\Siswa;
use App\Models\Spk;
use App\Models\SpkKejadian;
use App\Models\TingkatKeterlibatan;
use App\Services\Spk\SpkCalculationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpkController extends Controller
{
    public function __construct(private readonly SpkCalculationService $spkService) {}

    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_akhir' => array_filter(['nullable', 'date', $request->filled('tanggal_mulai') ? 'after_or_equal:tanggal_mulai' : null]),
            'tanggal' => ['nullable', 'date'],
        ]);
        $tanggalMulai = $request->string('tanggal_mulai')->toString();
        $tanggalAkhir = $request->string('tanggal_akhir')->toString();
        $tanggal = $request->string('tanggal')->toString();

        if ($tanggalMulai !== '' && $tanggalAkhir !== '') {
            $tanggal = '';
        } elseif (($tanggalMulai === '') !== ($tanggalAkhir === '')) {
            $tanggal = $tanggalMulai ?: $tanggalAkhir;
        }

        $spks = $this->spkService->listResults([
            'tanggal' => $tanggal,
            'tanggal_mulai' => $tanggalMulai && $tanggalAkhir ? $tanggalMulai : null,
            'tanggal_akhir' => $tanggalMulai && $tanggalAkhir ? $tanggalAkhir : null,
            'search' => $search,
            'hidden_ids' => session('spk_hidden_ids', []),
        ]);

        return view('spk.index', [
            'siswas' => Siswa::query()->oldest()->get(),
            'jenisPelanggarans' => JenisPelanggaran::query()->oldest()->get(),
            'tingkatKeterlibatans' => TingkatKeterlibatan::query()->oldest()->get(),
            'spks' => $spks,
            'pointTotals' => SpkKejadian::query()
                ->selectRaw('siswa_id, SUM(poin_snapshot) as total_poin')
                ->groupBy('siswa_id')
                ->pluck('total_poin', 'siswa_id'),
            'searchTanggalMulai' => $tanggalMulai,
            'searchTanggalAkhir' => $tanggalAkhir,
            'searchStudent' => $search,
        ]);
    }

    public function resetDisplay(Request $request): RedirectResponse
    {
        $request->session()->put('spk_hidden_ids', Spk::query()->pluck('id')->all());

        return to_route('spk.index')->with('success', 'Tampilan SPK direset. Data database tetap aman.');
    }

    public function store(Request $request): RedirectResponse
    {
        $spk = $this->spkService->process($request->only(['siswa_id', 'jenis_pelanggaran_id', 'tingkat_keterlibatan_id']));

        return to_route('spk.index')->with(
            $spk->jenis_hukuman_id ? 'success' : 'info',
            $spk->jenis_hukuman_id
                ? 'Proses SPK tersimpan dan hukuman yang sesuai berhasil ditemukan.'
                : 'Proses SPK tersimpan. Belum ada hukuman yang sesuai dengan nilai akhir.'
        );
    }

    public function destroy(Spk $spk): RedirectResponse
    {
        $spk->delete();

        return to_route('spk.index')->with('success', 'Data SPK berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_akhir' => array_filter(['nullable', 'date', $request->filled('tanggal_mulai') ? 'after_or_equal:tanggal_mulai' : null]),
            'tanggal' => ['nullable', 'date'],
        ]);
        $tanggalMulai = $request->string('tanggal_mulai')->toString();
        $tanggalAkhir = $request->string('tanggal_akhir')->toString();
        $tanggal = $request->string('tanggal')->toString();

        if ($tanggalMulai !== '' && $tanggalAkhir !== '') {
            $tanggal = '';
        } elseif (($tanggalMulai === '') !== ($tanggalAkhir === '')) {
            $tanggal = $tanggalMulai ?: $tanggalAkhir;
        }

        $search = trim($request->string('search')->toString());

        $results = $this->spkService->exportDataset([
            'tanggal' => $tanggal,
            'tanggal_mulai' => $tanggalMulai && $tanggalAkhir ? $tanggalMulai : null,
            'tanggal_akhir' => $tanggalMulai && $tanggalAkhir ? $tanggalAkhir : null,
            'search' => $search,
        ]);

        $pdf = Pdf::loadView('spk.pdf', [
            'results' => $results,
            'tanggal' => $tanggal,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
            'search' => $search,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('spk-'.now()->format('Ymd-His').'.pdf');
    }
}
