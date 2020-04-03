<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			12.09.2019
		
		Letztes Änderungsdatum: 	12.09.2019
		
	*/
	
	// authentication.php
	// Prüft, ob Benutzer angemeldet ist
	// Meldet Benutzer über AD an ( über get_data.php )
	
	class authentication{
		
		function control_login(){
		
			// control_login()
			// Prüft, ob Benutzer angemeldet ist
			// Wenn Ja, gib true zurück, wenn Nein, gib false zurück
			
			// Prüft, ob SESSION "login" existiert
			return isset($_SESSION['login']) ? true : false;
			
		}
		
		function try_login($username, $password){
			
			// try_login($username, $password)
			// Prüft über AD, ob Benutzerdaten korrekt sind

            // Speichere in $sDCServer die IP des DC Servers für AD-Autentifizierung
			$aDCServer = get_sql_data::find_all_data('SELECT * FROM DC', [ 0 => 'DC_server' ]);

			// Wenn keine IP gefunden wurde
			if(!isset($aDCServer['0'])) {

                echo 'Fehler: In der Datenbank kann keine IP-Adresse für den DC-Server gefunden werden.';
                exit;

            }
			
			// Speichere das Ergebis der AD Abfrage in $ad
			@$ad = ldap_bind(ldap_connect($aDCServer['0']), 'intra\\' . strtolower($username), $password);
			
			if($ad == 1){
				
				// Wenn Anmeldung erfolgreich, prüfe, ob Benutzer in SQL Tabelle steht
				// Rufe dazu is_searchword() auf
				if(!get_sql_data::is_searchword('user', 'name', strtolower($username), 'string')){
					
					// Benutzer steht nicht in der SQL Tabelle
					return 'Benutzer ist nicht berechtigt, sich anzumelden';
					
				} else {
					
					// Anmeldung war erfolgreich
					return 1;
					
				}
				
			} else {
				
				// Wen Anmeldung nicht erfolgreich war, gib false zurück
				return 'Benutzerdaten waren nicht korrekt';
				
			}
			
		}
		
		function login(){
			
			// login()
			
			// Erstelle SESSION mit dem Namen login
			$_SESSION['login'] = '1';
			
		}
		
	}
	
?>