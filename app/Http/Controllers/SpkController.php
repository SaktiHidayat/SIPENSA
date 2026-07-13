<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\Spk;
use App\Models\Siswa;
use App\Models\TingkatKeterlibatan;
use App\Services\Spk\SpkCalculationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpkController extends Controller
{
    public function __construct(private readonly SpkCalculationService $spkService)
    {
    }

    public function index(Request $request): View
    {
        $spks = $this->spkService->listResults($request->only(['tanggal']));

        return view('spk.index', [
            'siswas' => Siswa::query()->oldest()->get(),
            'jenisPelanggarans' => JenisPelanggaran::query()->oldest()->get(),
            'tingkatKeterlibatans' => TingkatKeterlibatan::query()->oldest()->get(),
            'spks' => $spks,
            'searchTanggal' => $request->string('tanggal')->toString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->spkService->process($request->only(['siswa_id', 'jenis_pelanggaran_id', 'tingkat_keterlibatan_id']));

        return to_route('spk.index')->with('success', 'Data SPK berhasil dihitung.');
    }

    public function destroy(Spk $spk): RedirectResponse
    {
        $spk->delete();

        return to_route('spk.index')->with('success', 'Data SPK berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tanggal = $request->string('tanggal')->toString();

        $results = $this->spkService->exportDataset([
            'tanggal' => $tanggal,
        ]);

        $pdf = Pdf::loadView('spk.pdf', [
            'results' => $results,
            'tanggal' => $tanggal,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('spk-'.now()->format('Ymd-His').'.pdf');
    }
}
