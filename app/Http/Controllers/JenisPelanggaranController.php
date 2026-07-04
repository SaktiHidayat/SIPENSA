<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Services\JenisPelanggaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $this->jenisPelanggaranService->create($validator->validated());

        return to_route('pelanggarans.index')->with('success', 'Data jenis pelanggaran berhasil ditambahkan.');
    }

    public function update(Request $request, JenisPelanggaran $jenisPelanggaran): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules($jenisPelanggaran), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'edit-jenis-pelanggaran-'.$jenisPelanggaran->id);
        }

        $this->jenisPelanggaranService->update($jenisPelanggaran, $validator->validated());

        return to_route('pelanggarans.index')->with('success', 'Data jenis pelanggaran berhasil diperbarui.');
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran): RedirectResponse
    {
        $this->jenisPelanggaranService->delete($jenisPelanggaran);

        return to_route('pelanggarans.index')->with('success', 'Data jenis pelanggaran berhasil dihapus.');
    }

    private function rules(?JenisPelanggaran $jenisPelanggaran = null): array
    {
        return [
            'kode_pelanggaran' => [
                'required',
                'string',
                'max:50',
                Rule::unique('jenis_pelanggarans', 'kode_pelanggaran')->ignore($jenisPelanggaran?->id),
            ],
            'nama_pelanggaran' => ['required', 'string', 'max:255'],
            'poin_pelanggaran' => ['required', 'integer', 'min:0', 'max:300'],
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
            'poin_pelanggaran.integer' => 'Poin pelanggaran harus berupa angka bulat.',
            'poin_pelanggaran.min' => 'Poin pelanggaran minimal 0.',
            'poin_pelanggaran.max' => 'Poin pelanggaran maksimal 300.',
        ];
    }
}
