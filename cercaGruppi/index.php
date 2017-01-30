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
			'<a href="index.html" xml:lang="en">Home</a>',
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
	require_once '../lib/php/query_server.php';
	$conn = dbConnectionData::getMysqli();
	$count = $conn->query("SELECT COUNT(username) FROM Utenti");
	$tot = $count->fetch_row()[0];
	$per_page = 10; # numero risultati per pagina
	$tot_pages = ceil($tot / $per_page); # numero totale pagine
	$curr_page = (!isset($_GET['page'])) ? 1 : (int)$_GET['page']; # pag. corrente
	$primo = ($curr_page - 1) * $per_page; # primo gruppo della pag. corrente
	$limit = " LIMIT $primo, $per_page";



	# costruisci risultati query
	require_once '../lib/php/query_server.php';
	$conn = dbConnectionData::getMysqli();
	$risultati = '';
	if (isset($_GET['genere']) && isset($_GET['provincia'])) {
		$stmt = $conn->prepare("SELECT gr.idGruppo, gr.nome, gr.provincia, gg.genere
			FROM Gruppi gr, GeneriGruppi gg
			WHERE gr.idGruppo = gg.gruppo
			AND gg.genere = ?
			AND gr.provincia = ?" . $limit);
		$stmt->bind_param('ss', $_GET['genere'], $_GET['provincia']);

		$res = $stmt->execute();
		$stmt_result = $stmt->get_result();
		$result = $stmt_result->fetch_all(MYSQLI_ASSOC);
		if ($result) {
			$risultati .= '<ul id="risultati">';
			foreach ($result as $el) {
				$risultati .= '<li><a href="../profilo/index.php?gruppo=' . $el['idGruppo'] . '">' . $el['nome'] . ' (' . $el['provincia'] . ') - ' . $el['genere'] . '</a></li>';
			}
			$risultati .= '</ul>';
		}
		else
			$risultati .= '<p>La tua ricerca non ha portato risultati.</p>';
		$stmt->close();
	}
	$conn->close();
	$file = str_replace('<risultati />', $risultati, $file);



	# costruisci footer
	require_once '../lib/php/footer.php';
	$footer = Footer::getFooter();
	$file = str_replace('<footer />', $footer, $file);



	# ritorna template popolato con contenuto dinamico
	echo $file;
?>
