<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('experience_years')->nullable()->after('phone');
            $table->string('document_path')->nullable()->after('experience_years');
            $table->boolean('is_approved')->default(false)->after('document_path');
            $table->timestamp('approved_at')->nullable()->after('is_approved');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'experience_years', 'document_path', 'is_approved', 'approved_at']);
        });
    }
};