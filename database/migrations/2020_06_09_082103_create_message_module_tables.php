<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMessageModuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('chat_id');
            $table->foreign('chat_id')
                ->references('id')
                ->on('chats');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->string('text', 2000);

            $table->timestamps();
        });

        DB::statement("alter table messages add column ts_text tsvector");
        DB::statement("create index ts_text_search_gin on messages using GIN(ts_text)");
        DB::statement("
            create trigger ts_text_search
                before insert or update on messages
                for each row
                    execute procedure
                        tsvector_update_trigger('ts_text', 'pg_catalog.simple', 'text')
        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_module_tables');
    }
}
