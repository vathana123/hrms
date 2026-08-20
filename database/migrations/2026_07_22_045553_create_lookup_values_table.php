<?php

use App\Enums\LookupValueType;
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
        Schema::create('lookup_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('lookup_values')
                ->nullOnDelete();
            $table->string('name');
            $table->string('short_name');
            $table->string('local_name')->nullable();
            $table->enum('type', array_column(LookupValueType::cases(), 'value'));
            $table->integer('display_ordering');
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lookup_values');
    }
};
