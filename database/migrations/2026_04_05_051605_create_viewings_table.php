<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Property;
use Illuminate\Database\Schema\Blueprint;
use Infrastructure\Persistence\Migrations\InfrastructureMigration;

return new class extends InfrastructureMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->schema()->create('viewings', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained();
            $table->foreignIdFor(Property::class)->constrained();
            $table->dateTime('scheduled_at');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema()->dropIfExists('viewings');
    }
};
