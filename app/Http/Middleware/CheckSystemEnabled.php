<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $system): Response
    {
        $enabled = false;

        switch ($system) {
            case 'ppdb':
                $enabled = SystemSetting::isPPDBEnabled();
                break;
            case 'transfer':
                $enabled = SystemSetting::isTransferStudentEnabled();
                break;
            default:
                $enabled = false;
        }

        if (!$enabled) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sistem sedang tidak tersedia. Silakan coba lagi nanti.'
                ], 503);
            }

            return response()->view('system-disabled', [
                'system' => $system === 'ppdb' ? 'PPDB' : 'Siswa Pindahan'
            ], 503);
        }

        return $next($request);
    }
}
