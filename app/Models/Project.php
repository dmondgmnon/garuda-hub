<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'category',
        'budget_min',
        'budget_max',
        'deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'budget_min' => 'integer',
            'budget_max' => 'integer',
        ];
    }

    /**
     * Relasi ke User (klien pemilik proyek).
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relasi ke semua penawaran (bids).
     */
    public function bids()
    {
        return $this->hasMany(Bid::class, 'project_id');
    }

    /**
     * Mendapatkan penawaran pemenang (jika ada).
     */
    public function winningBid()
    {
        return $this->hasOne(Bid::class, 'project_id')->where('is_winner', true);
    }

    /**
     * Cek apakah proyek masih terbuka untuk penawaran.
     */
    public function isOpen(): bool
    {
        return $this->status === 'terbuka' && $this->deadline >= now()->toDateString();
    }
}
