<?php

	require_once("dataManager.php");
	require_once("voting.php");

	class VotingManager extends DataManager {

		protected $pdo;

		public function __construct($connection = null) {
			parent::__construct($connection);
		}

		public function __destruct() {
			parent::__destruct();
		}

		public function findAll($kursnummer) {

			try {
				$stmt = $this->pdo->prepare('SELECT * FROM Voting WHERE kursnummer = :kursnummer');
				$stmt->bindParam(':kursnummer', $kursnummer);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_CLASS, 'Voting');

				return $stmt->fetchAll();
			} catch(PDOException $e) {
				echo("Fehler! Bitten wenden Sie sich an den Administrator...<br>" . $e->getMessage() . "<br>");
				die();
			}
		}

		public function show($votingID) {
			try {
				$stmt = $this->pdo->prepare('SELECT * FROM Voting WHERE id_voting = :voting');
				$stmt->bindParam(':voting', $votingID);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_CLASS, 'Voting');
				$result = $stmt->fetch();

				$err = $stmt->errorInfo();
				if($err[2] !== null) {
					echo "File " . __FILE__ .", line: " . __LINE__ . "<br>";
					print_r($err);
					exit();
				}

				return $result;
			} catch(PDOException $e) {
				echo("Fehler! Bitte wenden Sie sich an den Administrator...<br>" . $e->getMessage() . "<br>");
				die ();
			}
		}

		public function findById_voting($id_voting) {
			try {
				$stmt = $this->pdo->prepare('SELECT * FROM Voting WHERE id_voting = :id_voting');
				$stmt->bindParam(':id_voting', $id_voting);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_CLASS, 'Voting');
				$voting = $stmt->fetch();

				$err = $stmt->errorInfo();
				if($err[2] !== null) {
					echo "File " . __FILE__ .", line: " . __LINE__ . "<br>";
					print_r($err);
					exit();
				}

			} catch(PDOException $e) {
				echo("Fehler! Bitten wenden Sie sich an den Administrator...<br>" . $e->getMessage() . "<br>");
				die();
			}
			if(!$voting) $voting = null;

			return $voting;
		}

		public function save(Voting $voting) {

			if(isset($voting->id_voting)) {
				$this->update($voting);

				return $voting;
			}
			try {
				$stmt = $this->pdo->prepare('
              INSERT INTO Voting
                (thema, frage, a, b, c, d, kursnummer, token, bild)
              VALUES
                (:thema, :frage, :a, :b, :c, :d, :kursnummer, :token, :bild)
            ');
				$stmt->bindParam(':thema', $voting->thema);
				$stmt->bindParam(':frage', $voting->frage);
				$stmt->bindParam(':a', $voting->a);
				$stmt->bindParam(':b', $voting->b);
				$stmt->bindParam(':c', $voting->c);
				$stmt->bindParam(':d', $voting->d);
				$stmt->bindParam(':kursnummer', $voting->kursnummer);
				$stmt->bindParam(':token', $voting->token);
				$stmt->bindParam(':bild', $voting->bild);

				$stmt->execute();
				$voting->id_voting = $this->pdo->lastInsertId();

				$err = $stmt->errorInfo();
				if($err[2] !== null) {
					echo "File " . __FILE__ .", line: " . __LINE__ . "<br>";
					print_r($err);
					exit();
				}
			} catch(PDOException $e) {
				echo("Fehler! Bitten wenden Sie sich an den Administrator...<br>" . $e->getMessage() . "<br>");
				die();
			}

			return $voting;
		}

		private function update(Voting $voting) {

			try {
				$stmt = $this->pdo->prepare('
              UPDATE Voting
              SET thema = :thema,
                  frage = :frage,
                  a = :a,
                  b= :b,
                  c = :c,
                  d = :d,
                  stimmen_a = :stimmen_a,
                  stimmen_b = :stimmen_b,
                  stimmen_c = :stimmen_c,
                  stimmen_d = :stimmen_d,
                  gestartet = :gestartet,
                  token = :token,
                  bild = :bild
              WHERE id_voting = :id_voting
            ');
				$stmt->bindParam(':id_voting', $voting->id_voting);
				$stmt->bindParam(':thema', $voting->thema);
				$stmt->bindParam(':frage', $voting->frage);
				$stmt->bindParam(':a', $voting->a);
				$stmt->bindParam(':b', $voting->b);
				$stmt->bindParam(':c', $voting->c);
				$stmt->bindParam(':d', $voting->d);
				$stmt->bindParam(':stimmen_a', $voting->stimmen_a);
				$stmt->bindParam(':stimmen_b', $voting->stimmen_b);
				$stmt->bindParam(':stimmen_c', $voting->stimmen_c);
				$stmt->bindParam(':stimmen_d', $voting->stimmen_d);
				$stmt->bindParam(':gestartet', $voting->gestartet);
				$stmt->bindParam(':token', $voting->token);
				$stmt->bindParam(':bild', $voting->bild);
				$stmt->execute();

				$err = $stmt->errorInfo();
				if($err[2] !== null) {
					echo "File " . __FILE__ .", line: " . __LINE__ . "<br>";
					print_r($err);
					exit();
				}
			} catch(PDOException $e) {
				echo("Fehler! Bitten wenden Sie sich an den Administrator...<br>" . $e->getMessage() . "<br>");
				die();
			}

			return $voting;
		}

		public function delete(Voting $voting) {
			try {
				$stmt = $this->pdo->prepare('
              DELETE FROM Voting WHERE id_voting= :id_voting
            ');
				$stmt->bindParam(':id_voting', $voting->id_voting);
				$stmt->execute();

				$err = $stmt->errorInfo();
				if($err[2] !== null) {
					echo "File " . __FILE__ .", line: " . __LINE__ . "<br>";
					print_r($err);
					exit();
				}
			} catch(PDOException $e) {
				echo("Fehler! Bitten wenden Sie sich an den Administrator...<br>" . $e->getMessage() . "<br>");
				die();
			}
		}

		public function findByToken($token) {
			try {
				$stmt = $this->pdo->prepare('SELECT * FROM Voting WHERE token = :token');
				$stmt->bindParam(':token', $token);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_CLASS, 'Voting');
				$voting = $stmt->fetch();

				$err = $stmt->errorInfo();
				if($err[2] !== null) {
					echo "File " . __FILE__ .", line: " . __LINE__ . "<br>";
					print_r($err);
					exit();
				}
			} catch(PDOException $e) {
				echo("Fehler! Bitten wenden Sie sich an den Administrator...<br>" . $e->getMessage() . "<br>");
				die();
			}
			if(!$voting) $voting = null;

			return $voting;
		}
	}


?>