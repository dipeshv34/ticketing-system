<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Role enumeration
            $table->enum('role', ['super_admin', 'admin', 'client'])
                ->default('client')
                ->after('password');

            // Link admins/clients to a client account
            $table->foreignId('client_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->after('role');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
            $table->dropColumn('role');
        });
    }
};
