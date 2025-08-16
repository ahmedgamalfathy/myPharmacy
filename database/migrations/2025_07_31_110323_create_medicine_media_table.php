<?php
use App\Enums\Media\IsMain;
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
        Schema::create('medicine_media', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->boolean('is_main')->default(IsMain::SECONDARY->value);
            $table->foreignIdFor(Medicine::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
