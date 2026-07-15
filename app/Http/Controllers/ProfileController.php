<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function photo(Request $request)
    {
        $user = $request->user();

        if (! $user->foto || ! Storage::disk('public')->exists($user->foto)) {
            abort(404);
        }

        return Storage::disk('public')->response($user->foto);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->safe()->except('foto');

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $request->file('foto')->store('profile-photos', 'public');
        }

        $user->update($data);

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

}
