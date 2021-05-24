<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldNameSkipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skips', function (Blueprint $table) {
            $table->renameColumn('employee', 'user_id');
        });
        Schema::table('skips', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skips', function (Blueprint $table) {
            //
        });
    }
}
