<?php

/* CLASSE UTENTE */

/* Utente::cript($value) -> RITORNA LA STRINGA CRIPTATA */

include_once "query_server.php";

$span="paperino";
$numeroCriptazioni=100;

//testCreazione();

function testCreazione() {
	$utente=new Utente("username", "password", "password", "email", "", "", "13/02/1990", "", "", "");
	echo $utente;
}

class Utente {
	private $username="";
	private $password="";
	private $email="";
	private $nome=NULL;
	private $cognome=NULL;
	private $dataNascita="";
	private $immagine=NULL;
	private $descrizione=NULL;
	private $dataIscrizione="";
	private $provincia="";
	private $errore="";

	public function __toString() {
		return $this->errore;
	}

	function __construct($user, $pwd, $confPwd, $email, $nome, $cognome, $dataN, $imm, $desc, $prov) {
		$erroreUser=$this->setUsername($user);
		$errorePwd=$this->setPassword($pwd, $confPwd);
		$erroreEmail=$this->setEmail($email);
		$erroreNome=$this->setNome($nome);
		$erroreCognome=$this->setCognome($cognome);
		$erroreDataN=$this->setDataNascita($dataN);
		$erroreImmagine=$this->setImmagine($imm);
		$erroreDescrizione=$this->setDescrizione($desc);
		$this->setDataIscrizione();
		$this->setProvincia($prov);
		$this->errore=$erroreUser.$errorePwd.$erroreEmail.$erroreNome.$erroreCognome.$erroreDataN.$erroreImmagine.$erroreDescrizione;
		$this->errore=$this->errore ? "<ul class=\"errReg\">".$this->errore."</ul>" : "";
	}

	private function setUsername($value) {
		$errore="";
		if ($value=="") {
			$errore="<li>Username obbligatorio</li>";
		} elseif (strlen($value)>25) {
			$errore="<li>Username troppo lungo</li>";
		} else {
			//COLLEGAMENTO AL DATABASE E CONTROLLO CHE NON SIA GIA' USATO
			$presente=false;
			$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
			try {
				if ($connessione->connect_errno) {
					throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
				} else {
					$query="SELECT username FROM Utenti WHERE username=\"".$value."\";";
					if (!$result=$connessione->query($query)) {
						echo "Query non valida: ".$connessione->error.".";
						$presente=true;
					} else {
						if ($result->num_rows>0) {
							$presente=true;
						}
					}
					$connessione->close();
				}
			} catch (Exception $e){
				echo "Errore: inserimento fallito (".$e->getMessage().").";
				$presente=true;
			}
			if ($presente) {
				$errore="<li>Username già in uso</li>";
			} else {
				$this->username=$value;
			}
		}
		return $errore;
	}

	private function setPassword($value, $confValue) {
		$errore="";
		if ($value=="") {
			$errore="<li>Password obbligatoria</li>";
		} elseif (strcmp($value, $confValue)!=0) {
			$errore="<li>Campi \"Password\" e \"Conferma password\" diversi</li>";
		} elseif (strlen($value)>45) {
			$errore="<li>Password troppo lunga</li>";
		} else {
			$this->password=Utente::cript($value);
		}
		return $errore;
	}

	private function setEmail($value) {
		$errore="";
		if ($value=="") {
			$errore="<li>e-mail obbligatoria</li>";
		} elseif (strlen($value)>45) {
			$errore="<li>e-mail troppo lunga</li>";
		} else {
			if(!preg_match('/([\w]+)\@([\w]+)\.([\w])/', $value)) {
				$errore="<li>e-mail non valida</li>";
			} else {
				//COLLEGAMENTO AL DATABASE E CONTROLLO CHE NON SIA GIA' USATA
				$presente=false;
				$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
				try {
					if ($connessione->connect_errno) {
						throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
					} else {
						$query="SELECT email FROM Utenti WHERE email=\"".$value."\";";
						if (!$result=$connessione->query($query)) {
							echo "Query non valida: ".$connessione->error.".";
							$presente=true;
						} else {
							if ($result->num_rows>0) {
								$presente=true;
							}
						}
						$connessione->close();
					}
				} catch (Exception $e){
					echo "Errore: inserimento fallito (".$e->getMessage().").";
					$presente=true;
				}
				if ($presente) {
					$errore="<li>e-mail già registrata</li>";
				} else {
					$this->email=$value;
				}
			}
		}
		return $errore;
	}

	private function setNome($value) {
		$errore="";
		if (strlen($value)<=20) {
			if ($value!="") {
				$this->nome=$value;
			} else {
				$this->nome=NULL;
			}
		} else {
			$errore="<li>Nome troppo lungo</li>";
		}
		return $errore;
	}

	private function setCognome($value) {
		$errore="";
		if (strlen($value)<=20) {
			if ($value!="") {
				$this->cognome=$value;
			} else {
				$this->cognome=NULL;
			}
		} else {
			$errore="<li>Cognome troppo lungo</li>";
		}
		return $errore;
	}

	private function setDataNascita($value) {
		$errore="";
		if ($value=="") {
			$errore="<li>Data di nascita obbligatoria</li>";
		} elseif (strlen($value)>10) {
			$errore="<li>Data di nascita troppo lunga</li>";
		} else {
			if(!preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $value)) {
				$errore="<li>Formato data non valido</li>";
			} else {
				$dataAttuale=date("d/m/Y");
				$controlloData=explode("/", $dataAttuale);
				$controlloData[2]=$controlloData[2]-16;	//BISOGNA AVERE ALMENO 16 ANNI PER REGISTRARSI
				$data=explode("/", $value);
				$gg=$data[0];
				$mm=$data[1];
				$aa=$data[2];
				$flag=false;
				if (!checkdate($mm,$gg,$aa)) {
					$errore="<li>Data non valida</li>";
				} else {
					if ($aa<$controlloData[2]) {
						$flag=true;
					} elseif ($aa>$controlloData[2]) {
						$errore="<li>Bisogna avere almeno 16 anni</li>";
					} else {
						if ($mm<$controlloData[1]) {
							$flag=true;
						} elseif ($mm>$controlloData[1]) {
							$errore="<li>Bisogna avere almeno 16 anni</li>";
						} else {
							if ($gg<=$controlloData[0]) {
								$flag=true;
							} elseif ($gg>$controlloData[0]) {
								$errore="<li>Bisogna avere almeno 16 anni</li>";
							}
						}
					}
				}
				if ($flag) {
					$value=$aa."/".$mm."/".$gg;	//INVERSIONE DELLA DATA NEL FORMATO VALIDO PER SQL (gg/mm/aaaa -> aaaa/mm/gg)
					$this->dataNascita=$value;
				}
			}
		}
		return $errore;
	}

	private function setImmagine($value) {
		$errore="";
		if ($value!="") {
			if (strlen($value)<=45) {
				$this->immagine=$value;
			} else {
				$errore="<li>Nome <span xml:lang=\"en\" lang=\"en\">file</span> non valido</li>";
			}
		} else {
			$this->immagine=NULL;
		}
	}

	private function setDescrizione($value) {
		$errore="";
		if ($value!="") {
			if (strlen($value)<=200) {
				$this->descrizione=$value;
			} else {
				$errore="<li>Descrizione troppo lunga</li>";
			}
		} else {
			$this->descrizione=NULL;
		}
	}

	private function setDataIscrizione() {
		$value=date("Y/m/d", time());
		$this->dataIscrizione=$value;
	}

	private function setProvincia($value) {
		$this->provincia=$value;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getNome() {
		return $this->nome;
	}

	public function getCognome() {
		return $this->cognome;
	}

	public function getDataNascita() {
		return $this->dataNascita;
	}

	public function getImmagine() {
		return $this->immagine;
	}

	public function getDescrizione() {
		return $this->descrizione;
	}

	public function getDataIscrizione() {
		return $this->dataIscrizione;
	}

	public function getProvincia() {
		return $this->provincia;
	}

	public static function cript($value) {
		global $span, $numeroCriptazioni;
		for ($i=0; $i<$numeroCriptazioni; $i++) {
			$value=md5($value.$span);
		}
		return $value;
	}

	private function createPage() {
		$userTemplate = file_get_contents('user_template.php');
		$userPage = fopen('../../users/' . $this->username . '/index.php', 'w');
		fwrite($userPage, $userTemplate);
	}

	function save() {
		$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
		try {
			if ($connessione->connect_errno) {
				throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
			} else {
				$ins="INSERT INTO Utenti(username, password, email, dataNascita, dataIscrizione) VALUES (\"".$this->username."\",\"".$this->password."\",\"".$this->email."\",\"".$this->dataNascita."\",\"".$this->dataIscrizione."\");";	//CREAZIONE DELLA QUERY
				if (!$connessione->query($ins)) {
					throw new Exception("Query non valida: ".$connessione->error.".");
				}
				$connessione->close();	//CHIUSURA CONNESSIONE
				createPage();	// CREAZIONE DELLA PAGINA PROFILO HTML
			}
		} catch (Exception $e){
			echo "Errore: inserimento fallito (".$e->getMessage().").";
		}
	}
}

?>
