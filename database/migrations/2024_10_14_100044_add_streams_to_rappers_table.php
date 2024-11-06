<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rappers', function (Blueprint $table) {
            $table->unsignedBigInteger('streams_this_year')->nullable()->after('image_url');
        });
    }

    public function down()
    {
        Schema::table('rappers', function (Blueprint $table) {
            $table->dropColumn('streams_this_year');
        });
    }
};
