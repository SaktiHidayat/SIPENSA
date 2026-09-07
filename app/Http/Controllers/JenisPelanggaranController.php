<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Services\JenisPelanggaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JenisPelanggaranController extends Controller
{
    public function __construct(private readonly JenisPelanggaranService $jenisPelanggaranService)
    {
    }

    public function index(): View
    {
        $search = request('search');

        return view('pelanggarans.index', [
            'jenisPelanggarans' => $this->jenisPelanggaranService->paginate(search: $search),
            'search' => $search,
            'fuzzyRules' => $this->jenisPelanggaranService->fuzzyRules(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'create-jenis-pelanggaran');
        }

        try {
            $this->jenisPelanggaranService->create($this->normalizeValidatedData($validator->validated()));
        } catch (QueryException $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Data jenis pelanggaran gagal ditambahkan. Silakan cek kembali data yang diisi.');
        }

        return to_route('pelanggarans.index')->with('success', 'Data jenis pelanggaran berhasil ditambahkan.');
    }

    public function update(Request $request, string $pelanggaran): RedirectResponse
    {
        $current = JenisPelanggaran::query()->findOrFail($pelanggaran);
        $kodePelanggaran = trim((string) $request->input('kode_pelanggaran', $current->kode_pelanggaran));
        $validator = Validator::make(
            $request->all(),
            $this->rules($current, $kodePelanggaran),
            $this->messages()
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'edit-jenis-pelanggaran-'.$current->id);
        }

        try {
            $this->jenisPelanggaranService->update($current, $this->normalizeValidatedData($validator->validated()));
        } catch (QueryException $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Data jenis pelanggaran gagal diperbarui. Silakan cek kembali data yang diisi.');
        }

        return to_route('pelanggarans.index')->with('success', 'Data jenis pelanggaran berhasil diperbarui.');
    }

    public function destroy(string $pelanggaran): RedirectResponse
    {
        try {
            $current = JenisPelanggaran::query()->findOrFail($pelanggaran);
            $this->jenisPelanggaranService->delete($current);
        } catch (QueryException $exception) {
            report($exception);

            return back()->with('error', 'Data jenis pelanggaran gagal dihapus karena masih dipakai oleh data lain.');
        }

        return to_route('pelanggarans.index')->with('success', 'Data jenis pelanggaran berhasil dihapus.');
    }

    private function rules(?JenisPelanggaran $jenisPelanggaran = null, ?string $kodePelanggaran = null): array
    {
        $kodePelanggaran = $kodePelanggaran ?? '';

        $kodeRules = [
            $jenisPelanggaran ? 'nullable' : 'required',
            'string',
            'max:50',
        ];

        if ($jenisPelanggaran === null || $kodePelanggaran !== $jenisPelanggaran->kode_pelanggaran) {
            $kodeRules[] = Rule::unique('jenis_pelanggarans', 'kode_pelanggaran')->ignore($jenisPelanggaran?->id);
        }

        return [
            'kode_pelanggaran' => $kodeRules,
            'nama_pelanggaran' => [$jenisPelanggaran ? 'nullable' : 'required', 'string', 'max:255'],
            'poin_pelanggaran' => [$jenisPelanggaran ? 'nullable' : 'required', 'integer', 'min:5', 'max:300'],
        ];
    }

    private function messages(): array
    {
        return [
            'kode_pelanggaran.required' => 'Kode pelanggaran wajib diisi.',
            'kode_pelanggaran.string' => 'Kode pelanggaran harus berupa teks.',
            'kode_pelanggaran.max' => 'Kode pelanggaran maksimal 50 karakter.',
            'kode_pelanggaran.unique' => 'Kode pelanggaran ini sudah digunakan. Silakan gunakan kode lain.',
            'nama_pelanggaran.required' => 'Nama pelanggaran wajib diisi.',
            'nama_pelanggaran.string' => 'Nama pelanggaran harus berupa teks.',
            'nama_pelanggaran.max' => 'Nama pelanggaran maksimal 255 karakter.',
            'poin_pelanggaran.required' => 'Poin pelanggaran wajib diisi.',
            'poin_pelanggaran.integer' => 'Poin pelanggaran harus berupa satu angka bulat.',
            'poin_pelanggaran.min' => 'Poin pelanggaran minimal 5.',
            'poin_pelanggaran.max' => 'Poin pelanggaran maksimal 300.',
        ];
    }

    private function normalizeValidatedData(array $data): array
    {
        $data['poin_pelanggaran'] = preg_replace('/\s+/', '', (string) $data['poin_pelanggaran']);

        return $data;
    }
}
