<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultColum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('name')->default("")->change();
            $table->text('body')->default("")->change();
            $table->string('content_length')->default("")->change();
            $table->string('status_code')->default("")->change();
            $table->text('h1')->default("")->change();
            $table->text('keywords')->default("")->change();
            $table->text('description')->default("")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            //
        });
    }
}
