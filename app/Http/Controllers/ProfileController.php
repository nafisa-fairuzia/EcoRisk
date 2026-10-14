<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $matches = [];

        if ($user->isWarga()) {
            preg_match('/^RT\s*(.*?)\s*\/\s*RW\s*(.*?)$/i', $user->zona_wilayah ?? '', $matches);
        }

        return view('profile.edit', [
            'user' => $user,
            'isWarga' => $user->isWarga(),
            'rt' => trim($matches[1] ?? ''),
            'rw' => trim($matches[2] ?? ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'telepon' => ['nullable', 'string', 'max:30'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'current_password' => ['required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ];

        if ($user->isWarga()) {
            $rules['alamat'] = ['required', 'string', 'max:255'];
            $rules['rt'] = ['required', 'string', 'max:5'];
            $rules['rw'] = ['required', 'string', 'max:5'];
        } else {
            $rules['alamat'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        unset($validated['current_password'], $validated['password_confirmation']);

        if ($user->isWarga()) {
            $validated['zona_wilayah'] = "RT {$validated['rt']} / RW {$validated['rw']}";
            unset($validated['rt'], $validated['rw']);
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        unset($validated['foto_profil']);

        $oldPhotoPath = $user->profile_photo_path;
        if ($request->hasFile('foto_profil')) {
            $validated['profile_photo_path'] = $request->file('foto_profil')->store('profile-photos', 'public');
        }

        $user->fill($validated)->save();

        if ($request->hasFile('foto_profil') && $oldPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
