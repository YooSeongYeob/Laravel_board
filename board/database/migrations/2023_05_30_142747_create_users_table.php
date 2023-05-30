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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // 널 허용 x
            $table->string('password'); // 널 허용 x
            $table->string('email')->unique(); // pk는 보통 숫자 이메일이 문자열이기 때문에 오래 걸린다 보통은 숫자에 pk
            $table->string('name'); // 널 허용 x
            // $table->timestamp('email_verified_at')->nullable(); // 이메일 인증하는 애임
            $table->rememberToken(); // 라라벨 엘로퀀트가 얘를 자동으로 체크 로그인 유지 기능임
            $table->timestamps();
            $table->softDeletes();
        });
    }


    // 라라벨 최대 암호화 글자 수 60자임
    // 각 컬럼들의 크기를 늘리는 건 되는데 줄이는 건 안 됨
    // 그래서 크기 설정 값을 초기에 잘 설정해야 함


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
