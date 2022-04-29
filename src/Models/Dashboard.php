<?php

namespace Jhumanj\LaravelModelStats\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Jhumanj\LaravelModelStats\Database\Factories\DashboardFactory;

class Dashboard extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('model-stats.table_name');
        parent::__construct($attributes);
    }

    protected $fillable = [
        'name',
        'description',
        'body',
    ];

    protected $casts = [
        'body' => 'json',
    ];

    protected $attributes = [
        'body' => '{"widgets":[]}',
    ];

    protected static function newFactory(): DashboardFactory
    {
        return DashboardFactory::new();
    }
}
