<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('design_code')->unique(); // MB-xxxxxx
            $table->string('session_id')->nullable();
            $table->foreignId('template_id')->constrained('templates')->onDelete('cascade');
            $table->string('book_size');
            $table->integer('total_pages')->default(10);
            $table->json('design_data')->nullable();
            $table->string('status')->default('draft'); // draft/pending/ordered/expired
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('designs');
    }
};
