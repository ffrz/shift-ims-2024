<?php

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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();

            // customer info
            $table->string('customer_name', 100);
            $table->string('customer_contact', 100);
            $table->string('customer_address', 200);

            // device info
            $table->string('device_type', 100);
            $table->string('device', 100);
            $table->string('equipments', 200);
            $table->string('device_sn', 100);

            // service info
            $table->string('problems', 200);
            $table->string('actions', 200);
            $table->date('date_checked')->nullable()->default(null);;
            $table->date('date_work_begin')->nullable()->default(null);;
            $table->date('date_completed')->nullable()->default(null);;
            $table->unsignedTinyInteger('service_status');

            // order
            $table->unsignedTinyInteger('order_status');
            $table->date('date_received');
            $table->date('date_completed')->nullable()->default(null);
            $table->date('date_taken')->nullable()->default(null);

            // // cost and payment
            $table->decimal('down_payment', 8, 0)->default(0.);
            $table->decimal('estimated_cost', 8, 0)->default(0.);
            $table->decimal('total_cost', 8, 0)->default(0.);
            $table->unsignedTinyInteger('payment_status')->default(0);

            // extra
            $table->string('technician');
            $table->text('notes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
