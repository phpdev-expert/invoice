<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_queue', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->integer('mailable_id');
            $table->string('mailable_type');
            $table->string('from');
            $table->string('to');
            $table->string('cc');
            $table->string('bcc');
            $table->string('subject');
            $table->string('body');
            $table->boolean('attach_pdf');
            $table->boolean('sent');
            $table->text('error')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mail_queue');
    }
}
