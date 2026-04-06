<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'COMPLETED','CANCELLED'])->default('PENDING');
            $table->enum('priority', ['HIGH', 'LOW', 'MEDIUM'])->default('MEDIUM');
            $table->boolean('outside')->default(false);
            $table->date('due_date');
            $table->text('reason_cancelled')->nullable();
            $table->foreignId('admin_id')->references('id')->on('users');
            $table->foreignId('worker_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tasks');
    }
};
