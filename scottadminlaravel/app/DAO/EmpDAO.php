<?php

    namespace App\DAO;

	use PDO;
	use App\Entity\Emp;

	//empテーブルへのデータ操作クラス
    class EmpDAO {
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
        public function findByPK(int $id): ?Emp {
            $sql = "SELECT * FROM emps WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $emp = null;
            if($result && $row = $stmt->fetch()) {
                $idDb = $row["id"];
                $emNo = $row["em_no"];
                $emName = $row["em_name"];
                $emJob = $row["em_job"];
                $emMgr = $row["em_mgr"];
                $emHiredate = $row["em_hiredate"];
                $emSal = $row["em_sal"];
                $emDpId = $row["dept_id"];

                $emp = new Emp();
                $emp->setId($idDb);
                $emp->setEmNo($emNo);
                $emp->setEmName($emName);
                $emp->setEmJob($emJob);
                $emp->setEmMgr($emMgr);
                $emp->setEmHiredate($emHiredate);
                $emp->setEmSal($emSal);
                $emp->setEmDpId($emDpId);
            }
            return $emp;
        }

        //従業員番号による検索
        public function findByEmNo(int $emNo): ?Emp {
            $sql = "SELECT * FROM emps WHERE em_no = :em_no";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":em_no", $emNo, PDO::PARAM_INT);
            $result = $stmt->execute();
            $emp = null;
            if($result && $row = $stmt->fetch()) {
                $id = $row["id"];
                $emNo = $row["em_no"];
                $emName = $row["em_name"];
                $emJob = $row["em_job"];
                $emMgr = $row["em_mgr"];
                $emHiredate = $row["em_hiredate"];
                $emSal = $row["em_sal"];
                $emDpId = $row["dept_id"];

                $emp = new Emp();
                $emp->setId($id);
                $emp->setEmNo($emNo);
                $emp->setEmName($emName);
                $emp->setEmJob($emJob);
                $emp->setEmMgr($emMgr);
                $emp->setEmHiredate($emHiredate);
                $emp->setEmSal($emSal);
                $emp->setEmDpId($emDpId);
            }
            return $emp;
        }
        
        //全従業員情報検索
        public function findAll(): array {
            $sql = "SELECT * FROM emps ORDER BY em_no";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute();
            $empList = [];
            while($row = $stmt->fetch()) {
                $id = $row["id"];
                $emNo = $row["em_no"];
                $emName = $row["em_name"];
                $emJob = $row["em_job"];
                $emMgr = $row["em_mgr"];
                $emHiredate = $row["em_hiredate"];
                $emSal = $row["em_sal"];
                $emDpId = $row["dept_id"];

                $emp = new Emp();
                $emp->setId($id);
                $emp->setEmNo($emNo);
                $emp->setEmName($emName);
                $emp->setEmJob($emJob);
                $emp->setEmMgr($emMgr);
                $emp->setEmHiredate($emHiredate);
                $emp->setEmSal($emSal);
                $emp->setEmDpId($emDpId);
                $empList[$emNo] = $emp;
            }
            return $empList;
        }
    
        //従業員情報登録
        public function insert(Emp $emp): int {
            $sqlInsert = "INSERT INTO emps (em_no, em_name, em_job, em_mgr, em_hiredate, em_sal, dept_id) VALUES (:em_no, :em_name, :em_job, :em_mgr, :em_hiredate, :em_sal, :dept_id)";
            $stmt = $this->db->prepare($sqlInsert);
            $stmt->bindValue(":em_no", $emp->getEmNo(), PDO::PARAM_INT);
            $stmt->bindValue(":em_name", $emp->getEmName(), PDO::PARAM_STR);
            $stmt->bindValue(":em_job", $emp->getEmJob(), PDO::PARAM_STR);
            $stmt->bindValue(":em_mgr", $emp->getEmMgr(), PDO::PARAM_INT);
            $stmt->bindValue(":em_hiredate", $emp->getEmHiredate(), PDO::PARAM_STR);
            $stmt->bindValue(":em_sal", $emp->getEmSal(), PDO::PARAM_STR);
            $stmt->bindValue(":dept_id", $emp->getEmDpId(), PDO::PARAM_STR);
            $result = $stmt->execute();
            if($result) {
                $emId = $this->db->lastInsertId();
            }
            else {
                $emId = -1;
            }
            return  $emId;
        }
        
        //従業員情報更新
        public function update(Emp $emp): bool{
            $sqlUpdate = "UPDATE emps SET em_no = :em_no, em_name = :em_name, em_job = :em_job, em_mgr = :em_mgr, em_hiredate = :em_hiredate, em_sal = :em_sal, dept_id = :dept_id WHERE id = :id";
            $stmt = $this->db->prepare($sqlUpdate);
            $stmt->bindValue(":em_no", $emp->getEmNo(), PDO::PARAM_INT);
            $stmt->bindValue(":em_name", $emp->getEmName(), PDO::PARAM_STR);
            $stmt->bindValue(":em_job", $emp->getEmJob(), PDO::PARAM_STR);
            $stmt->bindValue(":em_mgr", $emp->getEmMgr(), PDO::PARAM_INT);
            $stmt->bindValue(":em_hiredate", $emp->getEmHiredate(), PDO::PARAM_STR);
            $stmt->bindValue(":em_sal", $emp->getEmSal(), PDO::PARAM_STR);
            $stmt->bindValue(":dept_id", $emp->getEmDpId(), PDO::PARAM_STR);
            $stmt->bindValue(":id", $emp->getId(), PDO::PARAM_INT);
            $result = $stmt->execute();
            return $result;
        }
        
        //従業員情報削除
        public function delete(int $id): bool {
            $sql = "DELETE FROM emps WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            return $result;
        }
    }
