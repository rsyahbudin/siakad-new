@extends('layouts.dashboard')
@section('title', 'Impor Nilai Siswa')
@section('content')
<div class="container mx-auto max-w-4xl py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Impor Nilai Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Unggah file Excel untuk mengimpor nilai tugas, UTS, dan UAS.</p>
        </div>
        <a href="{{ route('nilai.input') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    @if (session('success'))
    <div class="mb-4 rounded-md bg-green-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif
    @if (session('error'))
    <div class="mb-4 rounded-md bg-red-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif
    @if ($errors->any())
    <div class="mb-4 rounded-md bg-red-50 p-4">
        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan validasi:</h3>
        <div class="mt-2 text-sm text-red-700">
            <ul role="list" class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <form action="{{ route('nilai.import.show') }}" method="GET" class="mb-6">
            <label for="assignment_id_filter" class="block text-sm font-medium text-gray-700">1. Pilih Kelas</label>
            <div class="mt-2 flex items-center gap-3">
                <select name="assignment_id" id="assignment_id_filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($assignments as $assignment)
                    <option value="{{ $assignment->id }}" {{ $selectedAssignment == $assignment->id ? 'selected' : '' }}>
                        {{ $assignment->classroom->name }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Pilih
                </button>
            </div>
        </form>

        @if($selectedAssignment)
        <div class="mt-6 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">2. Unggah File Nilai</h3>
            <form action="{{ route('nilai.import.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-6">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $selectedAssignment }}">

                <div>
                    <label for="subject_id_upload" class="block text-sm font-medium text-gray-700">Pilih Mata Pelajaran</label>
                    <select name="subject_id" id="subject_id_upload" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">File Excel</label>
                    <div class="mt-2">
                        <input type="file" name="file" id="file" required class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                            ">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">File harus berformat .xlsx atau .xls.</p>
                </div>

                <div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Impor Nilai
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">3. Unduh Template</h3>
                <p class="mt-1 text-sm text-gray-600">Gunakan form di bawah ini untuk mengunduh template yang sudah terisi nilai siswa yang ada.</p>
                <form action="{{ route('nilai.import.template') }}" method="GET" class="mt-4">
                    <input type="hidden" name="assignment_id" value="{{ $selectedAssignment }}">
                    <div class="space-y-4">
                        <div>
                            <label for="subject_id_download" class="block text-sm font-medium text-gray-700">Pilih Mata Pelajaran untuk Template</label>
                            <select name="subject_id" id="subject_id_download" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh Template
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        @else
        <div class="text-center py-12 text-gray-500 border-t border-gray-200 mt-6 pt-6">
            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17.25v.001M9 13.5v.001M9 9.75v.001M12 9.75v.001M15 13.5v.001M15 17.25v.001M12 21.75a9.75 9.75 0 110-19.5 9.75 9.75 0 010 19.5z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Mulai dengan memilih kelas</h3>
            <p class="mt-1 text-sm text-gray-500">Form untuk unggah file dan unduh template akan muncul di sini setelah Anda memilih kelas.</p>
        </div>
        @endif
    </div>
</div>
@endsection