<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')
                    ->references('id')
                    ->on('admin_users')
                    ->onDelete('cascade'); // Cascade deletions to delete all the items when the company deleted
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            //
        });
    }
};