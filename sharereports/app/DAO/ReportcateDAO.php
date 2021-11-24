<?php

	namespace App\DAO;

	use PDO;
	use App\Entity\reportCate;

	//reportcatesテーブルへのデータ操作クラス
	class reportcateDAO {
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
		
		//全情報検索
		public function findAll(): array {
			$sql = "SELECT * FROM reportcates ORDER BY rc_order";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute();
			$reportCateList = [];
			while($row = $stmt->fetch()) {
				$id = $row["id"];
				$rcName = $row["rc_name"];
				$rcNote = $row["rc_note"];
				$rcListFlg = $row["rc_list_flg"];
				$rcOrder = $row["rc_order"];
				
				$repoCate = new reportCate();
				$repoCate->setId($id);
				$repoCate->setRcName($rcName);
				$repoCate->setRcNote($rcNote);
				$repoCate->setRcListFlg($rcListFlg);
				$repoCate->setRcOrder($rcOrder);
				$reportCateList[$id] = $repoCate;
			}
			return $reportCateList;
		}

		
	}