<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Services\KriteriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KriteriaController extends Controller
{
    public function __construct(private readonly KriteriaService $kriteriaService)
    {
    }

    public function index(): View
    {
        $search = request('search');

        return view('kriterias.index', [
            'kriterias' => $this->kriteriaService->paginate(search: $search),
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'create-kriteria');
        }

        $this->kriteriaService->create($validator->validated());

        return to_route('kriterias.index')->with('success', 'Data kriteria berhasil ditambahkan.');
    }

    public function update(Request $request, Kriteria $kriteria): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules($kriteria), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'edit-kriteria-'.$kriteria->id);
        }

        $this->kriteriaService->update($kriteria, $validator->validated());

        return to_route('kriterias.index')->with('success', 'Data kriteria berhasil diperbarui.');
    }

    public function destroy(Kriteria $kriteria): RedirectResponse
    {
        $this->kriteriaService->delete($kriteria);

        return to_route('kriterias.index')->with('success', 'Data kriteria berhasil dihapus.');
    }

    private function rules(?Kriteria $kriteria = null): array
    {
        return [
            'kode_kriteria' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kriterias', 'kode_kriteria')->ignore($kriteria?->id),
            ],
            'nama_kriteria' => ['required', 'string', 'max:255'],
            'bobot' => ['required', 'numeric', 'min:0', 'max:1'],
        ];
    }

    private function messages(): array
    {
        return [
            'kode_kriteria.required' => 'Kode kriteria wajib diisi.',
            'kode_kriteria.string' => 'Kode kriteria harus berupa teks.',
            'kode_kriteria.max' => 'Kode kriteria maksimal 50 karakter.',
            'kode_kriteria.unique' => 'Kode kriteria ini sudah digunakan. Silakan gunakan kode lain.',
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_kriteria.string' => 'Nama kriteria harus berupa teks.',
            'nama_kriteria.max' => 'Nama kriteria maksimal 255 karakter.',
            'bobot.required' => 'Bobot wajib diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka atau desimal.',
            'bobot.min' => 'Bobot minimal 0.',
            'bobot.max' => 'Bobot maksimal 1.',
        ];
    }
}
