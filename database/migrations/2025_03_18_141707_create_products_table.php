<?php

use App\Models\User;
use App\Models\Branch\Branch;
use App\Models\Product\Category;
use App\Enums\Product\ProductStatus;
use App\Enums\Product\LimitedQuantity;
use Illuminate\Support\Facades\Schema;
use App\Traits\CreatedUpdatedByMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use CreatedUpdatedByMigration;
    /**
     * Run the migrations.
     */
    public function up(): void
    {//name ,description, price, status
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->json('specifications')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->boolean('is_limited_quantity')->default(LimitedQuantity::UNLIMITED->value);
            $table->smallInteger('quantity')->default(0);
            $table->tinyInteger('status')->default(ProductStatus::INACTIVE->value);
            $table->foreignIdFor(Category::class,'category_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(Category::class,'sub_category_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            //expired_at, branch_id, user_id
            $table->date('expired_at')->nullable();
            $table->foreignIdFor(Branch::class)->nullable()->constrained('branches')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
