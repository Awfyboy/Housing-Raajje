
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('employment_status')
                ->default('active');
        });

        Schema::table('branch_managers', function (Blueprint $table) {
            $table->string('employment_status')
                ->default('active');
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('employment_status');
        });

        Schema::table('branch_managers', function (Blueprint $table) {
            $table->dropColumn('employment_status');
        });
    }
};
