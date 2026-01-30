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
        Schema::create('acf_field_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->string('description')->nullable();
            $table->string('position')->default('normal'); // normal, sidebar, after_title
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('acf_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('acf_field_groups')->onDelete('cascade');
            $table->string('name');
            $table->string('key')->unique();
            $table->string('type');
            $table->text('instructions')->nullable();
            $table->boolean('is_required')->default(false);
            $table->json('default_value')->nullable();
            $table->json('options')->nullable();
            $table->json('conditional_logic')->nullable();
            $table->json('presentation')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('acf_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('acf_field_groups')->onDelete('cascade');
            $table->string('model_type');
            $table->string('context')->default('admin.edit'); // admin.create, admin.edit, public
            $table->string('position')->default('normal');
            $table->json('rules')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('acf_values', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->foreignId('field_id')->constrained('acf_fields')->onDelete('cascade');
            $table->string('locale')->nullable();
            $table->json('value')->nullable();
            $table->timestamps();

            $table->unique(['model_type', 'model_id', 'field_id', 'locale'], 'acf_values_unique');
            $table->index(['model_type', 'model_id']);
        });

        Schema::create('acf_settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->json('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acf_settings');
        Schema::dropIfExists('acf_values');
        Schema::dropIfExists('acf_locations');
        Schema::dropIfExists('acf_fields');
        Schema::dropIfExists('acf_field_groups');
    }
};
