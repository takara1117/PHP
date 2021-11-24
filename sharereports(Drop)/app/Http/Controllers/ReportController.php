<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use App\Functions;
	use App\Entity\User;
	use App\DAO\UserDAO;
	use App\Entity\Reportcate;
	use App\DAO\ReportcateDAO;
	use App\Entity\Report;
	use App\DAO\ReportDAO;
	use App\Exceptions\DataAccessException;
	use App\Http\Controllers\Controller;

	class ReportController extends Controller {
		//レポートリスト画面表示処理
		public function showList(Request $request) {
			$templatePath = "reports.showList";
			$assign = [];

			$db = DB::connection()->getPdo();
			$reportDAO = new ReportDAO($db);
			$reportList = $reportDAO->findAll();
			$assign["reportList"] = $reportList;
			
			$name = session('name');
			$assign["name"] = $name;
		
			return view($templatePath, $assign);
		}
		
		//レポート登録画面表示処理
		public function goAdd(Request $request) {
			$templatePath = "reports.goAdd";
			$assign = [];
			$assign["report"] = new report();

			//作業種類IDの取得
			$db = DB::connection()->getPdo();
			$ReportcateDAO = new ReportcateDAO($db);
			$repocateList = $ReportcateDAO->findAll();
			$assign["repocateList"] = $repocateList;

			$name = session('name');
			$assign["name"] = $name;

			//作業種類ID
			$reportcateDAO = new ReportcateDAO($db);
			$repocateList = $reportcateDAO->findAll();

			$assign["repocateList"] = $repocateList;
			
			return view($templatePath, $assign);
		}

		//レポート登録処理
		public function reportAdd(Request $request) {
			$templatePath = "reports.goAdd";
			$isRedirect = false;
			$assign = [];

			$data = session('name');
			$assign["name"] = $data;

			//作業日
			$year = $_POST['addYear'];
			$month = $_POST['addMonth'];
			$day = $_POST['addDay'];

			$array = array($year, $month, $day);
			$date = implode("-", $array);

			$newRpDate = $request->input("", $date);

			//作業開始時間
			$startHour = $_POST['startHour'];
			$startMinutes = $_POST['startMinutes'];

			$array = array($startHour, $startMinutes);
			$startTime = implode(":", $array);

			$newRpTimeFrom = $request->input("", $startTime);

			//作業終了時間
			$lastHour = $_POST['lastHour'];
			$lastMinutes = $_POST['lastMinutes'];

			$array = array($lastHour, $lastMinutes);
			$lastTime = implode(":", $array);

			$newRpTimeTo = $request->input("", $lastTime);

			//登録日時の取得
			$now = date("Y-m-d H:i:s");

			$id = session('id');
			$userID = $id;
			
			$newRpContent = $request->input("newRpContent");
			$newReportcateId = $request->input("newReportcateId");

			
			$newRpContent = str_replace("　", " ", $newRpContent);
			$newRpContent = trim($newRpContent);


			[$year,$month,$day] = explode("-", $date);

			[$startHour,$startMinutes] = explode(":", $startTime);

			[$lastHour,$lastMinutes] = explode(":", $lastTime);
			
			$report = new report();
			$report->setRpDate($newRpDate);
			$report->setRpTimeFrom($newRpTimeFrom);
			$report->setRpTimeTo($newRpTimeTo);
			$report->setRpContent($newRpContent);
			$report->setRpCreatedAt($now);
			$report->setReportcateId($newReportcateId);
			$report->setUserId($userID);

			$report->setYear($year);
			$report->setMonth($month);
			$report->setDay($day);

			$report->setStartHour($startHour);
			$report->setStartMinutes($startMinutes);

			$report->setLastHour($lastHour);
			$report->setLastMinutes($lastMinutes);
			
			$validationMsgs = [];

			if(empty($newRpContent)) {
				$validationMsgs[] = "作業内容を入力してください。";
			}
			
			$db = DB::connection()->getPdo();
			$reportDAO = new reportDAO($db);
			$reportcateDAO = new ReportcateDAO($db);
			$repocateList = $reportcateDAO->findAll();

			if(empty($validationMsgs)) {
				$reportId = $reportDAO->insert($report);
				if($reportId === -1) {
					throw new DataAccessException("情報登録に失敗しました。もう一度はじめからやり直してください。");
				}
				else {
					$isRedirect = true;
				}
			}
			else {
				$assign["report"] = $report;
				$assign["repocateList"] = $repocateList;
				$assign["validationMsgs"] = $validationMsgs;
			}
			if($isRedirect) {
				$response = redirect("/reports/showList")->with("flashMsg", "レポートID".$reportId."でレポート情報を登録しました。");
			}
			else {
				$response = view($templatePath, $assign);
			}
			return $response;
		}

		//レポート詳細画面表示処理
		public function showDetail(Request $request, int $reportId) {
			$templatePath = "reports.showDetail";
			$assign = [];

			$db = DB::connection()->getPdo();
			$reportDAO = new ReportDAO($db);
			$reportList = $reportDAO->findAll();
			$assign["reportList"] = $reportList;

			$name = session('name');

			//氏名・メールアドレスの取得
			$userDAO = new UserDAO($db);
			$userList = $userDAO->findAll();
			
			//作業種類名の取得
			$reportcateDAO = new ReportcateDAO($db);
			$repocateList = $reportcateDAO->findAll();

			$report = $reportDAO->findByPK($reportId);
			if(empty($report)) {
				throw new DataAccessException("詳細が表示できません。");
			}
			else {
				$assign["report"] = $report;
				$assign["name"] = $name;
				$assign["userList"] = $userList;
				$assign["repocateList"] = $repocateList;
			}
			return view($templatePath, $assign);
		}

		//レポート内容編集画面表示処理
		public function prepareEdit(Request $request, int $reportId) {
			$templatePath = "reports.prepareEdit";
			$assign = [];

			$db = DB::connection()->getPdo();
			$reportDAO = new ReportDAO($db);
			$report = $reportDAO->findByPK($reportId);

			$name = session('name');

			//年月日
			[$year, $month, $day] = explode("-", $report->getRpDate());
			$workDay = [];
			$workDay = [
				"year" => $year,
				"month" => $month,
				"day" => $day
			];

			//作業開始時間
			[$hour, $minutes] = explode(":", $report->getRpTimeFrom());
			$startTime = [];
			$startTime = [
				"hour" => $hour,
				"minutes" => $minutes,
			];
			
			//作業終了時間
			[$hour, $minutes] = explode(":", $report->getRpTimeTo());
			$lastTime = [];
			$lastTime = [
				"hour" => $hour,
				"minutes" => $minutes,
			];

			//作業種類ID
			$reportcateDAO = new ReportcateDAO($db);
			$repocateList = $reportcateDAO->findAll();

			if(empty($report)) {
				throw new DataAccessException("詳細が表示できません。");
			}
			else {
				$assign["report"] = $report;
				$assign["name"] = $name;
				$assign["workDay"] = $workDay;
				$assign["startTime"] = $startTime;
				$assign["lastTime"] = $lastTime;
				$assign["repocateList"] = $repocateList;
			}
			return view($templatePath, $assign);
		}

		//レポート内容編集処理
		public function reportEdit(Request $request) {
			$templatePath = "reports.prepareEdit";
			$isRedirect = false;
			$assign = [];

			//sessionに入ってるname取得
			$data = session('name');
			$assign["name"] = $data;

			//作業日
			$year = $_POST['editYear'];
			$month = $_POST['editMonth'];
			$day = $_POST['editDay'];

			$array = array($year, $month, $day);
			$date = implode("-", $array);

			$editRpDate = $request->input("", $date);

			//作業開始時間
			$startHour = $_POST['startHour'];
			$startMinutes = $_POST['startMinutes'];

			$array = array($startHour, $startMinutes);
			$startTime = implode(":", $array);

			$editRpTimeFrom = $request->input("", $startTime);

			//作業終了時間
			$lastHour = $_POST['lastHour'];
			$lastMinutes = $_POST['lastMinutes'];

			$array = array($lastHour, $lastMinutes);
			$lastTime = implode(":", $array);

			$editRpTimeTo = $request->input("", $lastTime);

			//登録日時の取得
			$now = date("Y-m-d H:i:s");

			//報告者ID
			//sessionに入ってるid取得
			$id = session('id');
			$userID = $id;
			
			$editRepoId = $request->input("editRepoId");
			$editRpContent = $request->input("editRpContent");
			$editReportcateId = $request->input("editReportcateId");

			
			$editRpContent = str_replace("　", " ", $editRpContent);
			$editRpContent = trim($editRpContent);
			
			$report = new report();
			$report->setId($editRepoId);
			$report->setRpDate($editRpDate);
			$report->setRpTimeFrom($editRpTimeFrom);
			$report->setRpTimeTo($editRpTimeTo);
			$report->setRpContent($editRpContent);
			$report->setRpCreatedAt($now);
			$report->setReportcateId($editReportcateId);
			$report->setUserId($userID);
			
			$validationMsgs = [];

			if(empty($editRpContent)) {
				$validationMsgs[] = "作業内容を入力してください。";
			}

			$db = DB::connection()->getPdo();
			$reportDAO = new ReportDAO($db);
			$reportcateDAO = new ReportcateDAO($db);
			$repocateList = $reportcateDAO->findAll();
			//年月日
			[$year, $month, $day] = explode("-", $report->getRpDate());
			$workDay = [];
			$workDay = [
				"year" => $year,
				"month" => $month,
				"day" => $day
			];

			//作業開始時間
			[$hour, $minutes] = explode(":", $report->getRpTimeFrom());
			$startTime = [];
			$startTime = [
				"hour" => $hour,
				"minutes" => $minutes,
			];
			
			//作業終了時間
			[$hour, $minutes] = explode(":", $report->getRpTimeTo());
			$lastTime = [];
			$lastTime = [
				"hour" => $hour,
				"minutes" => $minutes,
			];
			if(empty($validationMsgs)) {
				$result = $reportDAO->update($report);
				if($result) {
					$isRedirect = true;
				}
				else {
					throw new DataAccessException("情報更新に失敗しました。もう一度はじめからやり直してください。");
				}
			}
			else {
				$assign["report"] = $report;
				$assign["workDay"] = $workDay;
				$assign["startTime"] = $startTime;
				$assign["lastTime"] = $lastTime;
				$assign["repocateList"] = $repocateList;
				$assign["validationMsgs"] = $validationMsgs;
			}
			if($isRedirect) {
				$response = redirect("/reports/showDetail/$editRepoId")->with("flashMsg", "レポートID".$editRepoId."のレポート情報を更新しました。");
			}
			else {
				$response = view($templatePath, $assign);
			}
			return $response;
		}
		
		//レポート情報削除確認画面表示処理
		public function confirmDelete(Request $request, int $reportId) {
			$templatePath = "reports.confirmDelete";
			$assign = [];
			
			$db = DB::connection()->getPdo();
			$reportDAO = new ReportDAO($db);
			$report = $reportDAO->findByPK($reportId);

			$name = session('name');

			if(empty($report)) {
				throw new DataAccessException("レポートリスト情報の取得に失敗しました。");
			}
			else {
				$assign["report"] = $report;
				$assign["name"] = $name;
			}
			return view($templatePath, $assign);
		}
		
		//レポート情報削除処理
		public function reportDelete(Request $request) {
			$deleteRepoId = $request->input("deleteRepoId");
			$db = DB::connection()->getPdo();
			$reportDAO = new ReportDAO($db);
			$result = $reportDAO->delete($deleteRepoId);
			if(!$result) {
				throw new DataAccessException("レポートリスト情報の取得に失敗しました。");
			}
			$response = redirect("/reports/showList")->with("flashMsg", "レポートID".$deleteRepoId."のレポート情報を削除しました。");
			return $response;
		}
	}