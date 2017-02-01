<?php

/* CLASSE GRUPPO */

include_once "query_server.php";

//testCreazione();

function testCreazione() {
	$gruppo=new Gruppo("nome", "(immagine)", "(descrizione)", "provincia");
	echo $gruppo;
}

class Gruppo {
	private $nome="";
	private $immagine=NULL;
	private $descrizione=NULL;
	private $dataIscrizione="";
	private $provincia="";
	private $errore="";

	public function __toString() {
		return $this->errore;
	}

	function __construct($nome, $imm, $desc, $prov) {
		$erroreNome=$this->setNome($nome);
		$erroreImmagine=$this->setImmagine($imm);
		$erroreDescrizione=$this->setDescrizione($desc);
		$this->setDataIscrizione();
		$erroreProvincia=$this->setProvincia($prov);
		$this->errore=$erroreNome.$erroreImmagine.$erroreDescrizione.$erroreProvincia;
		$this->errore=$this->errore ? "<ul class=\"errReg\">".$this->errore."</ul>" : "";
	}

	private function setNome($value) {
		$errore="";
		if ($value=="") {
			$errore="<li>Nome obbligatorio</li>";
		} elseif (strlen($value)>45) {
			$errore="<li>Nome troppo lungo</li>";
		} else {
			$this->nome=$value;
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
		$errore="";
		if ($value=="") {
			$errore="<li>Provincia obbligatoria</li>";
		} else {
			$this->provincia=$value;
		}
		return $errore;
	}

	public function getNome() {
		return $this->nome;
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

	public function save($user, $strum) {
		if ($user!="" && $strum!="") {
			$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
			try {
				if ($connessione->connect_errno) {
					throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
				} else {
					$ins="INSERT INTO Gruppi(nome, dataIscrizione, provincia) VALUES (\"".$this->nome."\",\"".$this->dataIscrizione."\",\"".$this->provincia."\");";	//CREAZIONE DELLA QUERY PER CREARE IL GRUPPO
					if (!$connessione->query($ins)) {
						throw new Exception("Query non valida: ".$connessione->error.".");
					}
					//CREAZIONE DELLA RELAZIONE TRA L'UTENTE ED IL GRUPPO CHE HA APPENA CREATO
					$query="SELECT idConoscenza FROM Conoscenze WHERE utente=\"".$user."\" AND strumento=\"".$strum."\";";
					if (!$result=$connessione->query($query)) {
						echo "Query non valida: ".$connessione->error.".";
						return false;
					} else {
						if ($result->num_rows>0) {
							$row=$result->fetch_array(MYSQLI_ASSOC);
							$conoscenza=$row['idConoscenza'];
						}
						$result->free();
					}
					$query="SELECT idGruppo FROM Gruppi WHERE nome=\"".$this->nome."\" AND dataIscrizione=\"".$this->dataIscrizione."\" AND provincia=\"".$this->provincia."\";";
					if (!$result=$connessione->query($query)) {
						echo "Query non valida: ".$connessione->error.".";
						return false;
					} else {
						if ($result->num_rows>0) {
							$row=$result->fetch_array(MYSQLI_ASSOC);
							$gruppo=$row['idGruppo'];
						}
						$result->free();
					}
					$ins="INSERT INTO Formazioni(gruppo,ruolo) VALUES (\"".$gruppo."\",\"".$conoscenza."\");";	//CREAZIONE DELLA QUERY
					if (!$connessione->query($ins)) {
						throw new Exception("Query non valida: ".$connessione->error.".");
					}
					$connessione->close();	//CHIUSURA CONNESSIONE
					return true;
				}
			} catch (Exception $e){
				echo "Errore: inserimento fallito (".$e->getMessage().").";
				return false;
			}
		} else {
			return false;
		}
	}
}

?>
