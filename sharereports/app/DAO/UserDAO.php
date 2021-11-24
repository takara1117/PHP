<?php

	namespace App\DAO;

	use PDO;
	use App\Entity\User;

	//usersテーブルへのデータ操作クラス
	class UserDAO {
		//PDO DB接続オブジェクト
		private PDO $db;
		
		//コンストラクタ
		public function __construct(PDO $db) {
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->db = $db;
		}

		//メールアドレスによる検索
		public function findByUsMail(string $usMail): ?User {
			$sql = "SELECT * FROM users WHERE us_mail = :us_mail";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":us_mail", $usMail, PDO::PARAM_INT);
			$result = $stmt->execute();
			$user = null;
			if($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$id = $row["id"];
				$usMail = $row["us_mail"];
				$usName = $row["us_name"];
				$usPassword = $row["us_password"];
				$usAuth = $row["us_auth"];
				
				$user = new User();
				$user->setId($id);
				$user->setUsMail($usMail);
				$user->setUsName($usName);
				$user->setUsPassword($usPassword);
				$user->setUsAuth($usAuth);
			}
			return $user;
		}

		public function findAll(): array {
			$sql = "SELECT * FROM users ORDER BY id";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute();
			$userList = [];
			while($row = $stmt->fetch()) {
				$id = $row["id"];
				$usMail = $row["us_mail"];
				$usName = $row["us_name"];
				$usPassword = $row["us_password"];
				$usAuth = $row["us_auth"];
				
				$user = new User();
				$user->setId($id);
				$user->setUsMail($usMail);
				$user->setUsName($usName);
				$user->setUsPassword($usPassword);
				$user->setUsAuth($usAuth);
				$userList[$id] = $user;
			}
			return $userList;
		}

		//主キーidによる検索
		public function findByPK(int $id): ?User {
			$sql = "SELECT * FROM users WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id, PDO::PARAM_INT);
			$result = $stmt->execute();
			$user = null;
			if($result && $row = $stmt->fetch()) {
				$id = $row["id"];
				$usMail = $row["us_mail"];
				$usName = $row["us_name"];
				$usPassword = $row["us_password"];
				$usAuth = $row["us_auth"];
				
				$user = new User();
				$user->setId($id);
				$user->setUsMail($usMail);
				$user->setUsName($usName);
				$user->setUsPassword($usPassword);
				$user->setUsAuth($usAuth);
				$userList[$id] = $user;
			}
			return $user;
		}
	}