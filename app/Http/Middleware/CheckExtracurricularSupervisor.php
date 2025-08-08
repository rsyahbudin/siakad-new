<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Extracurricular;
use Symfony\Component\HttpFoundation\Response;

class CheckExtracurricularSupervisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'teacher') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses sebagai guru.');
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Profil guru tidak ditemukan.');
        }

        // For routes that require extracurricular parameter
        if ($request->route('extracurricular')) {
            $extracurricular = $request->route('extracurricular');

            // Check if teacher is the supervisor of this extracurricular
            if ($extracurricular->teacher_id !== $teacher->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke ekstrakurikuler ini. Hanya pembina ekstrakurikuler yang dapat mengakses.');
            }
        }

        return $next($request);
    }
}
