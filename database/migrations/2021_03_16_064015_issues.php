<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Issues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->increments('issue_id');
            $table->string('issue_text')->nullable();
            $table->string('issue_type')->nullable();
            $table->string('issue_related')->nullable();
            $table->string('issue_file')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('issue_creator')->nullable();
            $table->string('issue_status')->nullable();
            $table->string('issue_remarks')->nullable();
            $table->string('issue_detail_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
