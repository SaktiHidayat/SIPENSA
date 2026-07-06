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
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

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

    private function rules(): array
    {
        return [
            'kategori' => ['required', Rule::in(array_keys(JenisHukuman::categories()))],
        ];
    }

    private function messages(): array
    {
        return [
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
