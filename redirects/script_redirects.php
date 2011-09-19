<?php

/*
	Script che prende l'elenco raw di eventi registrati e rigenera il semplice file che mappa i redirect nomecitta.linuxday.it
	In caso di piu' eventi nella stessa citta, nomecitta.linuxday.it punta ad una pagina ad-hoc su linuxday.it
*/

function testcity ($city) {
	sleep (1);

	$c = str_replace (' ', '%20', $city);
	$test = file_get_contents ("http://api.geonames.org/search?q=${c}&country=IT&username=madbob");

	$doc = new DOMDocument ();
	if ($doc->loadXML ($test, LIBXML_NOWARNING) == false) {
		echo "Impossibile testare $city\n";
		return FALSE;
	}

	$xpath = new DOMXPath ($doc);

	$results = $xpath->query ("/geonames/totalResultsCount", $doc);
	if ($results->length < 1) {
		echo "Risposta GeoNames invalida interrogando $city\n";
		return FALSE;
	}

	$test = $results->item (0);
	if ($test->nodeValue == 0) {
		echo "$city sembra non esistere\n";
		return FALSE;
	}

	return TRUE;
}

$final = array ();

$contents = file ('http://www.linuxday.it/data', FILE_IGNORE_NEW_LINES);
if ($contents === FALSE) {
	echo "Impossibile recuperare il file raw\n";
}
else {
	/*
		Questo e' per sopprimere la prima riga di intestazione
	*/
	unset ($contents [0]);
	$contents = array_values ($contents);

	foreach ($contents as $row) {
		$fields = explode ('","', $row);

		$city = strtolower (trim ($fields [3], '"'));
		$city = str_replace ("'", '', $city);
		$city = str_replace (' ', '', $city);

		$link = trim ($fields [5], '"');

		if ($city == '' || $link == '') {
			echo "Manca un elemento: $row\n";
			continue;
		}

		if (isset ($final [$city])) {
			$final [$city] = "http://www.linuxday.it/citypage/" . $fields [3];
		}
		else {
			if (testcity ($fields [3]) == FALSE)
				continue;

			if ($link [strlen ($link) - 1] != '/')
				$link .= '/';

			$final [$city] = $link;
		}
	}

	$text = '';

	foreach ($final as $city => $link)
		$text .= "$city $link\n";

	echo "\n\n$text\n";
	file_put_contents ('redirects.txt', $text);
}

?>
