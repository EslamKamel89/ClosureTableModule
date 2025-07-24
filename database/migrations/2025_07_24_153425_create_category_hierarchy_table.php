<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('category_hierarchy', function (Blueprint $table) {
            $table->foreignId('ancestor_id')->constrained('categories', 'id')->cascadeOnDelete();
            $table->foreignId('descendant_id')->constrained('categories', 'id')->cascadeOnDelete();
            $table->tinyInteger('depth')->unsigned()->index();

            $table->primary(['ancestor_id', 'descendant_id']);
            $table->index('descendant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('category_hierarchy');
    }
};
