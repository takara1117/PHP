<?php

    namespace App\Entity;

    //部門エンティティクラス
    class Emp {
        //従業員ID
        private ?int $id = null;
        
        //従業員番号
        private ?int $emNo = null;
    
        //従業員名
        private ?string $emName = "";
        
        //役職
        private ?string $emJob = "";

        //上司番号
        private ?string $emMgr = "";

        //雇用日
        private ?string $emHiredate = "";

        //給与
        private ?string $emSal = "";

        //所属部門ID
        private ?string $emDpId = "";


        //アクセサメソッド
        //従業員ID
        public function getId(): ?int {
			return $this->id;
		}
        public function setId(int $id): void {
            $this->id = $id;
        }

        //従業員番号
        public function getEmNo(): ?int {
            return $this->emNo;
        }
        public function setEmNo(int $emNo): void {
            $this->emNo = $emNo;
        }

        //従業員名
        public function getEmName(): ?string {
            return $this->emName;
        }
        public function setEmName(string $emName): void {
            $this->emName = $emName;
        }

        //役職
        public function getEmJob(): ?string {
            return $this->emJob;
        }
        public function setEmJob(?string $emJob): void {
            $this->emJob = $emJob;
        }

        //上司番号
        public function getEmMgr(): ?string {
            return $this->emMgr;
        }
        public function setEmMgr(?string $emMgr): void {
            $this->emMgr = $emMgr;
        }

        //雇用日
        public function getEmHiredate(): ?string {
            return $this->emHiredate;
        }
        public function setEmHiredate(?string $emHiredate): void {
            $this->emHiredate = $emHiredate;
        }

        //給与
        public function getEmSal(): ?string {
            return $this->emSal;
        }
        public function setEmSal(?string $emSal): void {
            $this->emSal = $emSal;
        }

        //所属部門ID
        public function getEmDpId(): ?string {
            return $this->emDpId;
        }
        public function setEmDpId(?string $emDpId): void {
            $this->emDpId = $emDpId;
        }
    }
