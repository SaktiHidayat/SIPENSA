<?php

namespace App\Http\Controllers;

use App\Models\TingkatKeterlibatan;
use App\Services\TingkatKeterlibatanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TingkatKeterlibatanController extends Controller
{
    public function __construct(private readonly TingkatKeterlibatanService $tingkatKeterlibatanService)
    {
    }

    public function index(): View
    {
        $search = request('search');

        return view('keterlibatans.index', [
            'tingkatKeterlibatans' => $this->tingkatKeterlibatanService->paginate(search: $search),
            'search' => $search,
            'fuzzyRules' => $this->tingkatKeterlibatanService->fuzzyRules(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'create-tingkat-keterlibatan');
        }

        $this->tingkatKeterlibatanService->create($validator->validated());

        return to_route('keterlibatans.index')->with('success', 'Data tingkat keterlibatan berhasil ditambahkan.');
    }

    public function update(Request $request, TingkatKeterlibatan $keterlibatan): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules($keterlibatan), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'edit-tingkat-keterlibatan-'.$keterlibatan->id);
        }

        $this->tingkatKeterlibatanService->update($keterlibatan, $validator->validated());

        return to_route('keterlibatans.index')->with('success', 'Data tingkat keterlibatan berhasil diperbarui.');
    }

    public function destroy(TingkatKeterlibatan $keterlibatan): RedirectResponse
    {
        $this->tingkatKeterlibatanService->delete($keterlibatan);

        return to_route('keterlibatans.index')->with('success', 'Data tingkat keterlibatan berhasil dihapus.');
    }

    private function rules(?TingkatKeterlibatan $tingkatKeterlibatan = null): array
    {
        return [
            'kode_tingkat_keterlibatan' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tingkat_keterlibatans', 'kode_tingkat_keterlibatan')->ignore($tingkatKeterlibatan?->id),
            ],
            'nama_keterangan' => ['required', Rule::in(TingkatKeterlibatan::labels())],
        ];
    }

    private function messages(): array
    {
        return [
            'kode_tingkat_keterlibatan.required' => 'Kode tingkat keterlibatan wajib diisi.',
            'kode_tingkat_keterlibatan.string' => 'Kode tingkat keterlibatan harus berupa teks.',
            'kode_tingkat_keterlibatan.max' => 'Kode tingkat keterlibatan maksimal 50 karakter.',
            'kode_tingkat_keterlibatan.unique' => 'Kode tingkat keterlibatan ini sudah digunakan. Silakan gunakan kode lain.',
            'nama_keterangan.required' => 'Nama keterangan wajib dipilih.',
            'nama_keterangan.in' => 'Nama keterangan yang dipilih tidak valid.',
        ];
    }
}
