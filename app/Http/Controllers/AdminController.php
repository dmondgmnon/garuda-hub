<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Halaman Utama Panel Admin
     */
    public function index()
    {
        $users = User::latest()->paginate(15, ['*'], 'users_page');
        $projects = Project::with(['client'])->withCount('bids')->latest()->paginate(15, ['*'], 'projects_page');

        return view('admin.index', compact('users', 'projects'));
    }

    /**
     * Mengubah peran (role) pengguna
     */
    public function updateRole(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        $validated = $request->validate([
            'role' => ['required', 'in:klien,developer,admin'],
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Peran pengguna {$user->name} berhasil diubah menjadi {$user->role}.");
    }

    /**
     * Menghapus pengguna (cascade ke data terkait via DB FK)
     */
    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Pengguna {$userName} beserta seluruh data terkait berhasil dihapus.");
    }

    /**
     * Menghapus proyek
     */
    public function deleteProject(Project $project)
    {
        $title = $project->title;
        $project->delete();

        return back()->with('success', "Proyek '{$title}' berhasil dihapus.");
    }
}
