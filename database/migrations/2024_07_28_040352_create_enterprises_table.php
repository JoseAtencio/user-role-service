<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\DocumentTypeEnum;
use App\Enums\StatesTypesEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enterprises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('document_number');
            $table->enum('document_type', [
                DocumentTypeEnum::PASSPORT->value,
                DocumentTypeEnum::DNI->value,
                DocumentTypeEnum::CIF->value,
                DocumentTypeEnum::NIE->value,
                DocumentTypeEnum::NIF->value,
                DocumentTypeEnum::DRIVER_LICENSE->value,
                DocumentTypeEnum::OTHER->value,
            ]);
            $table->enum('state', [
                StatesTypesEnum::ACTIVE->value,
                StatesTypesEnum::INACTIVE->value
            ])->nullable()->default(
                StatesTypesEnum::ACTIVE->value
             );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprises');
    }
};
