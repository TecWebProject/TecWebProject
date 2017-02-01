<?php

# leggi template
$file = file_get_contents('template.html');



# costruisci intestazione
require_once '../lib/php/start.php';
$start = Start::getHead(
	array(
		'Titolo' => 'Cerca utenti - BandBoard',
		'DescrizioneBreve' => 'Cerca utenti in BandBoard',
		'Descrizione' => 'Cerca utenti in BandBoard, per strumenti suonati, province e genere.',
		'Keywords' => array('cerca', 'utenti', 'musicisti'),
		'Stylesheets' => array('style.css', 'mobile.css'),
		'Extra' => array('<script type="text/javascript" src="../lib/js/province.js"></script>')
	)
);
$file = str_replace('<html>', $start, $file);



# costruisci header
require_once '../lib/php/header.php';
$header = Header::getHeader();
$file = str_replace('<header />', $header, $file);



# costruisci menù
require_once '../lib/php/menu.php';
$menu = Menu::getMenu(
	array(
		'<a href="../index.php" xml:lang="en" lang="en">Home</a>',
		'<a href="profilo.php">Profilo</a>',
		'Cerca Utenti',
		'<a href="../cercaGruppi">Cerca Gruppi</a>',
		'<a href="imieigruppi.php">I miei Gruppi</a>'
	)
);
$file = str_replace('<menu />', $menu, $file);



# costruisci selezione strumenti
require_once '../lib/php/strumenti.php';
$strumenti = '';
$arr_strumenti = Strumenti::getStrumenti();
foreach ($arr_strumenti as $el) {
	$strumenti .= '<option value="' . $el . '"';
	if (isset($_GET['strumento']) && $el == $_GET['strumento'])
		$strumenti .= ' selected="selected"';
	$strumenti .= '>' . $el . '</option>';
}
$file = str_replace('<strumenti />', $strumenti, $file);



# costruisci selezione regioni
require_once '../lib/php/regioni.php';
$regioni = '';
$arr_regioni = Regioni::getRegioni();
foreach ($arr_regioni as $el) {
	$regioni .= '<option value="' . $el['nome'] . '"';
	if (isset($_GET['regione']) && $el['nome'] == $_GET['regione'])
		$regioni .= ' selected="selected"';
	$regioni .= '>' . $el['nome'] . '</option>';
}
$file = str_replace('<regioni />', $regioni, $file);



# costruisci selezione province
require_once '../lib/php/province.php';
$province = '';
$arr_province = Province::getProvinceByRegione();
foreach ($arr_province as $key => $reg) {
	$province .= '<optgroup label="' . $key . '">';
	foreach ($reg as $prov) {
		$province .= '<option value="' . $prov['sigla'] . '"';
		if (isset($_GET['provincia']) && $prov['sigla'] == $_GET['provincia'])
			$province .= ' selected="selected"';
		$province .= '>' . $prov['nome'] . '</option>';
	}
	$province .= '</optgroup>';
}
$file = str_replace('<province />', $province, $file);



# costruisci selezione generi musicali
require_once '../lib/php/generiMusicali.php';
$generi = '';
$arr_generi = GeneriMusicali::getGeneriMusicali();
foreach ($arr_generi as $el) {
	$el = htmlentities($el);
	$generi .= '<option value="' . $el . '"';
	if (isset($_GET['genere']) && $el == $_GET['genere'])
		$generi .= ' selected="selected"';
	$generi .= '>' . $el . '</option>';
}
$file = str_replace('<generi />', $generi, $file);



# prepara parametri di paginazione dei risultati
$per_page = 10; # risultati per pagina
$curr_page = isset($_GET['num']) ? (int)$_GET['num'] : 1; # pag. corrente
$primo = ($curr_page - 1) * $per_page; # primo utente della pag. corrente



# costruisci risultati query
$filtro_provincia = $filtro_strumento = $filtro_genere = "? LIKE '%' "; # hack

if (isset($_GET['provincia']) && $_GET['provincia'] != '')
	$filtro_provincia = "u.provincia = ? ";
else $_GET['provincia'] = '_'; # hack

if (isset($_GET['strumento']) && $_GET['strumento'] != '')
	$filtro_strumento = "u.username = c.utente AND c.strumento = ? ";
else $_GET['strumento'] = '_'; # hack

if (isset($_GET['genere']) && $_GET['genere'] != '')
	$filtro_genere = "u.username = g.utente AND g.genere = ? ";
else $_GET['genere'] = '_'; # hack

require_once '../lib/php/query_server.php';
$conn = dbConnectionData::getMysqli();
$stmt = $conn->prepare("SELECT DISTINCT u.username, u.provincia, u.immagine
	FROM Utenti u, GeneriUtenti g, Conoscenze c
	WHERE $filtro_provincia
	AND $filtro_strumento
	AND $filtro_genere"
);

$risultati = '';
$tot_utenti = 0; # numero totale utenti
if ($stmt) {
	$stmt->bind_param('sss', $_GET['provincia'], $_GET['strumento'], $_GET['genere']);
	$stmt->execute();
	$stmt_result = $stmt->get_result();
	$utenti = $stmt_result->fetch_all(MYSQLI_ASSOC);
	if ($utenti) {
		$tot_utenti = count($utenti);
		$risultati .= '<ul class="listaRisultati">';
		for ($r = $primo; $r < min($primo + 10, $tot_utenti); $r++) {
			$img = '../images/site/defaultUser.png';
			if ($utenti[$r]['immagine'] != '' && file_exists('../images/users/' . $utenti[$r]['immagine'])) {
				$img = $utenti[$r]['immagine'];
			}
			$risultati .= '<li class="elementResult"><a href="../profiloUtente/profiloUtente.php?username=' . $utenti[$r]['username'] . '&amp;page=ricerca&amp;num=' . $curr_page . '">' . '<img class="listImage" src="' . $img . '" alt="Immagine di ' . $utenti[$r]['username'] . '" />' . $utenti[$r]['username'] . ' (' . $utenti[$r]['provincia'] . ')</a></li>';
		}
		unset($utenti);
		$risultati .= '</ul>';
	} else
		$risultati = '<p>La tua ricerca non ha portato risultati.</p>';
	$stmt->close();
} else {
	$risultati = '<p>Errore: [' . $conn->errno  .'] ' . $conn->error . '</p>';
}

$conn->close();
$file = str_replace('<risultati />', $risultati, $file);



# costruisci link paginazione
$tot_pages = ceil($tot_utenti / $per_page); # numero totale pagine
$paginazione = '';
if ($tot_pages > 0) {
	$precedente = '<li>precedente</li>';
	if ($curr_page > 1)
		$precedente = '<li><a href="index.php?num=' . ($curr_page - 1) . '">precedente</a></li>';
	$successiva = '<li>successiva</li>';
	if ($curr_page < $tot_pages)
		$successiva = '<li><a href="index.php?num=' . ($curr_page + 1) . '">successiva</a></li>';
	$paginazione = $precedente . $successiva;
}
$file = str_replace('<paginazione />', $paginazione, $file);



# costruisci footer
require_once '../lib/php/footer.php';
$footer = Footer::getFooter();
$file = str_replace('<footer />', $footer, $file);



# ritorna template popolato con contenuto dinamico
echo $file;

?>
