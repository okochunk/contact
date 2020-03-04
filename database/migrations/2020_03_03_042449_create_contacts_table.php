<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('group_id')->unsigned();
            $table->string('avatar', 255);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->integer('country');
            $table->bigInteger('state');
            $table->bigInteger('city');
            $table->text('address');
            $table->integer('zip');
            $table->string('email', 255)->unique();
            $table->string('phone')->nullable();
            $table->text('note')->default(null)->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('group_id');
        });

        DB::statement('ALTER TABLE contacts ADD FULLTEXT fulltext_index (first_name, last_name, email)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
