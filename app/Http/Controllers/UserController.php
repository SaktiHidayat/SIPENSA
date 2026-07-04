<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(): View
    {
        $search = request('search');

        return view('users.index', [
            'users' => $this->userService->paginate(search: $search),
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
                ->with('modal', 'create-user');
        }

        $this->userService->create($validator->validated());

        return to_route('users.index')->with('success', 'Data user berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules($user), $this->messages($user));

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', 'edit-user-'.$user->id);
        }

        $this->userService->update($user, $validator->validated());

        return to_route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userService->delete($user);

        return to_route('users.index')->with('success', 'Data user berhasil dihapus.');
    }

    private function rules(?User $user = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'nip' => ['required', 'regex:/^\d{18}$/'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'role' => ['required', Rule::in(['admin', 'guru_bk'])],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }

    private function messages(?User $user = null): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username maksimal 100 karakter.',
            'username.unique' => 'Username ini sudah digunakan. Silakan gunakan username lain.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.regex' => 'NIP harus tepat 18 digit angka, tidak boleh kurang atau lebih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => $user
                ? 'Password baru minimal 8 karakter.'
                : 'Password minimal 8 karakter.',
        ];
    }
}
