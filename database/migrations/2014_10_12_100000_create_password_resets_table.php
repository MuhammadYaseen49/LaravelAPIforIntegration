<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->text('token');
            $table->boolean('expiry');
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
