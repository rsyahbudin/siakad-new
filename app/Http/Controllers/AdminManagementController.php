<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.admin-management.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admin-management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.management.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('admin.admin-management.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('admin.admin-management.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($admin->id),
            ],
            'phone' => 'nullable|string|max:20',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.management.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        // Prevent deleting the current user
        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.management.index')
                ->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        $admin->delete();

        return redirect()->route('admin.management.index')
            ->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * Show the form for changing admin password
     */
    public function changePassword(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('admin.admin-management.change-password', compact('admin'));
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $request->validate([
            'current_password' => 'required_without:is_reset',
            'new_password' => 'required|string|min:8|confirmed',
            'is_reset' => 'boolean',
        ]);

        // If it's a password reset (by another admin), skip current password check
        if (!$request->is_reset) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
        }

        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        $message = $request->is_reset ? 'Password admin berhasil direset.' : 'Password berhasil diubah.';

        return redirect()->route('admin.management.index')
            ->with('success', $message);
    }

    /**
     * Show admin profile
     */
    public function profile()
    {
        $admin = auth()->user();
        return view('admin.admin-management.profile', compact('admin'));
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($admin->id),
            ],
            'phone' => 'nullable|string|max:20',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.management.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Show form to change own password
     */
    public function changeOwnPassword()
    {
        return view('admin.admin-management.change-own-password');
    }

    /**
     * Update own password
     */
    public function updateOwnPassword(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('admin.management.profile')
            ->with('success', 'Password berhasil diubah.');
    }
}
