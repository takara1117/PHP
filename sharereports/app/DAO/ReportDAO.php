<?php

	namespace App\DAO;

	use PDO;
	use App\Entity\Report;

	//reportsテーブルへのデータ操作クラス
	class reportDAO {
		//PDO DB接続オブジェクト
		private PDO $db;
		//コンストラクタ
		//PDO $db DB接続オブジェクト
		public function __construct(PDO $db) {
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->db = $db;
		}
		
		//主キーidによる検索
		public function findByPK(int $id): ?Report {
			$sql = "SELECT * FROM reports WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id, PDO::PARAM_INT);
			$result = $stmt->execute();
			$report = null;
			if($result && $row = $stmt->fetch()) {
				$idDb = $row["id"];
				$rpDate = $row["rp_date"];
				$rpTimeFrom = $row["rp_time_from"];
				$rpTimeTo = $row["rp_time_to"];
				$rpContent = $row["rp_content"];
				$rpCreatedAt = $row["rp_created_at"];
				$reportcateId = $row["reportcate_id"];
				$userId = $row["user_id"];
				
				$report = new Report();
				$report->setId($idDb);
				$report->setRpDate($rpDate);
				$report->setRpTimeFrom($rpTimeFrom);
				$report->setRpTimeTo($rpTimeTo);
				$report->setRpContent($rpContent);
				$report->setRpCreatedAt($rpCreatedAt);
				$report->setReportcateId($reportcateId);
				$report->setUserId($userId);
			}
			return $report;
		}
		
		//レポート一覧検索
		public function findAll(): array {
			$sql = "SELECT * FROM reports ORDER BY rp_date DESC";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute();
			$reportList = [];
			while($row = $stmt->fetch()) {
				$id = $row["id"];
				$rpDate = $row["rp_date"];
				$rpTimeFrom = $row["rp_time_from"];
				$rpTimeTo = $row["rp_time_to"];
				$rpContent = $row["rp_content"];
				$rpCreatedAt = $row["rp_created_at"];
				$reportcateId = $row["reportcate_id"];
				$userId = $row["user_id"];
				
				$report = new Report();
				$report->setId($id);
				$report->setRpDate($rpDate);
				$report->setRpTimeFrom($rpTimeFrom);
				$report->setRpTimeTo($rpTimeTo);
				$report->setRpContent($rpContent);
				$report->setRpCreatedAt($rpCreatedAt);
				$report->setReportcateId($reportcateId);
				$report->setUserId($userId);
				$reportList[$id] = $report;
			}
			return $reportList;
		}

		//レポート新規作成
		public function insert(Report $report): int {
			$sqlInsert = "INSERT INTO reports (rp_date, rp_time_from, rp_time_to, rp_content, rp_created_at, reportcate_id, user_id) VALUES (:rp_date, :rp_time_from, :rp_time_to, :rp_content, :rp_created_at, :reportcate_id, :user_id)";
			$stmt = $this->db->prepare($sqlInsert);
			$stmt->bindValue(":rp_date", $report->getRpDate(), PDO::PARAM_INT);
			$stmt->bindValue(":rp_time_from", $report->getRpTimeFrom(), PDO::PARAM_INT);
			$stmt->bindValue(":rp_time_to", $report->getRpTimeTo(), PDO::PARAM_INT);
			$stmt->bindValue(":rp_content", $report->getRpContent(), PDO::PARAM_INT);
			$stmt->bindValue(":rp_created_at", $report->getRpCreatedAt(), PDO::PARAM_INT);
			$stmt->bindValue(":reportcate_id", $report->getReportcateId(), PDO::PARAM_INT);
			$stmt->bindValue(":user_id", $report->getUserId(), PDO::PARAM_INT);
			$result = $stmt->execute();
			if($result) {
				$reportId = $this->db->lastInsertId();
			}
			else {
				$reportId = -1;
			}
			return $reportId;
		}

		//レポート情報更新
		public function update(Report $report): bool {
			$sqlUpdate = "UPDATE reports SET rp_date = :rp_date, rp_time_from = :rp_time_from, rp_time_to = :rp_time_to, rp_content = :rp_content, rp_created_at = :rp_created_at, reportcate_id = :reportcate_id, user_id = :user_id  WHERE id = :id";
			$stmt = $this->db->prepare($sqlUpdate);
			$stmt->bindValue(":rp_date", $report->getRpDate(), PDO::PARAM_INT);
			$stmt->bindValue(":rp_time_from", $report->getRpTimeFrom(), PDO::PARAM_INT);
			$stmt->bindValue(":rp_time_to", $report->getRpTimeTo(), PDO::PARAM_INT);
			$stmt->bindValue(":rp_content", $report->getRpContent(), PDO::PARAM_INT);
			$stmt->bindValue(":rp_created_at", $report->getRpCreatedAt(), PDO::PARAM_INT);
			$stmt->bindValue(":reportcate_id", $report->getReportcateId(), PDO::PARAM_INT);
			$stmt->bindValue(":user_id", $report->getUserId(), PDO::PARAM_INT);
			$stmt->bindValue(":id", $report->getId(), PDO::PARAM_INT);
			$result = $stmt->execute();
			return $result;
		}

		//レポート情報削除
		public function delete(int $id): bool {
			$sql = "DELETE FROM reports WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id, PDO::PARAM_INT);
			$result = $stmt->execute();
			return $result;
		}
	}