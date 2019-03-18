<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRestrictionUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restriction_games_by_country', function (Blueprint $table) {
            $table->unique(['game_id', 'code_country', 'mark'], 'unique_restriction_game_id_code_country_mark');
        });

        Schema::table('restriction_categories_by_country', function (Blueprint $table) {
            $table->unique(['category_id', 'code_country', 'mark'], 'unique_restriction_category_id_code_country_mark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restriction_games_by_country', function (Blueprint $table) {
            $table->dropUnique('unique_restriction_game_id_code_country_mark');
        });

        Schema::table('restriction_categories_by_country', function (Blueprint $table) {
            $table->dropUnique('unique_restriction_category_id_code_country_mark');
        });
    }
}
