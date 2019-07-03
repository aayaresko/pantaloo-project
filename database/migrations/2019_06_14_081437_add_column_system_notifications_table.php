<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSystemNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            $table->string('ext_id')->nullable()->index()->after('value');
            $table->integer('confirmations')->nullable()->after('value');
            $table->integer('transaction_id')->unsigned()->nullable()->index()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'confirmations', 'ext_id']);
        });
    }
}
