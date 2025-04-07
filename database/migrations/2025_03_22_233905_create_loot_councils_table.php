<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('loot_councils', function (Blueprint $table) {
            $table->id();
            $table->string('player');
            $table->date('date');
            $table->time('time');
            $table->string('loot_id')->nullable();
            $table->bigInteger('itemID');
            $table->text('itemString');
            $table->string('response');
            $table->integer('votes');
            $table->string('class');
            $table->string('instance');
            $table->string('boss');
            $table->string('gear1')->nullable();
            $table->string('gear2')->nullable();
            $table->string('responseID');
            $table->boolean('isAwardReason');
            $table->string('rollType');
            $table->string('subType');
            $table->string('equipLoc');
            $table->text('note')->nullable();
            $table->string('owner');
            $table->string('itemName');
            $table->bigInteger('servertime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loot_councils');
    }
};
