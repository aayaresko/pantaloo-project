<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinkToTrackers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('trackers', function(Blueprint $table) {
        $table->string('campaign_link',255);
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
  public function down()
{
    Schema::table('trackers', function(Blueprint $table) {
        $table->dropColumn('campaign_link');
    });
}
}