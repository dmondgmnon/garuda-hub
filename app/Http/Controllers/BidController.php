<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function store(Request $request, Project $project)
    {
        if (!$project->isOpen()) {
            return back()->with('error', 'Lelang sudah ditutup; penawaran tidak dapat diajukan.');
        }

        $sudahMenawar = Bid::where('project_id', $project->id)
            ->where('developer_id', Auth::id())
            ->exists();

        if ($sudahMenawar) {
            return back()->with('error', 'Anda sudah mengajukan penawaran pada proyek ini.');
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:10000',
            'duration_days' => 'required|integer|min:1|max:365',
            'proposal' => 'required|string',
        ], [
            'price.required' => 'Harga penawaran wajib diisi.',
            'price.min' => 'Harga penawaran minimal Rp 10.000.',
            'duration_days.required' => 'Durasi pengerjaan wajib diisi.',
            'proposal.required' => 'Proposal penawaran wajib diisi.',
        ]);

        $project->bids()->create([
            'developer_id' => Auth::id(),
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'proposal' => $validated['proposal'],
        ]);

        return back()->with('success', 'Penawaran berhasil dikirim.');
    }

    public function pickWinner(Project $project, Bid $bid)
    {
        if ($project->client_id !== Auth::id()) {
            abort(403, 'Anda bukan pemilik proyek ini.');
        }

        if ($bid->project_id !== $project->id) {
            abort(404);
        }

        if ($project->status === 'ditutup') {
            return back()->with('error', 'Lelang ini sudah ditutup.');
        }

        $bid->update(['is_winner' => true]);
        $project->update(['status' => 'ditutup']);

        return back()->with('success', 'Pemenang lelang berhasil dipilih.');
    }
}
