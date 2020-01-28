<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUserBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bonuses', function (Blueprint $table) {
            // TODO Lior - fix it manually
            //$table->binary('ip_address')->nullable()->index()->after('data');
            DB::statement('ALTER TABLE `user_bonuses` ADD `ip_address` VARBINARY(16) NULL AFTER `data`');
            DB::statement('ALTER TABLE `user_bonuses` ADD INDEX (`ip_address`)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bonuses', function (Blueprint $table) {
            //$table->dropColumn('ip_address');
            DB::statement('ALTER TABLE `user_bonuses` DROP COLUMN `ip_address`');
        });
    }
}
