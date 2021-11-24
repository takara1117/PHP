<?php

	namespace App\Http\Controllers;
	
	use Illuminate\Support\Facades\DB;
	use Illuminate\Http\Request;
	use App\DAO\UserDAO;
	use App\Http\Controllers\Controller;

	//ログイン・ログアウトに関するコントローラークラス
	class LoginController extends Controller {
		//ログイン画面表示処理
		public function goLogin() {
			return view("login");
		}
		
		//ログイン処理
		public function login(Request $request) {
			$isRedirect = false;
			$templatePath = "login";
			$assign = [];
			
			$loginMail = $request->input("mailAdress");
			$loginPw = $request->input("loginPw");
			$validationMsgs = [];
			if(empty($validationMsgs)) {
				$db = DB::connection()->getPdo();
				$userDAO = new UserDAO($db);
				$user = $userDAO->findByUsMail($loginMail);
				if($user == null) {
					$validationMsgs[] = "存在しないメールアドレスです。正しいメールアドレスを入力してください。";
				}
				else {
					$userPw = $user->getUsPassword();
					if(password_verify($loginPw, $userPw)) {
						$id = $user->getId();
						$UsName = $user->getUsName();
						
						
						$session = $request->session();
						$session->put("loginFlg", true);
						$session->put("id", $id);
						$session->put("name", $UsName);
						$session->put("auth", 1);
						$isRedirect = true;
					}
					else {
						$validationMsgs[] = "パスワードが違います。正しいパスワードを入力してください。";
					}
				}
			}
			
			if($isRedirect) {
				$response = redirect("/reports/showList");
			}
			else {
				if(!empty($validationMsgs)) {
					$assign["validationMsgs"] = $validationMsgs;
					$assign["mailAdress"] = $loginMail;
				}
				$response = view("login", $assign);
			}
			return $response;
		}
		
		//ログアウト処理
		public function logout(Request $request) {
			$session = $request->session();
			$session->flush();
			$session->regenerate();
			return redirect("/");
		}
	}