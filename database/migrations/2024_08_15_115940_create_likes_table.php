<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            # 「いいね」を行ったユーザーの ID を格納
            $table->unsignedBigInteger('user_id');
            # 「いいね」がついた投稿の ID を格納
            $table->unsignedBigInteger('post_id');
            $table->timestamps();
            # user_id と post_id の組み合わせがテーブル内で一意になるように制約を設ける(同じユーザーが同じ投稿に複数回「いいね」できない)
            $table->unique(['user_id', 'post_id']);
            # user_id 列に外部キー制約を追加し、users テーブルの id 列を参照、もしユーザーが削除された場合、そのユーザーの「いいね」も自動的に削除（カスケード削除）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            # post_id 列に外部キー制約を追加し、users テーブルの id 列を参照、もしユーザーが削除された場合、そのユーザーの「いいね」も自動的に削除（カスケード削除）
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
