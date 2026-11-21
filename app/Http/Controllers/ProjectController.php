<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Halaman Beranda / Pencarian Proyek
     */
    public function index(Request $request)
    {
        $search = $request->query('q');

        $projects = Project::with(['client', 'bids'])
            ->withCount('bids')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', compact('projects', 'search'));
    }

    /**
     * Halaman Form Pasang Proyek (Klien)
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Simpan Proyek Baru (Eloquent ORM)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:5000'],
            'budget_min' => ['required', 'integer', 'min:10000'],
            'budget_max' => ['required', 'integer', 'gte:budget_min'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
        ], [
            'title.required' => 'Judul proyek wajib diisi.',
            'category.required' => 'Kategori proyek wajib diisi.',
            'description.required' => 'Deskripsi proyek wajib diisi.',
            'budget_min.required' => 'Budget minimum wajib diisi.',
            'budget_max.required' => 'Budget maksimum wajib diisi.',
            'budget_max.gte' => 'Budget maksimum tidak boleh lebih kecil dari budget minimum.',
            'deadline.required' => 'Batas lelang wajib diisi.',
            'deadline.after_or_equal' => 'Batas lelang tidak boleh tanggal yang sudah lewat.',
        ]);

        // Simpan menggunakan relasi Eloquent
        Auth::user()->projects()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Proyek berhasil dipasang!');
    }

    /**
     * Detail Proyek & Daftar Penawaran
     */
    public function show(Project $project)
    {
        // Load relasi client dan bids beserta developer-nya
        $project->load([
            'client',
            'bids' => function ($query) {
                // Urutkan pemenang di atas, lalu harga terendah
                $query->orderByDesc('is_winner')->orderBy('price', 'asc')->with('developer');
            }
        ]);

        $userBid = null;
        if (Auth::check() && Auth::user()->isDeveloper()) {
            $userBid = $project->bids->firstWhere('developer_id', Auth::id());
        }

        return view('projects.show', compact('project', 'userBid'));
    }
}
