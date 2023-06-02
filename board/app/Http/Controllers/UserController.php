<?php
/*************************************
 * 프로젝트명   : laravel_borad
 * 디렉토리     : Controllers
 * 파일명       : UserController.php
 * 이력         : v001 0530 BJ.Park new
 *************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    function login() {

        $arr['key']='test';
        $arr['kim']='park';
        Log::emergency('emergency', $arr);
        Log::alert('alert', $arr);
        Log::critical('critical', $arr);
        Log::error('error', $arr);
        Log::warning('warning', $arr);
        Log::notice('notice', $arr);
        Log::info('info', $arr);
        Log::debug('debug', $arr);

        // 보통은 에러랑 크리티컬을 씀
        // 이멀전시는 서버 자체가 죽는다고 함

        return view('login');
    }

    function loginpost(Request $req) {

        // Log::debug('유효성 ok'); 특정 값을 확인할 때만 쓴다고 하심
        // log::debug($req->password. ' : '. .....) 이런 식으로 만듬

        //유효성 체크
        $req->validate([
            'email'    => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))) {
            $error = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('error', $error);
        }

        // 유저 인증작업
        Auth::login($user);
        if(Auth::check()) {
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index'));
        } else {
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
        }
    }

    function registration() {
        return view('registration');
    }

    function registrationpost(Request $req) {
        //유효성 체크
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        $user = User::create($data); // insert
        if(!$user) {
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.<br>잠시 후에 다시 회원가입을 시도해 주십시오.';
            return redirect()
                ->route('users.registration')
                ->with('error', $error);
        }

        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.');
    }

    function logout() {
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    function withdraw() {
        $id = session('id');
        $result = User::destroy($id);
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    function edit() {
        $user = User::find(Auth::User()->id);
        
        return view('useredit')->with('data', $user);
    }

    function editpost(Request $req) {
        $arrKey = []; // 수정할 항목을 배열에 담는 변수

        $baseUser = User::find(Auth::User()->id); // 기존 데이터 획득

        // 기존 패스워드 체크
        if(!Hash::check($req->bpassword, $baseUser->password)) {
            return redirect()->back()->with('error', '기존 비밀번호를 확인해 주세요.');
        }

        // 수정할 항목을 배열에 담는 처리
        if($req->name !== $baseUser->name) {
            $arrKey[] = 'name';
        }
        if($req->email !== $baseUser->email) {
            $arrKey[] = 'email';
        }
        if(isset($req->password)) {
            $arrKey[] = 'password';
        }

        // 유효성체크를 하는 모든 항목 리스트
        $chkList = [
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'bpassword'=> 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ];

        // 유효성 체크할 항목 셋팅하는 처리
        $arrchk['bpassword'] = $chkList['bpassword'];
        foreach($arrKey as $val) {
            $arrchk[$val] = $chkList[$val];
        }

        //유효성 체크
        $req->validate($arrchk);

        // 수정할 데이터 셋팅
        foreach($arrKey as $val) {
            if($val === 'password') {
                $baseUser->$val = Hash::make($req->$val);
                continue;
            }
            $baseUser->$val = $req->$val;
        }
        $baseUser->save(); // update

        return redirect()->route('users.edit');
    }

    // with 메소드, 뷰랑 리다이렉트일 때 차이
    // redirect에 with 작성하면 세션에 저장해서 사용하고
    // view에 with를 작성하면 
    // with는 기본적으로 세션에 등록함 뷰에 가면 with가 뷰에 체이닝하면 뷰에 등록함
    // intended 그냥 리다이렉트 안에 넣으라고 하심 메소드 원래 접속하게 하려는 url에 접속하게 해주고 설정된 페이지가 없다면 다른 페이지로 넘어가게 하는거라고 함 
    // 토큰 / 보통 유저가 검증하는 작업을 할 때 사용함 블레이드 템플릿에 csrf도 자동으로 통신 되는 애임 유저 쿠키에 저장하고 우리 웹서버에도 저장함 
    // 토큰은 검증 작업 // 세션을 인증으로 브라우저에서는 사용할 수 있는데 문제가 있음 모바일은 쿠키가 없음 세션 인증 불가능임 그래서 나온게 jwt 토큰을 통해서 인증하게 됨
    // 메일 << 숙제


    // 쿼리빌더로 한다고 함 + sql
    // 엘로퀀트는 다음에 할 듯
    // 체이닝 없이 해야할 듯 
}
