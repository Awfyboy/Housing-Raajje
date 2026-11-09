<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('email');
            $table->string('pending_email')->nullable()->after('avatar_path');
            $table->string('pending_email_token')->nullable()->after('pending_email');
            $table->string('admin_request_type')->nullable()->after('pending_email_token');
            $table->text('admin_request_note')->nullable()->after('admin_request_type');
            $table->string('admin_request_status')->nullable()->after('admin_request_note');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_path', 'pending_email', 'pending_email_token', 'admin_request_type', 'admin_request_note', 'admin_request_status']);
        });
    }
};