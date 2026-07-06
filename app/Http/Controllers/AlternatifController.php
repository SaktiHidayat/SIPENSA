<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\JenisPelanggaran;
use App\Models\Siswa;
use App\Models\TingkatKeterlibatan;
use App\Services\AlternatifService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AlternatifController extends Controller
{
    public function __construct(private readonly AlternatifService $alternatifService)
    {
    }

    public function index(): View
    {
        $search = request('search');

        return view('alternatifs.index', [
            'alternatifs' => $this->alternatifService->paginate(search: $search),
            'search' => $search,
            'siswas' => Siswa::query()->orderBy('nama')->get(),
            'jenisPelanggarans' => JenisPelanggaran::query()->orderBy('nama_pelanggaran')->get(),
            'tingkatKeterlibatans' => TingkatKeterlibatan::query()->orderBy('nama_keterangan')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'create-alternatif');
        }

        $this->alternatifService->create($validator->validated());

        return to_route('alternatifs.index')->with('success', 'Data alternatif berhasil ditambahkan.');
    }

    public function update(Request $request, Alternatif $alternatif): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules($alternatif->id), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'edit-alternatif-'.$alternatif->id);
        }

        $this->alternatifService->update($alternatif, $validator->validated());

        return to_route('alternatifs.index')->with('success', 'Data alternatif berhasil diperbarui.');
    }

    public function destroy(Alternatif $alternatif): RedirectResponse
    {
        $this->alternatifService->delete($alternatif);

        return to_route('alternatifs.index')->with('success', 'Data alternatif berhasil dihapus.');
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'kode_alternatif' => [
                'required',
                'string',
                'max:20',
                Rule::unique('alternatifs', 'kode_alternatif')->ignore($ignoreId),
            ],
            'siswa_id' => ['required', Rule::exists('siswas', 'id')],
            'jenis_pelanggaran_id' => ['required', Rule::exists('jenis_pelanggarans', 'id')],
            'tingkat_keterlibatan_id' => ['required', Rule::exists('tingkat_keterlibatans', 'id')],
        ];
    }

    private function messages(): array
    {
        return [
            'kode_alternatif.required' => 'Kode alternatif wajib diisi.',
            'kode_alternatif.unique' => 'Kode alternatif sudah digunakan.',
            'kode_alternatif.max' => 'Kode alternatif terlalu panjang.',
            'siswa_id.required' => 'Nama siswa wajib dipilih.',
            'siswa_id.exists' => 'Siswa yang dipilih tidak ditemukan.',
            'jenis_pelanggaran_id.required' => 'Jenis pelanggaran wajib dipilih.',
            'jenis_pelanggaran_id.exists' => 'Jenis pelanggaran yang dipilih tidak ditemukan.',
            'tingkat_keterlibatan_id.required' => 'Tingkat keterlibatan wajib dipilih.',
            'tingkat_keterlibatan_id.exists' => 'Tingkat keterlibatan yang dipilih tidak ditemukan.',
        ];
    }
}
