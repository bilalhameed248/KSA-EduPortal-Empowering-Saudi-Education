<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IssueFlow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('issueflow', function (Blueprint $table) {
            $table->increments('issue_flow_id');
            $table->string('issue_current_owner')->nullable();
            $table->timestamps();
            $table->string('issue_comment')->nullable();
            $table->string('report_id')->nullable();
            $table->string('issue_id')->nullable(); 
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issueflow');
    }
}
