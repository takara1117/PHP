<?php

	namespace App\Entity;
	
	//ユーザエンティティクラス
	class User {
		//主キーのid
		private ?int $id = null;
		//ログインID
		private ?string $login = "";
		//パスワード
		private ?string $passwd = "";
		//姓
		private ?string $nameLast = "";
		//名
		private ?string $nameFirst = "";
		//メールアドレス
		private ?string $mail = "";
		
		//以下アクセサメソッド
		public function getId(): ?int {
			return $this->id;
		}
		public function setId(?int $id): void {
			$this->id = $id;
		}
		public function getLogin(): ?string {
			return $this->login;
		}
		public function setLogin(?string $login): void {
			$this->login = $login;
		}
		public function getPasswd(): ?string {
			return $this->passwd;
		}
		public function setPasswd(?string $passwd): void {
			$this->passwd = $passwd;
		}
		public function getNameLast(): ?string {
			return $this->nameLast;
		}
		public function setNameLast(?string $nameLast): void {
			$this->nameLast = $nameLast;
		}
		public function getNameFirst(): ?string {
			return $this->nameFirst;
		}
		public function setNameFirst(?string $nameFirst): void {
			$this->nameFirst = $nameFirst;
		}
		public function getMail(): ?string {
			return $this->mail;
		}
		public function setMail(?string $mail): void {
			$this->mail = $mail;
		}
	}