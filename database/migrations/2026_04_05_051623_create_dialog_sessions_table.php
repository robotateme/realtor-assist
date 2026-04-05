<?php

use App\Models\Client;
use Illuminate\Database\Schema\Blueprint;
use Infrastructure\Persistence\Migrations\InfrastructureMigration;

return new class extends InfrastructureMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->schema()->create('dialog_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained();
            $table->integer('current_intent');
            $table->json('context_data')->nullable();
            $table->text('last_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema()->dropIfExists('dialog_sessions');
    }
};
