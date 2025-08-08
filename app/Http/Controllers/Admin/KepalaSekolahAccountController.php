<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KepalaSekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KepalaSekolahAccountController extends Controller
{
    public function index()
    {
        $kepala = User::where('role', User::ROLE_KEPALA_SEKOLAH)->with('kepalaSekolah')->first();
        return view('admin.kepala-sekolah.index', compact('kepala'));
    }

    public function create()
    {
        $exists = User::where('role', User::ROLE_KEPALA_SEKOLAH)->exists();
        if ($exists) {
            return redirect()->route('admin.kepsek.index')->with('error', 'Hanya boleh ada 1 Kepala Sekolah.');
        }
        return view('admin.kepala-sekolah.create');
    }

    public function store(Request $request)
    {
        if (User::where('role', User::ROLE_KEPALA_SEKOLAH)->exists()) {
            return redirect()->route('admin.kepsek.index')->with('error', 'Hanya boleh ada 1 Kepala Sekolah.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'required|string|size:18|unique:kepala_sekolahs,nip',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'last_education' => 'nullable|string|max:100',
            'degree' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:150',
            'university' => 'nullable|string|max:150',
            'graduation_year' => 'nullable|integer|min:1950|max:2100',
            'birth_place' => 'nullable|string|max:150',
            'birth_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_KEPALA_SEKOLAH,
        ]);

        KepalaSekolah::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'],
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'position' => 'Kepala Sekolah',
            'last_education' => $validated['last_education'] ?? null,
            'degree' => $validated['degree'] ?? null,
            'major' => $validated['major'] ?? null,
            'university' => $validated['university'] ?? null,
            'graduation_year' => $validated['graduation_year'] ?? null,
            'birth_place' => $validated['birth_place'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
        ]);

        return redirect()->route('admin.kepsek.index')->with('success', 'Akun Kepala Sekolah berhasil dibuat.');
    }

    public function edit(User $user)
    {
        abort_unless($user->role === User::ROLE_KEPALA_SEKOLAH, 404);
        $kepala = $user->load('kepalaSekolah');
        return view('admin.kepala-sekolah.edit', compact('kepala'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->role === User::ROLE_KEPALA_SEKOLAH, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'nip' => 'required|string|size:18|unique:kepala_sekolahs,nip,' . ($user->kepalaSekolah->id ?? 'null'),
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'last_education' => 'nullable|string|max:100',
            'degree' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:150',
            'university' => 'nullable|string|max:150',
            'graduation_year' => 'nullable|integer|min:1950|max:2100',
            'birth_place' => 'nullable|string|max:150',
            'birth_date' => 'nullable|date',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $profile = $user->kepalaSekolah;
        if (!$profile) {
            $profile = new KepalaSekolah(['user_id' => $user->id]);
        }
        $profile->nip = $validated['nip'];
        $profile->full_name = $validated['full_name'];
        $profile->phone_number = $validated['phone_number'] ?? null;
        $profile->address = $validated['address'] ?? null;
        $profile->position = 'Kepala Sekolah';
        $profile->last_education = $validated['last_education'] ?? $profile->last_education;
        $profile->degree = $validated['degree'] ?? $profile->degree;
        $profile->major = $validated['major'] ?? $profile->major;
        $profile->university = $validated['university'] ?? $profile->university;
        $profile->graduation_year = $validated['graduation_year'] ?? $profile->graduation_year;
        $profile->birth_place = $validated['birth_place'] ?? $profile->birth_place;
        $profile->birth_date = $validated['birth_date'] ?? $profile->birth_date;
        $profile->save();

        return redirect()->route('admin.kepsek.index')->with('success', 'Data Kepala Sekolah berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === User::ROLE_KEPALA_SEKOLAH, 404);
        $user->delete();
        return redirect()->route('admin.kepsek.index')->with('success', 'Akun Kepala Sekolah dihapus.');
    }
}
