@extends('layouts.dashboard')
@section('title', 'Manajemen Pengguna')
@section('content')
<h2 class="text-2xl font-bold mb-4">Manajemen Pengguna</h2>
<p>Halaman ini untuk mengelola akun login pengguna sistem.</p>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
@endif
<div class="overflow-x-auto bg-white rounded shadow mt-4">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4 text-left">#</th>
                <th class="py-2 px-4 text-left">Nama</th>
                <th class="py-2 px-4 text-left">Email</th>
                <th class="py-2 px-4 text-left">Role</th>
                <th class="py-2 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-b hover:bg-blue-50">
                <td class="py-2 px-4">{{ $loop->iteration }}</td>
                <td class="py-2 px-4">{{ $user->name }}</td>
                <td class="py-2 px-4">{{ $user->email }}</td>
                <td class="py-2 px-4 capitalize">{{ $user->role }}</td>
                <td class="py-2 px-4">
                    <form method="POST" action="{{ route('admin.user.reset-password', $user->id) }}" onsubmit="return confirm('Reset password user ini ke password123?');">
                        @csrf
                        <button type="submit" class="px-2 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Reset Password</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection