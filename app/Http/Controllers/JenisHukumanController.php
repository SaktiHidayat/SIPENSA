<?php

namespace App\Http\Controllers;

use App\Models\JenisHukuman;
use App\Services\JenisHukumanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JenisHukumanController extends Controller
{
    public function __construct(private readonly JenisHukumanService $jenisHukumanService)
    {
    }

    public function index(): View
    {
        $search = request('search');

        return view('hukumans.index', [
            'jenisHukumans' => $this->jenisHukumanService->paginate(search: $search),
            'search' => $search,
            'categories' => JenisHukuman::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'create-jenis-hukuman');
        }

        $this->jenisHukumanService->create($validator->validated());

        return to_route('hukumans.index')->with('success', 'Data jenis hukuman berhasil ditambahkan.');
    }

    public function update(Request $request, JenisHukuman $hukuman): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules($hukuman), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'edit-jenis-hukuman-'.$hukuman->id);
        }

        $this->jenisHukumanService->update($hukuman, $validator->validated());

        return to_route('hukumans.index')->with('success', 'Data jenis hukuman berhasil diperbarui.');
    }

    public function destroy(JenisHukuman $hukuman): RedirectResponse
    {
        $this->jenisHukumanService->delete($hukuman);

        return to_route('hukumans.index')->with('success', 'Data jenis hukuman berhasil dihapus.');
    }

    private function rules(?JenisHukuman $hukuman = null): array
    {
        return [
            'kode_hukuman' => [
                'required',
                'string',
                'max:50',
                Rule::unique('jenis_hukumans', 'kode_hukuman')->ignore($hukuman?->id),
            ],
            'nama_hukuman' => ['required', 'string', 'max:255'],
            'kategori' => ['required', Rule::in(array_keys(JenisHukuman::categories()))],
        ];
    }

    private function messages(): array
    {
        return [
            'kode_hukuman.required' => 'Kode hukuman wajib diisi.',
            'kode_hukuman.string' => 'Kode hukuman harus berupa teks.',
            'kode_hukuman.max' => 'Kode hukuman maksimal 50 karakter.',
            'kode_hukuman.unique' => 'Kode hukuman sudah digunakan.',
            'nama_hukuman.required' => 'Nama hukuman wajib diisi.',
            'nama_hukuman.string' => 'Nama hukuman harus berupa teks.',
            'nama_hukuman.max' => 'Nama hukuman maksimal 255 karakter.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
