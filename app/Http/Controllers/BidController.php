<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    /**
     * Mengajukan penawaran lelang (Developer)
     */
    public function store(Request $request, Project $project)
    {
        // 1. Cek apakah status proyek masih terbuka
        if (!$project->isOpen()) {
            return back()->with('error', 'Lelang untuk proyek ini sudah ditutup.');
        }

        // 2. Cek apakah developer sudah pernah menawar
        $alreadyBid = Bid::where('project_id', $project->id)
            ->where('developer_id', Auth::id())
            ->exists();

        if ($alreadyBid) {
            return back()->with('error', 'Anda sudah mengajukan penawaran pada proyek ini.');
        }

        // 3. Validasi input
        $validated = $request->validate([
            'price' => ['required', 'integer', 'min:10000'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'proposal' => ['required', 'string', 'max:5000'],
        ], [
            'price.required' => 'Harga penawaran wajib diisi.',
            'price.min' => 'Harga penawaran minimal Rp 10.000.',
            'duration_days.required' => 'Durasi pengerjaan wajib diisi.',
            'duration_days.min' => 'Durasi minimal 1 hari.',
            'proposal.required' => 'Proposal penawaran wajib diisi.',
        ]);

        // 4. Simpan via Eloquent
        $project->bids()->create([
            'developer_id' => Auth::id(),
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'proposal' => $validated['proposal'],
        ]);

        return back()->with('success', 'Penawaran Anda berhasil diajukan!');
    }

    /**
     * Memilih pemenang lelang (Klien pemilik proyek)
     */
    public function pickWinner(Project $project, Bid $bid)
    {
        // 1. Otorisasi kepemilikan proyek
        if ($project->client_id !== Auth::id()) {
            abort(403, 'Anda bukan pemilik proyek ini.');
        }

        // 2. Cek apakah penawaran memang milik proyek ini
        if ($bid->project_id !== $project->id) {
            abort(404, 'Penawaran tidak ditemukan pada proyek ini.');
        }

        // 3. Cek status proyek
        if ($project->status === 'ditutup') {
            return back()->with('error', 'Pemenang lelang sudah pernah dipilih sebelumnya.');
        }

        // 4. Update atomic dengan Transaction
        DB::transaction(function () use ($project, $bid) {
            // Tandai bid sebagai pemenang
            $bid->update(['is_winner' => true]);

            // Ubah status proyek menjadi ditutup
            $project->update(['status' => 'ditutup']);
        });

        return back()->with('success', 'Selamat! Pemenang lelang berhasil ditentukan.');
    }
}
