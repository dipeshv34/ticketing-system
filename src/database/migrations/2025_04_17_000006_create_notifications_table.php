<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_chat_id')
                ->constrained('ticket_chats')
                ->cascadeOnDelete();
            $table->string('sent_to'); // email address
            $table->enum('status', ['queued', 'sent', 'failed'])
                ->default('queued');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('notifications');
    }
};
