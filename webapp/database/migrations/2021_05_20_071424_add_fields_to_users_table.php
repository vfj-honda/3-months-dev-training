<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedinteger('chatwork_id');
            $table->unsignedinteger('employee_number')->nullable();
            $table->unsignedtinyInteger('authority')->default(1);
            $table->timestamp('birthday')->nullable();
            $table->timestamp('entry_day')->nullable();
            $table->unsignedtinyInteger('gender')->nullable();
            $table->string('country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'deleted_at',
                'chatwork_id',
                'authority',
                'birthday',
                'entry_day',
                'gender',
                'country']);
        });
    }
}
