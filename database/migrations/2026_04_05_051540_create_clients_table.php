<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Infrastructure\Persistence\Migrations\InfrastructureMigration;

return new class () extends InfrastructureMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->schema()->create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema()->dropIfExists('clients');
    }
};
