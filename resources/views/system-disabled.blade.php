<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Tidak Tersedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">Sistem {{ $system }} Tidak Tersedia</h1>

            <p class="text-gray-600 mb-6">
                Sistem {{ $system }} saat ini sedang dinonaktifkan oleh administrator.
                Silakan coba lagi nanti atau hubungi pihak sekolah untuk informasi lebih lanjut.
            </p>

            <div class="space-y-3">
                <a href="{{ url('/') }}"
                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Kembali ke Beranda
                </a>

                <a href="{{ url('/login') }}"
                    class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Login Admin
                </a>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Jika Anda adalah administrator, silakan login untuk mengaktifkan sistem ini kembali.
                </p>
            </div>
        </div>
    </div>
</body>

</html>