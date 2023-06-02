<?php

namespace Tests\Feature;

use App\Models\Boards;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardsTest extends TestCase
{
    //php artisan make:test BoardsTest
    // 이름의 끝이 Test로 끝날 것

    use RefreshDatabase; // 테스트 완료 후 DB 초기화를 위한 트레이트
    use DatabaseMigrations; // DB 마이그레이션 - 시작하자마자 테이블 다 만들어줌
    // Ctrl + Space emmet 기능 활성화 됨 elephense 확장프로그램 설치하면 됨
    
    // 메소드는 무조건 테스트로 시작해야지 작동한다
    // 클래스명은 테스트로 끝나야 함

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_게스트_리다이렉트() // 게스트로 로그인하고 리다이렉트가 되는지 안 되는지 확인을 한다고 함
    {
        $response = $this->get('/boards'); // 아무런 인증 없이 보드즈에 갈거다 -> 이것에 대한 레스폰스를 받을 것이다

        $response->assertRedirect('/users/login');
        // 메소드 마다 리다이렉트를 사용하는 코드가 다르다고 함 
    
        // 보드즈를 리스폰스에 넣고 리스폰스를 리다이렉트 할 때 users/login이 존재하는지 봐야 함
    }

    public function test_index_유저인증() {
        // 테스트용 유저 생성
        $user = new User([
        'email' => 'asdasd@aa.aa'
        ,'name' => '테스트'
        ,'password' => 'asdasd'

    ]);
    $user->save();

    $response = $this->actingAs($user)->get('/boards');
    
    $this->assertAuthenticatedAs($user);
    }


    public function test_index_유저인증_뷰반환() {
        // 테스트용 유저 생성
        $user = new User([
        'email' => 'aa@aa.aa'
        ,'name' => '테스트'
        ,'password' => 'asdasd'

    ]);
    $user->save();

    $response = $this->actingAs($user)->get('/boards');
    
    $response->assertViewIs('list');
    }

    public function test_index_유저인증_뷰반환_데이터확인() {
        // 테스트용 유저 생성
        $user = new User([
        'email' => 'aa@aa.aa'
        ,'name' => '테스트'
        ,'password' => 'asdasd'

    ]);
    $user->save();
    $board1 = new Boards([
        'title' => 'test1'
        ,'content' => 'content1'
    ]);
    $board1->save();

    $board2 = new Boards([
        'title' => 'test2'
        ,'content' => 'content2'
    ]);
    $board2->save();


    $response = $this->actingAs($user)->get('/boards');
    // response 해서 boards를 부름 레스폰스 안에는 굉장히 많은 스트링(문자열)이 들어가 있음 
    // 문자열 안에서 각각의 태그를 구분하고 뷰를 찾아서 데이터가 있는지 없는지 보는 것

    $response->assertViewHas('data'); // 뷰 안에 키값인 데이터가 있느냐 확인하는 것
    $response->assertsee('test1'); // 레스폰스 안에 해당하는 문자열이 있는지 없는지 확인하는 것
    $response->assertsee('test2'); // 레스폰스 안에 해당하는 문자열이 있는지 없는지 확인하는 것
    }
}

// 장점: 테스트 자동화를 만들어두면 수정한 부분만 참고해서 적절하게 바꿔주고 테스트를 빠르게 끝내버릴 수 있음
// 단점: 작업이 이중임 30% 내외로 작업시간이 조금 길어진다
// 테스트 코드 짜는데 시간이 오래 걸린다 만지지도 못하게 한다 함 4년차 때부터 선생님이 가능하셨다고 함
// 육성하고도 문제가 생김 옳은가에 대한 판단도 있음
// 사용할지 말지 고민이 필요하다고 함 
// 대부분의 프로젝트에는 사용하지 않음 이걸 정확히 아는 전문가가 많이 없다고 함
// 안 써도 된다 함 
// 그럼 이거 말고 대신 사용할 수 있는 게 무엇일까?

// 로깅, 설정(한글화)

// crud 중에 지금 이건 r만 한거라고 함 boardsController에 내용이 많음
// 게임 업계에서는 자동화 테스트로 확인한다고 함 
// 웹개발 현장에서는 잘 안 한다고 함 
// 한 번 구축하면 수정이 잘 없다고 함 버그가 일어나지 않는 이상

// 셀렉트 가져오는지
// 게스트로 들어가지는지
// 로그인 되는지
// 오더바이 역순으로 되는지 테스트 현업 가서 하라고 함 동작 잘 되면 지금은 오케이라 하심

// 쿼리문에 대한 테스트만 일단 하자 
// $response->assertViewHas('data'); // 뷰 안에 키값인 데이터가 있느냐 확인하는 것
// $response->assertsee('test1'); // 레스폰스 안에 해당하는 문자열이 있는지 없는지 확인하는 것
// $response->assertsee('test2'); // 레스폰스 안에 해당하는 문자열이 있는지 없는지 확인하는 것
// 이거라고 하심


// url과 레스폰스 잘 설정되어 있는지 확인

// php artisan test

// 라라벨 431p~ 477p 난이도 어렵고 이해 안 된다고 함 지금은

// 로그를 파일에 저장하거나 데이터베이스에 저장을 함
// 파일에 저장하기도 하는데 라라벨은 파일에 저장함 