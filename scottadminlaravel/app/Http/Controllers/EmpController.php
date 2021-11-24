<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use App\Functions;
	use App\Entity\Emp;
	use App\DAO\EmpDAO;
	use App\Entity\Dept;
	use App\DAO\DeptDAO;
	use App\Http\Controllers\Controller;

	//従業員情報管理に関するコントローラクラス
	class EmpController extends Controller {
		//従業員リスト画面表示処理
		public function showEmpList(Request $request) {
			$templatePath = "emp.empList";
			$assign = [];
			if(Functions::loginCheck($request)) {
				$validationMsgs[] = "ログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";
				$assign["validationMsgs"] = $validationMsgs;
				$templatePath = "login";
			}
			else {
				$db = DB::connection()->getPdo();
				$empDAO = new EmpDAO($db);
				$empList = $empDAO->findAll();
				$assign["empList"] = $empList;
			}
			return view($templatePath, $assign);
		}

		//従業員情報登録画面表示処理
		public function goEmpAdd(Request $request) {
			$templatePath = "emp.empAdd";
			$assign = [];
			if(Functions::loginCheck($request)) {
				$validationMsgs[] = "ログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";
				$assign["validationMsgs"] = $validationMsgs;
				$templatePath = "login";
			}
			else {
				$assign["emp"] = new Emp();
				$assign["dept"] = new Dept();

				$db = DB::connection()->getPdo();
				$empDAO = new EmpDAO($db);
				$deptDAO = new DeptDAO($db);

				//従業員番号と上司名の取得
				$empList = $empDAO->findAll();
				$assign["empList"] = $empList;

				//年月日
				$empDate = [];
				$empDate = [
					"year" => "----",
					"month" => "--",
					"day" => "--"
				];
				$assign["empDate"] = $empDate;

				//所属部門IDの取得
				$deptList = $deptDAO->findAll();
				$assign["deptList"] = $deptList;
			}
			return view($templatePath, $assign);
		}

		//従業員情報登録処理
		public function empAdd(Request $request) {
			$templatePath = "emp.empAdd";
			$isRedirect = false;
			$assign = [];
			if(Functions::loginCheck($request)) {
				$validationMsgs[] = "ログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";
				$assign["validationMsgs"] = $validationMsgs;
				$templatePath = "login";
			}
			else {
				//年月日
				$addYear = $_POST['addEmYear'];
				$addMonth = $_POST['addEmMonth'];
				$addDay = $_POST['addEmDay'];

				$array = array($addYear, $addMonth, $addDay);
				$addHiredate = implode("-", $array);

				$addEmHiredate = $request->input("", $addHiredate);
				
				$addEmNo = $request->input("addEmNo");
				$addEmName = $request->input("addEmName");
				$addEmJob = $request->input("addEmJob");
				$addEmMgr = $request->input("addEmMgr");
				$addEmSal = $request->input("addEmSal");
				$addEmDpId = $request->input("addEmDpId");

				$addEmName = str_replace("　", " ", $addEmName);
				$addEmJob = str_replace("　", " ", $addEmJob);
				//$addEmSal = str_replace("", " ","　", $addEmSal);

				$addEmName = trim($addEmName);
				$addEmJob = trim($addEmJob);
				//$addEmSal = trim($addEmSal);
				
				$emp = new Emp();
				$emp->setEmNo($addEmNo);
				$emp->setEmName($addEmName);
				$emp->setEmJob($addEmJob);
				$emp->setEmMgr($addEmMgr);
				$emp->setEmHiredate($addEmHiredate);
				$emp->setEmSal($addEmSal);
				$emp->setEmDpId($addEmDpId);
				
				$validationMsgs = [];
				
				if(empty($addEmName)) {
					$validationMsgs[] = "従業員名の入力は必須です。";
				}
				if(empty($addEmJob)) {
					$validationMsgs[] = "役職の入力は必須です。";
				}
				if(empty($addEmSal)) {
					$validationMsgs[] = "給料の入力は必須です。";
				}

				$db = DB::connection()->getPdo();
				$empDAO = new EmpDAO($db);
				$deptDAO = new DeptDAO($db);

				$empList = $empDAO->findAll();
				$deptList = $deptDAO->findAll();

				[$emYear, $emMonth, $emDay] = explode("-", $addEmHiredate);
				$empDate = [];
				$empDate = [
					"year" => $emYear,
					"month" => $emMonth,
					"day" => $emDay
				];

				$empDB = $empDAO->findByEmNo($emp->getEmNo());

				if(!empty($empDB)) {
					$validationMsgs[] = "その従業員番号はすでに使われています。別のものを指定してください。";}
					if(empty($validationMsgs)) {
						$emId = $empDAO->insert($emp);
						if($emId === -1) {
							$assign["errorMsg"] = "従業員情報登録に失敗しました。もう一度はじめからやり直してください。";
							$templatePath = "error";
						}
						else {
							$isRedirect = true;
						}
					}
					else {
						$assign["emp"] = $emp;
						$assign["empList"] = $empList;
						$assign["deptList"] = $deptList;
						$assign["empDate"] = $empDate;
						$assign["validationMsgs"] = $validationMsgs;
					}
				}
				if($isRedirect) {
					$response = redirect("/emp/showEmpList")->with("flashMsg", "従業員ID".$emId."で従業員情報を登録しました。");
				}
				else {
					$response = view($templatePath, $assign);
				}
				return $response;
			}

			//従業員情報編集画面表示処理
			public function prepareEmpEdit(Request $request, int $emId) {
				$templatePath = "emp.empEdit";
				$assign = [];
				if(Functions::loginCheck($request)) {
					$validationMsgs[] = "ログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";
					$assign["validationMsgs"] = $validationMsgs;
					$templatePath = "login";
				}
				else {
					$db = DB::connection()->getPdo();
					$empDAO = new EmpDAO($db);
					$deptDAO = new DeptDAO($db);

					$emp = $empDAO->findByPK($emId);

					if(empty($emp)) {
						$assign["errorMsg"] = "従業員情報の取得に失敗しました。";
						$templatePath = "error";
					}
					else {
						$assign["emp"] = $emp;

						//従業員番号と上司名の取得
						$empList = $empDAO->findAll();
						$assign["empList"] = $empList;

						//年月日
						[$emYear, $emMonth, $emDay] = explode("-", $emp->getEmHiredate());
						$empDate = [];
						$empDate = [
							"year" => $emYear,
							"month" => $emMonth,
							"day" => $emDay
						];
						$assign["empDate"] = $empDate;

						//所属部門IDの取得
						$deptList = $deptDAO->findAll();
						$assign["deptList"] = $deptList;
					}
				}
				return view($templatePath, $assign);
			}

			//従業員情報編集処理
			public function empEdit(Request $request) {
				$templatePath = "emp.empEdit";
				$isRedirect = false;
				$assign = [];
				if(Functions::loginCheck($request)) {
					$validationMsgs[] = "ログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";
					$assign["validationMsgs"] = $validationMsgs;
					$templatePath = "login";
				}
				else {
					//年月日
					$editYear = $_POST['editEmYear'];
					$editMonth = $_POST['editEmMonth'];
					$editDay = $_POST['editEmDay'];

					$array = array($editYear, $editMonth, $editDay);
					$editHiredate = implode("-", $array);

					$editEmHiredate = $request->input("", $editHiredate);

					$editEmId = $request->input("editEmId");
					$editEmNo = $request->input("editEmNo");
					$editEmName = $request->input("editEmName");
					$editEmJob = $request->input("editEmJob");
					$editEmMgr = $request->input("editEmMgr");
					$editEmSal = $request->input("editEmSal");
					$editEmDpId = $request->input("editEmDpId");

					$editEmName = str_replace("　", " ", $editEmName);
					$editEmJob = str_replace("　", " ", $editEmJob);
					//$editEmSal = str_replace("", " ", $editEmSal);

					$editEmName = trim($editEmName);
					$editEmJob = trim($editEmJob);
					//$editEmSal = trim($editEmSal);
					
					$emp = new Emp();
					$emp->setId($editEmId);
					$emp->setEmNo($editEmNo);
					$emp->setEmName($editEmName);
					$emp->setEmJob($editEmJob);
					$emp->setEmMgr($editEmMgr);
					$emp->setEmHiredate($editEmHiredate);
					$emp->setEmSal($editEmSal);
					$emp->setEmDpId($editEmDpId);
					
					$validationMsgs = [];
					
					if(empty($editEmName)) {
					$validationMsgs[] = "従業員名の入力は必須です。";
					}
					if(empty($editEmJob)) {
						$validationMsgs[] = "役職の入力は必須です。";
					}
					if(empty($editEmSal)) {
						$validationMsgs[] = "給料の入力は必須です。";
					}
					
					$db = DB::connection()->getPdo();
					$empDAO = new EmpDAO($db);
					$deptDAO = new DeptDAO($db);

					$empList = $empDAO->findAll();
					$deptList = $deptDAO->findAll();

					[$emYear, $emMonth, $emDay] = explode("-", $editEmHiredate);
					$empDate = [];
					$empDate = [
						"year" => $emYear,
						"month" => $emMonth,
						"day" => $emDay
					];
					$empDB = $empDAO->findByEmNo($emp->getEmNo());
					if(!empty($empDB) && $empDB->getId() != $editEmId) {
						$validationMsgs[] = "その従業員番号はすでに使われています。別のものを指定してください。";
					}
					if(empty($validationMsgs)) {
						$result = $empDAO->update($emp);
						if($result) {
							$isRedirect = true;
						}
						else {
							$assign["errorMsg"] = "従業員情報更新に失敗しました。もう一度はじめからやり直してください。";
							$templatePath = "error";
						}
					}
					else {
						$assign["emp"] = $emp;
						$assign["empList"] = $empList;
						$assign["deptList"] = $deptList;
						$assign["empDate"] = $empDate;
						$assign["validationMsgs"] = $validationMsgs;
					}
				}
				if($isRedirect) {
					$response = redirect("/emp/showEmpList")->with("flashMsg", "従業員ID".$editEmId."の従業員情報を更新しました。");
				}
				else {
					$response = view($templatePath, $assign);
				}
				return $response;
			}

			//従業員情報削除確認画面表示処理
			public function confirmEmpDelete(Request $request, int $emId) {
				$templatePath = "emp.empConfirmDelete";
				$assign = [];
				if(Functions::loginCheck($request)) {
					$validationMsgs[] = "ログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";
					$assign["validationMsgs"] = $validationMsgs;
					$templatePath = "login";
				}
				else {
					$db = DB::connection()->getPdo();
					$empDAO = new EmpDAO($db);
					$emp = $empDAO->findByPK($emId);
					if(empty($emp)) {
						$assign["errorMsg"] = "従業員情報の取得に失敗しました。";
						$templatePath = "error";
					}
					else {
						$assign["emp"] = $emp;
					}
				}
				return view($templatePath, $assign);
			}

			//従業員情報削除処理
			public function empDelete(Request $request) {
				$templatePath = "error";
				$isRedirect = false;
				$assign = [];
				if(Functions::loginCheck($request)) {
					$validationMsgs[] = "ログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";
					$assign["validationMsgs"] = $validationMsgs;
					$templatePath = "login";
				}
				else {
					$deleteEmpId = $request->input("deleteEmpId");
					$db = DB::connection()->getPdo();
					$empDAO = new EmpDAO($db);
					$result = $empDAO->delete($deleteEmpId);
					if($result) {
						$isRedirect = true;
					}
					else {
						$assign["errorMsg"] = "従業員情報削除に失敗しました。もう一度はじめからやり直してください。";
					}
				}
				if($isRedirect) {
					$response = redirect("/emp/showEmpList")->with("flashMsg", "従業員ID".$deleteEmpId."の従業員情報を削除しました。");
				}
				else {
					$response = view($templatePath, $assign);
				}
				return$response;
			}
		}