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

        $this->jenisHukumanService->create($this->normalizeValidatedData($validator->validated()));

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

        $this->jenisHukumanService->update($hukuman, $this->normalizeValidatedData($validator->validated()));

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
            'rentang_poin_awal' => ['required', 'numeric', 'min:0.1'],
            'rentang_poin_akhir' => [
                'required',
                'numeric',
                'min:0.1',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ((float) $value < (float) request('rentang_poin_awal')) {
                        $fail('Rentang kedua harus lebih besar atau sama dengan rentang pertama.');
                    }
                },
            ],
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
            'rentang_poin_awal.required' => 'Rentang pertama wajib diisi.',
            'rentang_poin_awal.numeric' => 'Rentang pertama harus berupa angka.',
            'rentang_poin_awal.min' => 'Rentang pertama minimal 0.1.',
            'rentang_poin_akhir.required' => 'Rentang kedua wajib diisi.',
            'rentang_poin_akhir.numeric' => 'Rentang kedua harus berupa angka.',
            'rentang_poin_akhir.min' => 'Rentang kedua minimal 0.1.',
        ];
    }

    private function normalizeValidatedData(array $data): array
    {
        $data['rentang_poin'] = trim((string) $data['rentang_poin_awal']).' - '.trim((string) $data['rentang_poin_akhir']);

        return $data;
    }
}
