<?php

declare(strict_types=1);

use Domain\Property\TypesEnum;
use Illuminate\Database\Schema\Blueprint;
use Infrastructure\Persistence\Migrations\InfrastructureMigration;

return new class extends InfrastructureMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->schema()->create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->bigInteger('price');
            $table->enum('type', array_column(TypesEnum::cases(), 'value'));
            $table->tinyInteger('availability_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema()->dropIfExists('properties');
    }
};
