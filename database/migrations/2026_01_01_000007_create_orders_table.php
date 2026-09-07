<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_id')->constrained('designs')->onDelete('cascade');
            $table->string('order_code')->unique();
            $table->string('customer_name');
            $table->string('customer_wa');
            $table->text('customer_address');
            $table->text('notes')->nullable();
            $table->string('book_size');
            $table->integer('total_pages');
            $table->string('status')->default('new'); // new/waiting_payment/processed/shipped/completed/cancelled
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->string('tracking_number')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
