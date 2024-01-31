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
        Schema::table('users', function (Blueprint $table) {
             $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('agent_type')->nullable();
            $table->string('agentid')->nullable();
            $table->string('subagentid')->nullable();
            $table->string('role')->nullable();
            $table->boolean('active_status')->default(true);
            $table->boolean('mobile_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('mobile');
            $table->dropColumn('agent_type');
            $table->dropColumn('agentid');
            $table->dropColumn('subagentid');
            $table->dropColumn('role');
            $table->dropColumn('active_status');
            $table->dropColumn('mobile_verified');
        });
    }
};
