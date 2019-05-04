<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOptTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_log', function ($table) {
            $table->dropIndex('raw_log_type_id_index');
        });

        Schema::table('transactions', function ($table) {
            $table->dropIndex('token_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_log', function ($table) {
            $table->index('type_id');
        });

        Schema::table('transactions', function ($table) {
            $table->index('token_id');
        });
    }
}
