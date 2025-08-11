@extends('layouts.dashboard')
@section('title', 'Jadwal & Penugasan Mengajar')
@section('content')
<div class="space-y-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        Jadwal & Penugasan Mengajar
                    </h1>
                    <div class="mt-2 text-gray-600">
                        <span class="font-medium">Tahun Ajaran:</span>
                        <span class="text-blue-600 font-semibold">{{ $activeSemester->academicYear->year ?? '-' }}</span>
                        <span class="mx-2">•</span>
                        <span class="font-medium">Semester:</span>
                        <span class="text-blue-600 font-semibold">{{ $activeSemester->name ?? '-' }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="#" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 0v4m0 0h4m-4 0H8" />
                        </svg>
                        Cetak Jadwal
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Conflict Warning -->
        @if(!empty($conflicts))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 mb-2">⚠️ Konflik Jadwal Ditemukan</h3>
                    <div class="text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($conflicts as $conflict)
                            <li>
                                <strong>{{ $conflict['teacher'] }}</strong> pada hari <strong>{{ $conflict['day'] }}</strong>:<br>
                                <span class="ml-4">• {{ $conflict['conflict1'] }}</span><br>
                                <span class="ml-4">• {{ $conflict['conflict2'] }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Class Selection -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3m10 0h3a1 1 0 001-1V7a1 1 0 00-1-1h-3m-10 0H4a1 1 0 00-1 1z" />
                            </svg>
                            Pilih Kelas:
                        </label>
                        <select name="assignment_id" onchange="this.form.submit()"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-[200px]">
                            @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}" {{ $selectedAssignment == $assignment->id ? 'selected' : '' }}>
                                {{ $assignment->classroom->name }} ({{ $assignment->academicYear->year ?? '' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedAssignment)
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Kelas terpilih:</span>
                        <span class="text-blue-600 font-semibold">
                            {{ $assignments->firstWhere('id', $selectedAssignment)->classroom->name ?? '' }}
                        </span>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Time Slot Legend -->
        <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi Jam Pelajaran
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                @php
                $timeSlots = config('siakad.time_slots');
                $breakTimes = config('siakad.break_times');
                @endphp
                @foreach($timeSlots as $slotNumber => $slot)
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="font-semibold text-blue-600 text-sm">{{ $slot['name'] }}</div>
                    <div class="text-gray-600 text-xs">{{ $slot['start'] }} - {{ $slot['end'] }}</div>
                </div>
                @endforeach
            </div>

            <!-- Break Times -->
            <div class="border-t border-gray-200 pt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Waktu Istirahat
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($breakTimes as $breakKey => $break)
                    <div class="bg-orange-50 p-3 rounded-lg border border-orange-200">
                        <div class="font-semibold text-orange-600 text-sm">{{ $break['name'] }}</div>
                        <div class="text-orange-600 text-xs">{{ $break['start'] }} - {{ $break['end'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10 border-r border-gray-200">
                                Hari/Jam
                            </th>
                            @for($i=1; $i<=10; $i++)
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex flex-col items-center">
                                    <span class="font-semibold text-blue-600">{{ $timeSlots[$i]['name'] }}</span>
                                    <span class="text-gray-400 text-xs">{{ $timeSlots[$i]['start'] }}-{{ $timeSlots[$i]['end'] }}</span>
                                </div>
                                </th>
                                @endfor
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                        $days = config('siakad.school_days');
                        $hasAny = false;
                        @endphp
                        @foreach($days as $day)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    {{ $day }}
                                </div>
                            </td>
                            @for($i=1; $i<=10; $i++)
                                <td class="px-3 py-4 text-center">
                                @php
                                $currentTimeSlot = $timeSlots[$i];
                                $slot = $schedules->first(function($s) use ($day, $currentTimeSlot) {
                                // Normalize time format to handle HH:MM vs HH:MM:SS
                                $dbTimeStart = substr($s->time_start, 0, 5); // Get HH:MM from HH:MM:SS
                                $dbTimeEnd = substr($s->time_end, 0, 5); // Get HH:MM from HH:MM:SS
                                return $s->day == $day && $dbTimeStart == $currentTimeSlot['start'] && $dbTimeEnd == $currentTimeSlot['end'];
                                });
                                @endphp
                                @if($slot)
                                @php $hasAny = true; @endphp
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-3 shadow-sm">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $slot->subject->name }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-600 text-center">
                                            <div class="flex items-center justify-center gap-1 mb-1">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804" />
                                                </svg>
                                                {{ $slot->teacher->full_name }}
                                            </div>
                                            <div class="text-gray-500 text-xs">
                                                {{ substr($slot->time_start, 0, 5) }} - {{ substr($slot->time_end, 0, 5) }}
                                            </div>
                                        </div>
                                        <div class="flex gap-1 justify-center pt-2 border-t border-blue-200">
                                            <a href="{{ route('jadwal.admin.edit', $slot) }}"
                                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 10-4-4l-8 8v3z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('jadwal.admin.destroy', $slot) }}" method="POST" onsubmit="return confirm('Hapus slot ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <a href="{{ route('jadwal.admin.create', ['assignment_id' => $selectedAssignment, 'day' => $day, 'jam' => $i]) }}"
                                    class="inline-flex items-center justify-center w-full h-full min-h-[80px] bg-gradient-to-br from-green-50 to-green-100 border-2 border-dashed border-green-300 rounded-lg text-green-700 hover:from-green-100 hover:to-green-200 hover:border-green-400 transition-all duration-200 group">
                                    <div class="text-center">
                                        <svg class="w-6 h-6 mx-auto mb-1 text-green-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span class="text-xs font-medium">Tambah</span>
                                    </div>
                                </a>
                                @endif
                                </td>
                                @endfor
                        </tr>
                        @endforeach
                        @if(!$hasAny)
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-500 mb-2">Belum ada jadwal</h3>
                                    <p class="text-sm text-gray-400">Silakan tambahkan jadwal dengan mengklik tombol hijau di atas.</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection