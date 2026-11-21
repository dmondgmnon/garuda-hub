<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dasbor sesuai Peran User
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isClient()) {
            // Proyek Saya
            $projects = $user->projects()
                ->withCount('bids')
                ->latest()
                ->paginate(10);

            return view('dashboard.client', compact('projects'));
        }

        if ($user->isDeveloper()) {
            // Penawaran Saya
            $bids = $user->bids()
                ->with(['project.client'])
                ->latest()
                ->paginate(10);

            return view('dashboard.developer', compact('bids'));
        }

        abort(403);
    }
}
