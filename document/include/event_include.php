<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			12.09.2019
		
		Letztes Änderungsdatum: 	12.09.2019
		
	*/
	
	// event_include.php
	// Bearbeitet alle Events
	
	class event{
		
		// Events
		
		public static function logout(){

			// logout()
			// Meldet den Benztzer ab
			
			session_destroy();
			// Leitet den Benutzer zur Startseite weiter
			header('Location: index.php');
			// Script beenden
			exit;
			
		}

		function update_printer($data) {

            $info = explode(",", $data);

            switch ($info['2']) {

                case 0:
                    put_sql_data::update_data('printer', 'ID', $info['1'], 'name', '\'' . $info['0'] . '\'');
                    break;

                case 1:
                    put_sql_data::update_data('printer', 'ID', $info['1'], 'customID', $info['0']);
                    break;

                case 2:
                    put_sql_data::update_data('toner', 'ID', $info['1'], 'min_quantity', $info['0']);
                    break;

                case 3:
                    put_sql_data::update_data('addon', 'ID', $info['1'], 'min_quantity', $info['0']);
                    break;

                default:
                   die('Anwendungsfall unbekannt');
                    break;

            }

        }

	}
	
?>