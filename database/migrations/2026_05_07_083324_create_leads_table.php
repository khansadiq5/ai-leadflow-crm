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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('company_name')->nullable();

            $table->string('source')->nullable();
            $table->string('interested_service')->nullable();
            $table->decimal('budget', 10, 2)->nullable();

            $table->enum('status', [
                'new',
                'contacted',
                'interested',
                'demo_scheduled',
                'proposal_sent',
                'negotiation',
                'converted',
                'lost'
            ])->default('new');

            $table->enum('priority', ['hot', 'warm', 'cold'])->default('warm');

            $table->date('follow_up_date')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
