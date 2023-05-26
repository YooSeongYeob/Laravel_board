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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('title', 30);
            $table->string('content', 2000);
            $table->integer('hits');
            $table->timestamps();
            $table->softDeletes(); // 옐로퀀트 이용할 때 검색을 빼준다함 ?.. 일일이 플래그를 안 써줘도 되게끔 해준다
        });
    }
    // 더미데이터는 시더로 넣을지 팩토리로 넣을지 선택

    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards');
    }
};
