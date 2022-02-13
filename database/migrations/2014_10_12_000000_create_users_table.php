<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            // Basique
            $table->string('name')->unique()->nullable();
            $table->string('steamid')->unique();
            $table->string('guid')->nullable();
            // Email
            $table->string('email')->unique()->nullable();
            $table->boolean('email_enabled')->nullable();
            $table->boolean('email_verified')->default(false);
            $table->string('email_verified_token')->nullable();
            $table->dateTime('email_verified_token_at')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->dateTime('email_disabled_at')->nullable();
            $table->boolean('email_prevent')->default(false);
            // Info
            $table->boolean('has_game')->default(false);
            $table->date('birth_date')->nullable();
            $table->string('country')->nullable();
            $table->string('timezone')->nullable();
            $table->dateTime('rules_seen_at')->nullable();
            // Forum
            $table->string('ipb_token')->nullable();
            $table->string('ipb_refresh')->nullable();
            $table->integer('ipb_id')->nullable();
            // Code de support
            $table->string('support_code')->nullable();
            $table->dateTime('support_code_at')->nullable();
            // Import
            $table->dateTime('imported')->nullable();
            $table->boolean('imported_exam_exempt')->default(false);
            $table->text('imported_exam_message')->nullable();
            // Désactivé
            $table->boolean('disabled')->default(false);
            $table->string('disabled_reason')->nullable();
            $table->dateTime('disabled_at')->nullable();
            // Changement de nom
            $table->integer('name_changes_remaining')->default(0);
            $table->text('name_changes_reason')->nullable();
            // Whitelist
            $table->dateTime('whitelist_at')->nullable();
            // Paramètres
            $table->text('settings')->nullable();
            // Admin
            $table->boolean('admin')->default(false);
            // active
            $table->dateTime('active_at')->nullable();
            // Autres
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
