<?php

use App\Enums\MenuType;
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
        Schema::create('dashboard_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('local_name')->nullable();
            $table->string('icon')->nullable();
            $table->string('permission')->nullable();
            $table->string('route_url')->nullable();
            $table->boolean('open_in_new_tab')->default(false);
            $table->enum('type', array_column(MenuType::cases(), 'value'));
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
        Schema::dropIfExists('dashboard_menus');
    }
};
