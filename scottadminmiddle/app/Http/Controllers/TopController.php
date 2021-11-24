<?php
	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Functions;
	use App\Http\Controllers\Controller;

	/**
	* Topに関するコントローラクラス。
	*/

	class TopController extends Controller {
		/**
		* Top画面表示処理。
		*/
		public function goTop(Request $request) {
			return view("top");
		}
	}