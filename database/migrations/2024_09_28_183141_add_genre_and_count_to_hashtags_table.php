<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // genre, countカラムが存在しない場合に追加
        Schema::table('hashtags', function (Blueprint $table) {
            if (!Schema::hasColumn('hashtags', 'genre')) {
                $table->string('genre')->nullable();
            }
            if (!Schema::hasColumn('hashtags', 'count')) {
                $table->integer('count')->default(0);
            }
        });
    }

    public function down()
    {
        // genre, countカラムがすでに存在する場合に削除
        Schema::table('hashtags', function (Blueprint $table) {
            if (Schema::hasColumn('hashtags', 'genre')) {
                $table->dropColumn('genre');
            }
            if (Schema::hasColumn('hashtags', 'count')) {
                $table->dropColumn('count');
            }
        });
    }
};
