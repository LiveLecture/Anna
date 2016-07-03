<?php

	class Voting {

		public $id_voting;
		public $gestartet;
		public $kursnummer;
		public $token;
		public $bild;

		public $thema;
		public $frage;
		public $a;
		public $b;
		public $c;
		public $d;

		public $stimmen_a;
		public $stimmen_b;
		public $stimmen_c;
		public $stimmen_d;

		function __construct($data = null) {
			if(is_array($data)) {
				$this->thema = $data ['thema'];
				$this->frage = $data['frage'];
				$this->a = $data['a'];
				$this->b = $data['b'];
				$this->c = $data['c'];
				$this->d = $data['d'];
				$this->kursnummer = $data['kursnummer'];
				$this->token = $data['token'];
				$this->bild = $data['bild'];
			}
		}
	}

?>