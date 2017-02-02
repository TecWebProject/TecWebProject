<?php

session_start();



# leggi template
$file = file_get_contents('template.xml');



# costruisci intestazione
require_once '../lib/php/start.php';
$start = Start::getHead(
	array(
		'Titolo' => 'Cerca gruppi - BandBoard',
		'DescrizioneBreve' => 'Cerca gruppi in BandBoard',
		'Descrizione' => 'Cerca gruppi in BandBoard, per generi musicali, province e genere.',
		'Keywords' => array('BandBoard', 'cerca', 'gruppi', 'musicisti'),
		'Stylesheets' => array('style.css'),
		'Extra' => array('<link rel="stylesheet" media="handheld, screen and (max-width:480px), only screen and (max-device-width:480px)" href="../lib/css/style_mobile.css" type="text/css" />', '<script type="text/javascript" src="../lib/js/province.js"></script>')
	)
);
$file = str_replace('<html>', $start, $file);



# costruisci header
require_once '../lib/php/header.php';
$header = Header::getHeader();
$file = str_replace('<header />', $header, $file);



# costruisci menù
require_once '../lib/php/menu.php';
if (isset($_SESSION['username'])) { # utente loggato
	$menu = Menu::getMenu(
		array(
			'<a href="../index.php" xml:lang="en" lang="en">Home</a>',
			'<a href="../profiloUtente/profiloUtente.php?username=' . $_SESSION['username'] . '">Visualizza Profilo</a>',
			'<a href="../cercaUtenti/index.php">Cerca Utenti</a>',
			'Cerca Gruppi',
			'<a href="../gestioneGruppi/index.php">I miei Gruppi</a>'
		)
	);
} else { # utente non loggato
	$menu = Menu::getMenu(
		array(
			'<a href="../index.php" xml:lang="en" lang="en">Home</a>',
			'<a href="../cercaUtenti/index.php">Cerca Utenti</a>',
			'Cerca Gruppi'
		)
	);
}
$file = str_replace('<menu />', $menu, $file);



# costruisci pulsante logout per utente registrato
$logout = '';
if (isset($_SESSION['username'])) {
	$logout = '<div class="logout">' .
		'<form action="../lib/php/logout.php" method="post">' .
			'<p><input type="submit" id="logout" value="Logout" /></p>' .
		'</form>' .
	'</div>';
}
$file = str_replace('<logout />', $logout, $file);



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



# prepara parametri di paginazione dei risultati
$per_page = 10; # numero risultati per pagina
$curr_page = isset($_GET['num']) ? (int)$_GET['num'] : 1; # pag. corrente
$primo = ($curr_page - 1) * $per_page; # primo gruppo della pag. corrente



# costruisci risultati query
$where_provincia = $where_genere = "? LIKE '%' "; # hack

if (isset($_GET['provincia']) && $_GET['provincia'] != '')
	$where_provincia = "gr.provincia = ? ";
else $_GET['provincia'] = '_'; # hack

if (isset($_GET['genere']) && $_GET['genere'] != '')
	$where_genere = "gr.idGruppo = gg.gruppo AND gg.genere = ? ";
else $_GET['genere'] = '_'; # hack

require_once '../lib/php/query_server.php';
$conn = dbConnectionData::getMysqli();
$stmt = $conn->prepare("SELECT DISTINCT gr.idGruppo, gr.nome, gr.provincia, gr.immagine
	FROM Gruppi gr, GeneriGruppi gg
	WHERE $where_provincia
	AND $where_genere"
);

$risultati = '';
$tot_gruppi = 0;
if ($stmt) {
	$stmt->bind_param('ss', $_GET['provincia'], $_GET['genere']);
	$stmt->execute();
	$stmt_result = $stmt->get_result();
	$gruppi = $stmt_result->fetch_all(MYSQLI_ASSOC);
	if ($gruppi) {
		$tot_gruppi = count($gruppi);
		$risultati .= '<ul class="listaRisultati">';
		for ($g = $primo; $g < min($primo + 10, $tot_gruppi); $g++) {
			$img = '../images/site/defaultBand.png';
			if ($gruppi[$g]['immagine'] != '' && file_exists('../images/bands/' . $gruppi[$g]['immagine'])) {
				$img = $gruppi[$g]['immagine'];
			}
			$risultati .= '<li class="elementResult"><a href="../profiloGruppo/profiloGruppo.php?idGruppo=' . $gruppi[$g]['idGruppo'] . '">' . '<img class="listImage" src="' . $img . '" alt="Immagine del gruppo ' . $gruppi[$g]['nome'] . '" />' . $gruppi[$g]['nome'] . ' (' . $gruppi[$g]['provincia'] . ')</a></li>';
		}
		unset($gruppi);
		$risultati .= '</ul>';
	}
	else
		$risultati .= '<p>La tua ricerca non ha portato risultati.</p>';
	$stmt->close();
} else {
	$risultati = '<p>Errore: [' . $conn->errno  .'] ' . $conn->error . '</p>';
}

$conn->close();
$file = str_replace('<risultati />', $risultati, $file);



# costruisci link paginazione
$tot_pages = ceil($tot_gruppi / $per_page); # numero totale pagine
$paginazione = '';
if ($tot_pages > 0) {
	$precedente = '<span class="notClickable">precedente</span>';
	if ($curr_page > 1)
		$precedente = '<a href="index.php?num=' . ($curr_page - 1) . '">precedente</a>';
	$successiva = '<span class="notClickable">successiva</span>';
	if ($curr_page < $tot_pages)
		$successiva = '<a href="index.php?num=' . ($curr_page + 1) . '">successiva</a>';
	$paginazione = $precedente . ' ' . $successiva;
}
$file = str_replace('<paginazione />', $paginazione, $file);



# costruisci footer
require_once '../lib/php/footer.php';
$footer = Footer::getFooter();
$file = str_replace('<footer />', $footer, $file);



# ritorna template popolato con contenuto dinamico
echo $file;

?>
