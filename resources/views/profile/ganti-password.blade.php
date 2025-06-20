@extends('layouts.dashboard')
@section('title', 'Ganti Password')
@section('content')
<h2 class="text-2xl font-bold mb-4">Ganti Password</h2>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
    <ul class="list-disc pl-5">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form method="POST" action="{{ route('profil.ganti-password.update') }}" class="max-w-md space-y-4">
    @csrf
    <div>
        <label class="block font-semibold mb-1">Password Lama</label>
        <input type="password" name="old_password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
    </div>
    <div>
        <label class="block font-semibold mb-1">Password Baru</label>
        <input type="password" name="new_password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
    </div>
    <div>
        <label class="block font-semibold mb-1">Konfirmasi Password Baru</label>
        <input type="password" name="new_password_confirmation" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
</form>
@endsection