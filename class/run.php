<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// run.php
	// Bindet kritische Dateien ein
	
	// Startet SESSION
	session_start();
	
		function run() {
			
			// run()
			// Bindet header.php ein
			// Bearbeitet alle GET-Anfragen außer "login"
			
			// Bindet die header.php ein
			include_once('./class/header/header.php');

			// Prüfe, ob GET-Anfragen existiert
			// Wenn Benutzer eine Seite aufruft, wird die Anfrage in GET['site'] gespeichert
			if(isset($_GET['site'])) {

				// Wenn Benutzer eine Seite aufgerufen hat, wird geprüft, ob diese exisitiert
				if(file_exists('./document/' . $_GET['site'] . '.php')) {

					// Wenn die Datei existiert, binde sie ein
					include_once('./document/' . $_GET['site'] . '.php');

				} else {

					// Wenn nicht, leite zur Startseite weiter
					header('Location: index.php?site=Druckerübersicht');

				}
				
			} else {

				// Wenn keine Anfrage existiert, leite zur Startsete weiter
				header('Location: index.php?site=Druckerübersicht');
				
			}

		}
	
?>