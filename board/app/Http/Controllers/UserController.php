<?php
//------------------------------------------------------
// 프로젝트명 : laravel_board
// 디렉터리   : controllers
// 파일명     : UserController.php
// 이력       : v001 0530 SY.Yoo new 
//------------------------------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// 클래스를 사용할 때는 항상 use를 사용해줘야 한다

class UserController extends Controller
{
    function login() {
        return view('login');
    }

    function loginpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'email'    => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^&*-])(?=.*[0-9]).{8,20}$/'
            ]);
    
            // 유저 정보 습득 이메일 기준으로 찾아서 가져올 거임
            // 우선 담을 변수 설정
            $user = User::where('email', $req->email)->first();
            // 엘로퀀트 객체를 이용해서 리퀘스트를 하는데 이메일 기준으로 가져온다는 것임
            // 2번이면 분기문 나와야 하고 1번만에 체크하면 1번만에 간단해짐
            if(!$user || !(Hash::check($req->password, $user->password))) {
                $errors[] = '아이디와 비밀번호를 확인해주세요';
                return redirect()->back()->with('errors', collect($errors));
            }

            // 유저 인증 작업
            Auth::login($user); // 로그인 작업하고 라라벨이 토큰이라던가 세션 아이디를 알아서 처리해줌
            if(Auth::check()) {
            // 인증작업을 해서 트루나 펄스로 오는데 트루면 인증 작업 완료
                session([$user->only('id')]); // 세션에 인증된 회원 pk 등록
                return redirect()->intended(route('boards.index')); // route로 리다이렉트 하면 정보가 담겨있지만 인텐디드는 완전 새로운 정보가 담기게 된다
                // 로그인할 때 인텐디드랄 많이 사용함 라우트로 경로 지정해주기
            } else {
                $errors[] = '인증작업 에러';
                return redirect()->back()->with('errors', collect($errors));
            } // 에러나면 로그인 페이지로 가게 함
        }

    function registration() {
        return view('registration');
    }

    function registrationpost(Request $req) {
        // DB 연결 전 유효성 검사부터 
        // 유효성 체크
        $req->validate([
        'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
        ,'email'    => 'required|email|max:100'
        ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^&*-])(?=.*[0-9]).{8,20}$/'
        ]);

        $data['name'] = $req->name;
        //req가 객체 리퀘스트의 객체 배열처럼 사용하는 것처럼 느끼는데 배열이 아니라서 프로퍼티를 사용할 수도 있음 메소드를 가져올 수도 있음 
        $data['email'] = $req->email;
        $data['email'] = Hash::make($req->password);
        // 엘로퀀트를 이용해서 삽입할 거임
         
        $user = User::create($data); // 이렇게 하면 insert가 돼서 이 정보가 유저에게 담긴다 Models class명 User.php
        if(!$user) {
            $errors[] = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            $errors[] = '잠시 후에 다시 회원가입을 시도해 주십시오';
            return redirect()
                ->route('users.registration') // 라우트를 체이닝으로 연결 에러메시지를 받을 수 있는 곳을 찾아야 함
                ->with('errors', collect($errors));                
        }

        // 회원가입을 마치면 자동으로 로그인이 되고 메인페이지로 넘어감 본인확인을 다 하니까
        // 해외는 메일로 인증해야 해서 인서트가 성공하면 메일을 날려주고 로그인 페이지로 넘기고 로그인 페이지에서는 인증 메일이나 링크를 눌러 접속했을 때 이메일 인증이 그때 갱신되고 로그인까지 완료
        // 회원가입을 시도했으니 registration으로 넘어가야 함
        // 인서트 할 때 실패한 경우임 
        // 잘 되지 않았다면 펄스가 될거니까 앞에 느낌표
        // 돌아온 결과를 확인해서 에러인지 아닌지 확인을 해야 한다
        // 회원가입 완료 로그인 페이지로 이동한다고 함


        // $data['email'] = $req->input('name');
        // 유효성 체크하고 문제 없으면 db랑 연결 패스워드 암호화 해주고 db 연결
    
    
        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료했습니다.<br>가입하신 아이디와 비밀번호로 로그인을 해주십시오');
        }
    }
    // 로그인 메소드부터 만들고 있음 이후에 뷰를 만들어야 함
    // 로그인 회원가입 로그아웃 아이디 비밀번호 찾기 다 유저 컨트롤러에 포함 
    // 인증과 인가 관련 처리도 포함 라이브러리로 따로 뺄 수도 있음
    // 라라벨은 미들웨어로 커스터마이징을 많이 한다고 함 
    // 거기까지는 안 한다고 하심