<?php

	namespace App;
	
	use Illuminate\Http\Request;
	
	//共通処理が書かれたクラス
	class Functions {
		public static function loginCheck(Request$request): bool {
			$result = false;
			$session = $request->session();
			if(!$session->has("loginFlg") || $session->get("loginFlg") == false || !$session->has("id") || !$session->has("name") || !$session->has("auth")) {
				$result =  true;
			}
			return$result;
		}
	}