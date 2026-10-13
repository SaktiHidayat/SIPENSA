<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'nip' => ['required', 'regex:/^\d{18}$/', 'unique:users,nip'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'role' => ['required', Rule::in(['guru_bk', 'wali_kelas'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->userService->create($validated);

        return to_route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}