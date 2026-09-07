<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('design_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_id')->constrained('designs')->onDelete('cascade');
            $table->integer('page_number');
            $table->string('layout_type')->nullable();
            $table->json('photos')->nullable();
            $table->json('texts')->nullable();
            $table->string('background')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('design_pages');
    }
};
