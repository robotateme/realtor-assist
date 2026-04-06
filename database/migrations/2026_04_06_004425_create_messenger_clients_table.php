<?php

use App\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messenger_clients', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained();
            $table->string('provider');
            $table->string('username');
            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('is_bot')->default(false);
            $table->string('messenger_id');
            $table->timestamps();

            $table->unique(['provider', 'messenger_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messenger_clients');
    }
};
