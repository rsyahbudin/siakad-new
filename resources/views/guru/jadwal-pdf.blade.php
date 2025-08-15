<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Mengajar - {{ $teacher->full_name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin: 0 0 10px 0;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }

        .info-grid {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .info-item {
            flex: 1;
            min-width: 200px;
            margin: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            font-size: 11px;
        }

        .info-value {
            color: #1f2937;
            font-size: 12px;
            margin-top: 2px;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }

        .schedule-table th {
            background-color: #2563eb;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #1d4ed8;
        }

        .schedule-table td {
            padding: 6px;
            border: 1px solid #d1d5db;
            text-align: center;
            vertical-align: top;
        }

        .schedule-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .schedule-item {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 4px;
            padding: 4px;
            margin: 2px 0;
            font-size: 9px;
        }

        .subject-name {
            font-weight: bold;
            color: #1e40af;
        }

        .classroom-name {
            color: #374151;
            font-size: 8px;
        }

        .time-slot {
            color: #6b7280;
            font-size: 8px;
        }

        .day-header {
            background-color: #1e40af;
            color: white;
            font-weight: bold;
            padding: 8px;
            text-align: center;
        }

        .statistics {
            margin-top: 30px;
            padding: 15px;
            background-color: #f0f9ff;
            border-radius: 8px;
            border: 1px solid #bae6fd;
        }

        .statistics h3 {
            color: #0369a1;
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        .stats-grid {
            display: flex;
            justify-content: space-around;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #0369a1;
        }

        .stat-label {
            font-size: 10px;
            color: #64748b;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }

        .empty-cell {
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>JADWAL MENGAJAR</h1>
        <p><strong>{{ $teacher->full_name }}</strong></p>
        <p>Tahun Ajaran: {{ $activeYear->year ?? '-' }} | Semester: {{ $activeSemester->name ?? '-' }}</p>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Information Section -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nama Guru:</div>
                <div class="info-value">{{ $teacher->full_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">NIP:</div>
                <div class="info-value">{{ $teacher->nip ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tahun Ajaran:</div>
                <div class="info-value">{{ $activeYear->year ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Semester:</div>
                <div class="info-value">{{ $activeSemester->name ?? '-' }}</div>
            </div>
        </div>
    </div>



    <!-- Schedule Table -->
    @if($schedules && $schedules->count() > 0)
    <table class="schedule-table">
        <thead>
            <tr>
                <th style="width: 15%;">Hari</th>
                <th style="width: 17%;">Senin</th>
                <th style="width: 17%;">Selasa</th>
                <th style="width: 17%;">Rabu</th>
                <th style="width: 17%;">Kamis</th>
                <th style="width: 17%;">Jumat</th>
            </tr>
        </thead>
        <tbody>
            @php
            // Get time slots from config
            $timeSlots = config('siakad.time_slots');
            $breakTimes = config('siakad.break_times');

            // Create time slots array with proper format
            $timeSlotsArray = [];
            foreach ($timeSlots as $slot) {
            $timeSlotsArray[$slot['start']] = $slot['start'] . '-' . $slot['end'];
            }

            // Add break times
            foreach ($breakTimes as $break) {
            $timeSlotsArray[$break['start']] = $break['start'] . '-' . $break['end'] . ' (' . $break['name'] . ')';
            }

            // Sort by time
            ksort($timeSlotsArray);

            $days = config('siakad.school_days');
            @endphp

            @foreach($timeSlotsArray as $startTime => $timeRange)
            <tr>
                <td style="background-color: #f3f4f6; font-weight: bold; text-align: center;">
                    {{ $timeRange }}
                </td>
                @foreach($days as $day)
                <td>
                    @php
                    // Convert time format to match database format (HH:MM:SS)
                    $startTimeFormatted = $startTime . ':00';
                    $daySchedules = $schedules->where('day', $day)->where('time_start', $startTimeFormatted);

                    // Check if this is a break time
                    $isBreakTime = false;
                    foreach ($breakTimes as $break) {
                    if ($break['start'] === $startTime) {
                    $isBreakTime = true;
                    break;
                    }
                    }
                    @endphp

                    @if($isBreakTime)
                    <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 4px; padding: 4px; margin: 2px 0; font-size: 9px; color: #92400e; text-align: center; font-weight: bold;">
                        {{ $timeRange }}
                    </div>
                    @elseif($daySchedules->count() > 0)
                    @foreach($daySchedules as $schedule)
                    <div class="schedule-item">
                        <div class="subject-name">{{ $schedule->subject->name ?? $schedule->subject->code ?? 'Mata Pelajaran' }}</div>
                        <div class="classroom-name">{{ $schedule->classroom->name ?? 'Kelas' }}</div>
                        <div class="time-slot">{{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') ?? '00:00' }} - {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') ?? '00:00' }}</div>
                    </div>
                    @endforeach
                    @else
                    <div class="empty-cell">-</div>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #6b7280;">
        <p>Tidak ada jadwal mengajar yang ditemukan.</p>
    </div>
    @endif

    <!-- Statistics -->
    <div class="statistics">
        <h3>Ringkasan Jadwal Mengajar</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $totalSchedules }}</div>
                <div class="stat-label">Total Jadwal</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalClassrooms }}</div>
                <div class="stat-label">Kelas Diajar</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalSubjects }}</div>
                <div class="stat-label">Mata Pelajaran</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem SIAKAD</p>
        <p>© {{ date('Y') }} Sistem Informasi Akademik - Semua hak cipta dilindungi</p>
    </div>
</body>

</html>