<?php


namespace Jhumanj\LaravelModelStats\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    // use HasFactory;

    protected $table = 'model-stats-dashboards';

    protected $fillable = [
        'name',
        'description',
        'body',
    ];

    protected $casts = [
        'body' => 'array',
    ];
}
