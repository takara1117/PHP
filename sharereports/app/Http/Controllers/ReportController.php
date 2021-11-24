<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use App\DAO\UserDAO;
	use App\DAO\ReportcateDAO;
	use App\Entity\Report;
	use App\DAO\ReportDAO;
	use App\Exceptions\DataAccessException;
	use App\Http\Controllers\Controller;

	//レポートリスト情報管理に関するコントローラクラス
	class ReportController extends Controller {
		//レポートリスト画面表示処理
		public function showList(Request $request) {
			$templatePath = "reports.list";
			$assign = [];

			//sessionに入ってるnameの取得
			$data = session('name');
			$assign["name"] = $data;

			$db = DB::connection()->getPdo();
			$reportDAO = new ReportDAO($db);
			$reportList = $reportDAO->findAll();
			$assign["reportList"] = $reportList;
		
			return view($templatePath, $assign);
		}
		
		//レポート登録画面表示処理
		public function goAdd(Request $request) {
			$templatePath = "reports.add";
			$assign = [];
			$assign["report"] = new report();

			//sessionに入ってるname取得
			$data = session('name');
			$assign["name"] = $data;

			//作業種類ID
			$db = DB::connection()->getPdo();
			$reportcateDAO = new ReportcateDAO($db);
			$repocateList = $reportcateDAO->findAll();

			$assign["repocateList"] = $repocateList;
			
			return view($templatePath, $assign);
		}

		//レポート登録処理
		public function reportAdd(Request $request) {
			$templatePath = "reports.add";
			$isRedirect = false;
			$assign = [];

			//報告者ID
			$id = session('id');
			$userID = $id;

			//sessionに入ってるnameの取得
			$data = session('name');
			$assign["name"] = $data;

			//登録日時の取得
			$now = date("Y-m-d H:i:s");
			
			$newRpDate = $request->input("newRpDate");
			$newRpTimeFrom = $request->input("newRpTimeFrom");
			$newRpTimeTo = $request->input("newRpTimeTo");
			$newRpContent = $request->input("newRpContent");
			$newReportcateId = $request->input("newReportcateId");

			
			$newRpContent = str_replace("　", " ", $newRpContent);
			$newRpContent = trim($newRpContent);
			
			$report = new report();
			$report->setRpDate($newRpDate);
			$report->setRpTimeFrom($newRpTimeFrom);
			$report->setRpTimeTo($newRpTimeTo);
			$report->setRpContent($newRpContent);
			$report->setRpCreatedAt($now);
			$report->setReportcateId($newReportcateId);
			$report->setUserId($userID);
			
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
			$templatePath = "reports.detail";
			$assign = [];

			//sessionに入ってるname取得
			$data = session('name');
			$assign["name"] = $data;

			$db = DB::connection()->getPdo();
			$reportDAO = new ReportDAO($db);
			$reportList = $reportDAO->findAll();
			$assign["reportList"] = $reportList;

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
				$assign["userList"] = $userList;
				$assign["repocateList"] = $repocateList;
			}
			return view($templatePath, $assign);
		}

		//レポート内容編集画面表示処理
		public function prepareEdit(Request $request, int $reportId) {
			$templatePath = "reports.edit";
			$assign = [];

			//sessionに入ってるname取得
			$data = session('name');
			$assign["name"] = $data;

			$db = DB::connection()->getPdo();
			$reportcateDAO = new ReportcateDAO($db);
			$repocateList = $reportcateDAO->findAll();

			$reportDAO = new ReportDAO($db);
			$report = $reportDAO->findByPK($reportId);
			if(empty($report)) {
				throw new DataAccessException("詳細が表示できません。");
			}
			else {
				$assign["report"] = $report;
				$assign["repocateList"] = $repocateList;
			}
			return view($templatePath, $assign);
		}

		//レポート内容編集処理
		public function reportEdit(Request $request) {
			$templatePath = "reports.edit";
			$isRedirect = false;
			$assign = [];

			//sessionに入ってるname取得
			$data = session('name');
			$assign["name"] = $data;

			//登録日時の取得
			$now = date("Y-m-d H:i:s");

			//報告者ID
			//sessionに入ってるid取得
			$id = session('id');
			$userID = $id;
			
			$editRepoId = $request->input("editRepoId");
			$editRpDate = $request->input("editRpDate");
			$editRpTimeFrom = $request->input("editRpTimeFrom");
			$editRpTimeTo = $request->input("editRpTimeTo");
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

			//sessionに入ってるname取得
			$data = session('name');
			$assign["name"] = $data;

			if(empty($report)) {
				throw new DataAccessException("レポートリスト情報の取得に失敗しました。");
			}
			else {
				$assign["report"] = $report;
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