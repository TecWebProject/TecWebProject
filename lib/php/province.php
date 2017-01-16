<?php

	$relPath = realpath(dirname(__FILE__));
	require_once($relPath . '/query_server.php');

	/**
	*  Classe per la richiesta delle province d'italia
	*  Due opzioni: getProvince() o getProvinceByRegione()
	*/
	class Province {

		# DESCR: Ritorna un array con i nomi delle province
		# OUTPUT: Array associativo ordinato alfabeticamente
		public static function getProvince() {
			$result = null;
			try {
				mysqli_report(MYSQLI_REPORT_STRICT);

				$query = "SELECT * FROM Province ORDER BY Nome";

				$_mysqli = dbConnectionData::getMysqli();

				if (!$stmt = $_mysqli->prepare($query)) {
					throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
				}
				$stmt->execute();
				$stmt_result = $stmt->get_result();
				$result = $stmt_result->fetch_all(MYSQLI_ASSOC);

				return $result;
			} catch (Exception $e) {
				throw $e;
			}
		}

		# DESCR: Ritorna un array con le province raggruppate per regione
		# OUTPUT: Array associativo
		public static function getProvinceByRegione() {
			$result = array();
			$total = Province::getProvince();
			foreach ($total as $val)
				$result[$val['regione']][] = $val;

			ksort($result);
			return $result;
		}
	}

#	ESEMPIO D'USO per getProvinceByRegione():
#
#	$arr_province = Province::getProvinceByRegione();
#	foreach ($arr_province as $key => $reg) {
#		$province .= '<optgroup label="' . $key . '">';
#		foreach ($reg as $prov) {
#			$province .= '<option value="' . $prov['sigla'] . '"';
#			if (isset($_GET['provincia']) && $prov['sigla'] == $_GET['provincia'])
#				$province .= ' selected="selected"';
#			$province .= '>' . $prov['nome'] . '</option>';
#		}
#		$province .= '</optgroup>';
#	}

?>
