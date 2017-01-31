<?php

# leggi template
$file = file_get_contents('template.html');



# costruisci intestazione
require_once '../lib/php/start.php';
$start = Start::getHead(
	array(
		'Titolo' => 'Cerca gruppi - BandBoard',
		'DescrizioneBreve' => 'Cerca gruppi in BandBoard',
		'Descrizione' => 'Cerca gruppi in BandBoard, per strumenti suonati, province e genere.',
		'Keywords' => array('cerca', 'gruppi', 'musicisti'),
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
		'<a href="../cercaUtenti">Cerca Utenti</a>',
		'Cerca Gruppi',
		'<a href="imieigruppi.php">I miei Gruppi</a>'
	)
);
$file = str_replace('<menu />', $menu, $file);



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
	$where_provincia = " AND gr.provincia = ? ";
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
			$risultati .= '<li class="elementResult"><a href="../profiloGruppo/profiloGruppo.php?idGruppo=' . $gruppi[$g]['idGruppo'] . '&page=ricerca&num=' . $curr_page . '">' . '<img class="listImage" src="' . $img . '" alt="Immagine di ' . $gruppi[$g]['idGruppo'] . '" />' . $gruppi[$g]['nome'] . ' (' . $gruppi[$g]['provincia'] . ')</a></li>';
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
