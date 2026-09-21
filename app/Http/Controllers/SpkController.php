<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\Siswa;
use App\Models\Spk;
use App\Models\SpkCalonSiswa;
use App\Models\SpkKejadian;
use App\Models\TingkatKeterlibatan;
use App\Services\Spk\SpkCalculationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SpkController extends Controller
{
    public function __construct(private readonly SpkCalculationService $spkService) {}

    public function index(Request $request): View
    {
        $activeBatchId = $this->activeBatchId($request);
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
            'user_id' => $request->user()->id,
            'tanggal' => $tanggal,
            'tanggal_mulai' => $tanggalMulai && $tanggalAkhir ? $tanggalMulai : null,
            'tanggal_akhir' => $tanggalMulai && $tanggalAkhir ? $tanggalAkhir : null,
            'search' => $search,
            'active_batch_id' => $activeBatchId,
        ]);

        return view('spk.index', [
            'candidates' => SpkCalonSiswa::query()
                ->with(['jenisPelanggaran', 'tingkatKeterlibatan'])
                ->where('user_id', $request->user()->id)
                ->whereNull('diproses_pada')
                ->oldest()
                ->get(),
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
        $request->session()->put('spk_active_batch_id', (string) Str::uuid());

        return to_route('spk.index')->with('success', 'Proses SPK direset. Data proses sebelumnya tetap tersimpan sebagai history.');
    }

    public function storeCandidate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50', 'regex:/^(VII|VIII|IX)(-[IVXLCDM0-9A-Z]+)?$/'],
            'jenis_pelanggaran_id' => ['required', 'exists:jenis_pelanggarans,id'],
            'tingkat_keterlibatan_id' => ['required', 'exists:tingkat_keterlibatans,id'],
        ]);

        SpkCalonSiswa::create($data + ['user_id' => $request->user()->id]);

        return to_route('spk.index')->with('success', 'Siswa berhasil disimpan ke daftar calon proses SPK.');
    }

    public function updateCandidate(Request $request, SpkCalonSiswa $candidate): RedirectResponse
    {
        abort_unless($candidate->user_id === $request->user()->id && $candidate->diproses_pada === null, 404);
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50', 'regex:/^(VII|VIII|IX)(-[IVXLCDM0-9A-Z]+)?$/'],
            'jenis_pelanggaran_id' => ['required', 'exists:jenis_pelanggarans,id'],
            'tingkat_keterlibatan_id' => ['required', 'exists:tingkat_keterlibatans,id'],
        ]);
        $candidate->update($data);

        return to_route('spk.index')->with('success', 'Data calon siswa berhasil diperbarui.');
    }

    public function destroyCandidate(Request $request, SpkCalonSiswa $candidate): RedirectResponse
    {
        abort_unless($candidate->user_id === $request->user()->id && $candidate->diproses_pada === null, 404);
        $candidate->delete();

        return to_route('spk.index')->with('success', 'Data calon siswa berhasil dihapus.');
    }

    public function process(Request $request): RedirectResponse
    {
        $candidates = SpkCalonSiswa::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('diproses_pada')
            ->oldest()
            ->get();

        if ($candidates->count() < 5) {
            return to_route('spk.index')->with('error', 'Minimal 5 siswa diperlukan untuk menjalankan proses SPK.');
        }

        $this->spkService->processPending($candidates, $request->user(), $this->activeBatchId($request));

        return to_route('spk.index')->with('success', 'Proses SPK berhasil dijalankan untuk semua siswa yang dipilih.');
    }

    public function destroy(Spk $spk): RedirectResponse
    {
        abort_unless($spk->user_id === request()->user()->id, 404);
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
        $activeBatchId = $this->activeBatchId($request);

        $results = $this->spkService->exportDataset([
            'user_id' => $request->user()->id,
            'tanggal' => $tanggal,
            'tanggal_mulai' => $tanggalMulai && $tanggalAkhir ? $tanggalMulai : null,
            'tanggal_akhir' => $tanggalMulai && $tanggalAkhir ? $tanggalAkhir : null,
            'search' => $search,
            'active_batch_id' => $activeBatchId,
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

    private function activeBatchId(Request $request): string
    {
        $batchId = $request->session()->get('spk_active_batch_id');

        if (! $batchId) {
            $batchId = Spk::query()
                ->where('user_id', $request->user()->id)
                ->latest('tanggal_spk')
                ->latest('id')
                ->value('batch_id') ?: (string) Str::uuid();

            $request->session()->put('spk_active_batch_id', $batchId);
        }

        return $batchId;
    }
}
