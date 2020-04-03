<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// header.php
	// Prüft, ob Benutzer angemeldet ist ( über authentication.php )
	// Erzeugt den header-Bereich

	// Prüft, ob GET-Anfragen "login" existiert
	// Wenn Ja, versucht Benutzer sich anzumelden
	// Es muss also nicht geprüft werden, ob Benutzer angemdlet ist
	
	// Bindet die authentication.php Datei ein
	// include_once('./class/authentication/authentication.php');
	include_once('./class/header/header_function.php');

	// Prüft, ob GET-Anfragen mit dem Namen "login" existiert
	if(isset($_GET['login'])) {
		
		// Wenn nun eine POST-Anfrage namens "username" existiet, hat der Benutzer seine Benutzerdaten eingegeben
		if(isset($_POST['username'])) {
			
			// Nun muss geprüft werden, ob Benutzerdaten korrekt sind
			// Wenn Passwort leer ist, speichere Fehler in $error
			if($_POST['password'] == '') {
				
				$error = 'Passwort darf nicht leer sein';
				
			} else {
			
				// Prüfe Anmeldedaten mit try_login($_POST['username'], $_POST['password']) ( über authentication.php )
				if(authentication::try_login($_POST['username'], $_POST['password']) == 1) {
					
					// Wenn Anmeldung erfolgreich, rufe login() auf ( über authentication.php )
					authentication::login();
					// Als nächstes Seite neu laden ( ohne GET-Anfrage )
					header('Location: index.php');
					// Script beenden
					exit;
					
				} else {
					
					// Wenn Anmeldung nicht erfolgreich, speichere Fehlermeldung in $error
					$error = authentication::try_login($_POST['username'], $_POST['password']);
					
				}
				
			}
			
		}
		
		// Wenn Benutzer noch keine Benutzerdaten eingegeben hat, soll das Anmeldeformular angezeigt werden
		// Dazu erzeue zuerst header mit header_login()
		header_login();
		// Lade das Loginformular
		include_once('./document/login.php');
		// Beende das Script
		exit;
		
	}
	
	// Wenn GET-Anfragen nicht existiert, prüfe, ob Benutzer angemeldet ist ( über authentication.php )
	// Wenn nicht, dann lade das Anmeldeformular
	if(!authentication::control_login()) {
		
		// Leite zu Anmeldeformular weiter
		// Sendet eine GET-Anfragen, die hier ( header.php ) bearbeitet wird
		header('Location: ./index.php?login=true');
		// Beende das Script
		exit;
		
	} else {
		
		// Wenn der Benutzer eingeloggt wurde, rufe header() auf
		header_logged();
		
	}