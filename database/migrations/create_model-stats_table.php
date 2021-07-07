<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('model-stats-dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            if (config('database.default') == 'mysql') {
                $table->json('body');
            } else {
                $table->jsonb('body')->default('{"widgets":[]}');
            }
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('model-stats-dashboards');
    }
};
