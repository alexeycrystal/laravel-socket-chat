<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CrcreateFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Function to sort PG one-dimension arrays
         */
        DB::statement("
            CREATE OR REPLACE FUNCTION array_sort (ANYARRAY)
                RETURNS ANYARRAY LANGUAGE SQL
                AS $$
                SELECT ARRAY(SELECT unnest($1) ORDER BY 1)
                $$;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
