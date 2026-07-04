<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Services\SiswaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function __construct(private readonly SiswaService $siswaService)
    {
    }

    public function index(): View
    {
        $search = request('search');

        return view('siswas.index', [
            'siswas' => $this->siswaService->paginate(search: $search),
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
                ->with('modal', 'create-siswa');
        }

        $this->siswaService->create($validator->validated());

        return to_route('siswas.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules($siswa), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'edit-siswa-'.$siswa->id);
        }

        $this->siswaService->update($siswa, $validator->validated());

        return to_route('siswas.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $this->siswaService->delete($siswa);

        return to_route('siswas.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    private function rules(?Siswa $siswa = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'nisn' => [
                'required',
                'regex:/^\d{10}$/',
                Rule::unique('siswas', 'nisn')->ignore($siswa?->id),
            ],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'kelas' => ['required', 'string', 'max:50', 'regex:/^(VII|VIII|IX)(-[IVXLCDM0-9A-Z]+)?$/'],
        ];
    }

    private function messages(): array
    {
        return [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nama.string' => 'Nama siswa harus berupa teks.',
            'nama.max' => 'Nama siswa maksimal 255 karakter.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.regex' => 'NISN wajib terdiri dari tepat 10 digit angka.',
            'nisn.unique' => 'NISN ini sudah terdaftar. Silakan gunakan NISN lain.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',
            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.string' => 'Kelas harus berupa teks.',
            'kelas.max' => 'Kelas maksimal 50 karakter.',
            'kelas.regex' => 'Kelas wajib diawali angka romawi VII, VIII, atau IX. Contoh: VII-I, VIII-II, atau IX-III.',
        ];
    }
}
