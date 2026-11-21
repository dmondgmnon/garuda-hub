<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'developer_id',
        'price',
        'duration_days',
        'proposal',
        'is_winner',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'duration_days' => 'integer',
            'is_winner' => 'boolean',
        ];
    }

    /**
     * Relasi ke Proyek yang ditawar.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relasi ke Developer penawar.
     */
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}
