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
			'Stylesheets' => array('style.css'),
			'Extra' => array('<link rel="stylesheet" type="text/css" href="../lib/css/style.css" />', '<script type="text/javascript" src="../lib/js/province.js"></script>')
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



	# costruisci selezione generi
	require_once '../lib/php/generiMusicali.php';
	$generi = '';
	$arr_generi = GeneriMusicali::getGeneriMusicali();
	foreach ($arr_generi as $el) {
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



	# costruisci risultati query
	require_once '../lib/php/query_server.php';
	$conn = dbConnectionData::getMysqli();
	$risultati = '';
	if (isset($_GET['genere']) && isset($_GET['provincia'])) {
		$stmt = $conn->prepare("SELECT gr.idGruppo, gr.nome, gr.provincia, gg.genere
			FROM Gruppi gr, GeneriGruppi gg
			WHERE gr.idGruppo = gg.gruppo
			AND gg.genere = ?
			AND gr.provincia = ?");
		$stmt->bind_param("ss", $_GET['genere'], $_GET['provincia']);

		$stmt->bind_result($res_band, $res_nome, $res_prov, $res_genere);
		$res = $stmt->execute();
		$stmt_result = $stmt->get_result();
		$result = $stmt_result->fetch_all(MYSQLI_ASSOC);
		if ($result) {
			$risultati .= '<ul id="risultati">';
			foreach ($result as $el) {
				$risultati .= '<li><a href="www.sito.it/users/' . $el['idGruppo'] . '">' . $el['nome'] . ' (' . $el['provincia'] . ') - ' . $el['genere'] . "</a></li>";
			}
			$risultati .= '</ul>';
		}
		else
			$risultati .= '<p>La tua ricerca non ha portato risultati.</p>';
		$stmt->close();
	}
	$conn->close();
	$file = str_replace('<risultati />', $risultati, $file);



	# ritorna template popolato con contenuto dinamico
	echo $file;
?>
