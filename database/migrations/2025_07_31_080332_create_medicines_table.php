<?php

use App\Models\Category\Category;
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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class)->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('generic_name')->nullable();//الاسم العلمي
            $table->string('facturer')->nullable();//الشركة المصنعة
            $table->tinyInteger('dosage_form')->default(0);//dosijاقراص وشراب و كبسولات و اخر
            $table->string('strength');//تركيز
            $table->tinyInteger('isLimited')->default(0);
            $table->integer('stock')->default(0);
            $table->decimal('price',10,2)->default(0);
            $table->date('expired_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
