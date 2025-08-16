<?php

use App\Models\Branch\Branch;
use App\Models\Medicine\Medicine;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branch_medicine', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Medicine::class)->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_medicine');
    }
};
