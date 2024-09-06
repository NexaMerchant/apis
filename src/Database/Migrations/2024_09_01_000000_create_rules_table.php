<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        
        Schema::create('rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ptype')->nullable();
            $table->string('v0')->nullable();
            $table->string('v1')->nullable();
            $table->string('v2')->nullable();
            $table->string('v3')->nullable();
            $table->string('v4')->nullable();
            $table->string('v5')->nullable();
            $table->timestamps();

            $table->comment('casbin rule table');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        
        Schema::dropIfExists('rules');
    }
}
