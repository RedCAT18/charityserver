<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\User;
use App\Userdata;


class RegisterController extends Controller
{
    //중복 유저아이디 검사.
//    public function validateUser(Request $request){
//
//    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function duplicationCheck(Request $request){
//        return $request->all();
        try {
            $data = User::where('login_id', '=', $request->login_id)->get();
//            return $data;
            if(count($data) == 0) {
                return response()->json(['success'=> 1, 'check'=> 1]);
            } else {
                return response()->json(['success'=> 1, 'check'=> 0]);
            }
        } catch (Exception $e) {
            return response()->json(['error'=>$e->getLine().": ".$e->getMessage()]);
        }
    }

    public function register(Request $request) {
//        return $request->all();
//    }
      try{
//          파일 확장자 다시 한 번 검사 (이미 클라이언트에서 하긴 했지만..
            if($request->hasFile('stamp') || $request->hasFile('logo')) {
                $stampFileType = $request->file('stamp')->getClientOriginalExtension();
                $logoFileType = $request->file('logo')->getClientOriginalExtension();
            }
            if($stampFileType != ('jpg' || 'png')){
                return response()->json(['success'=> 0, 'error' => '직인 이미지가 잘못된 파일 형식입니다. jpg, png 파일만 가능합니다.']);
            }
            if($logoFileType != ('jpg' || 'png')){
                return response()->json(['success'=> 0, 'error' => '로고 이미지가 잘못된 파일 형식입니다. jpg, png 파일만 가능합니다.']);
            }

    //      유저 정보 저장하기
            $user = new User; // 로그인파일


//          로그인용 정보
          $user->login_id = $request->user_id;
          $user->password = \Hash::make($request->password);

          $user->save();

//          로그인정보에서 생성된 아이디를 가져온다 (foreign key로 사용)

          $self = User::where('login_id','=',$request->user_id)->first();

          $userdata = new Userdata; //상세 정보파일

          $userdata->user_id = $self->id;
          $userdata->organization_name = $request->organization_name;
          $userdata->business_number = $request->business_number;
          $userdata->username = $request->username;
          $userdata->nickname = $request->nickname;
          $userdata->phone = $request->phone;
          $userdata->mobile = $request->mobile;
          $userdata->email = $request->email;
          $userdata->address = $request-> address;
          $userdata->zipcode = $request->postcode;

          if($request->webzine == 'false') $userdata->webzine = 0;

          if($request->hasFile('stamp')) $userdata->stamp = 1;

          if($request->hasFile('logo')) $userdata->logo = 1;

//          이미지 파일 저장
//          1.유저 아이디로 폴더 생성

          $filePath = base_path().'/public/userimages/'.$self->id.'/';
          if($request->file('stamp')) {
              $request->file('stamp')->move($filePath, 'stamp.'.$stampFileType);
          }
          if($request->file('logo')) {
              $request->file('logo')->move($filePath, 'logo.'.$logoFileType);
          }
          $userdata -> save();

          return response()->json(['success'=> 1, 'message'=>'회원 가입이 성공적으로 완료되었습니다.']);
      } catch(\Exception $e) {
          return response()->json(['error'=>$e->getLine().": ".$e->getMessage()]);
      }
    }

}
