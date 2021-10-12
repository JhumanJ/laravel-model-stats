<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create(Config::get('model-stats.table_name'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            if (Config::get('database.defaults') === 'pgsql') {
                $table->jsonb('body')->default('{"widgets":[]}');
            } else {
                $table->json('body');
            }
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Config::get('model-stats.table_name'));
    }
};
