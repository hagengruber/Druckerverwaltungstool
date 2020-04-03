<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			12.09.2019
		
		Letztes Änderungsdatum: 	12.09.2019
		
	*/
	
	// get_data.php
	// Baut eine SQL Verbindung auf und liefert die gewünschten Daten zurück
	
	class get_sql_data{
		
		// Funktion is_searchword
		// Prüft, ob gesuchtes Wort in Tabelle steht
		public static function is_searchword($table_name, $column_name, $word, $varType){
			
			// In $varType steht der Variablentyp, den das gesuchte Wort hat
			switch($varType){
				
				/*
					Wenn der Variablentype eine Zeichenkette ist,
					muss in der SQL Abfrage vor und nach der Zeichenkette ' stehen
				*/
				case 'string':
					$sql = 'SELECT * FROM ' . $table_name . ' WHERE ' . $column_name . '=\'' . $word . '\'';
				break;
				
				/*
					Wenn der Variablentype keine Zeichenkette ist,
					muss in der SQL Abfrage vor und nach der Zeichenkette kein ' stehen
				*/
				default:
					$sql = 'SELECT * FROM ' . $table_name . ' WHERE ' . $column_name . '=' . $word;
				break;
				
			}
			
			// Gib $found zurück
			$found = false;
			
			// $word in kleinbuchstaben umwandeln
			$word = strtolower($word);
			
			// In $db_link wird eine mysql Verbindung gespeichert
			$db_link = mysqli_connect("localhost", "root", "", "druckerverwaltungstool");
			
			// In db_erg wird das Ergebnis der SQL Abfrage gespeichert
			$db_erg = mysqli_query( $db_link, $sql );
			
			$data = mysqli_fetch_array($db_erg);
			
			// Wenn nichts gefunden wurde, dann ist in der Variable $db_erg kein Inhalt
			if($data['0'] != ""){
				
				// Wenn etwas gefunden wurde, setzte $fouund auf True;
				$found = true;
				
			}
			
			// Wenn Suchwort gefunden wurde, gib True zurück
			// Sonst False
			return $found;
			
		}

		public static function get_toner_menge($toner_id, $farbe){
			
			// get_toner_menge
			// Baut eine SQL Verbindung auf und liefert die Anzahl eines Toners zurück
			
			// In $db_link wird eine mysql Verbindung gespeichert
			$db_link = mysqli_connect("localhost", "root", "", "Druckerverwaltungstool");
			
			switch($farbe){
				
				case 'Schwarz':
					$color = 'black';
				break;
				case 'Magenta':
					$color = 'magenta';
				break;
				case 'Gelb':
					$color = 'yellow';
				break;
				case 'Cyan':
					$color = 'cyan';
				break;
				
			}
			
			// SQL Befehl
			$sql = "SELECT * FROM bestellung WHERE Toner_" . $color . '_id=' . $toner_id;
			
			// In db_erg wird das Ergebnis der SQL Abfrage gespeichert
			$db_erg = mysqli_query( $db_link, $sql );
			
			$found = 0;
			
			// Solange in der Variable db_erg Einträge sind
			while ($zeile = mysqli_fetch_array($db_erg)){
				
				// Wenn $name_of_column existiert, speichere dies in $found ab
				if(isset($zeile['Bestellung_id'])){
					
					$found += $zeile['menge'];
					
				}
				
			}
			
			return isset($found) ? $found : false;
			
		}

		public static function get_free($table, $name_of_column){
			
			// get_free
			// Liefert einen freien Wert einer Datenbanktabelle zurück
			
			//Datenbankverbindung wird aufgebaut
			$db_link = mysqli_connect("localhost", "root", "", "Druckerverwaltungstool");
			$sql = "SELECT * FROM " . $table;
			$db_erg = mysqli_query( $db_link, $sql );
			
			// Die erste Nummer wird gespeichert
			$zeile = mysqli_fetch_array($db_erg);
			$idEins = $zeile[$name_of_column];
			
			//Nun überprüft das Script, welche Nummer frei ist
			while($zeile = mysqli_fetch_array($db_erg)){
				
				if($zeile[$name_of_column] > $idEins){
					
					$idEins = $zeile[$name_of_column];
					
				}
			
			}

			mysqli_free_result( $db_erg );

			$id = $idEins + 1;
			
			return $id;
			
		}

		public static function find_all_data($sql, $doc) {
			
			// find_all_data
			// Baut eine SQL Verbindung auf und liefert alle Werte einer gewünschten Spalte zurück
			
			// In $db_link wird eine mysql Verbindung gespeichert
			$db_link = mysqli_connect("localhost", "root", "", "Druckerverwaltungstool");
			
			// In db_erg wird das Ergebnis der SQL Abfrage gespeichert
			$db_erg = mysqli_query( $db_link, $sql );
			
			// Speichert alle gewünschten Einträge ( die im Array $doc vorhanden sind ) im Array $erg
			$erg = [];

			if($db_erg == '')
				return false;

			// Solange in der Variable db_erg Einträge sind
			while ($zeile = mysqli_fetch_array($db_erg)) {

			    // Speichert in $erg die gefundenen Ergebnise
                for ($i = 0; $i != count($doc); $i++)
                    $erg += [ count($erg) => $zeile[ $doc[$i] ] ];

			}
			
			return $erg;
			
		}

		public static function find_data_array($sql, $doc) {

            // find_data_array
            // Baut eine SQL Verbindung auf und liefert alle Werte in einem zweidimensionalen Array zurück

            // In $db_link wird eine mysql Verbindung gespeichert
            $db_link = mysqli_connect("localhost", "root", "", "Druckerverwaltungstool");

            // In db_erg wird das Ergebnis der SQL Abfrage gespeichert
            $db_erg = mysqli_query( $db_link, $sql );

            // Speichert alle gewünschten Einträge ( die im Array $doc vorhanden sind ) im zweidimensionalen Array $erg
            $erg = [];

            if($db_erg == '')
                return false;

            // Solange in der Variable db_erg Einträge sind
            while ($zeile = mysqli_fetch_array($db_erg)) {

                $aTempErg = [];

                // Speichert in $aTempErg die Ergebnisse des SQL-Abrufes
                for ($i = 0; $i != count($doc); $i++)
                    $aTempErg += [ count($aTempErg) => $zeile[$doc[$i]] ];

                /*  Speichert die Ergebnisse des eindimensionalen Arrays $aTempErg
                    in den zweidimensionalen Array $erg */
                $erg += [ count($erg) => $aTempErg ];

            }

            return $erg;

        }

		public static function get_toner_warning($id, $return_warning) {

		    // In $toner_info werden alle Toner gespeichert, die zum Drucker gehören
			$toner_info = get_sql_data::find_all_data('SELECT * FROM toner WHERE printerID=' . $id, [ 0 => 'ID' ]);
            $addon_info = self::find_all_data('SELECT * FROM addon WHERE printerID=' . $id, [ 0 => 'ID' ]);

			$warning = [];

            for ($i = 0; $i != count($toner_info); $i++) {

                $quantity = get_sql_data::find_all_data('SELECT * FROM toner WHERE ID=' . $toner_info[$i], [ 0 => 'quantity', 1 => 'min_quantity', 2 => 'color' ]);

                if ($quantity['0'] <= $quantity['1']){

                    switch ($quantity['2']){

                        case 'black':
                            $color_translate = 'Schwarz';
                            break;

                        case 'cyan':
                            $color_translate = 'Cyan';
                            break;

                        case 'yellow':
                            $color_translate = 'Gelb';
                            break;

                        case 'magenta':
                            $color_translate = 'Magenta';
                            break;

                    }

                    $warning += [ count($warning) => 'Farbe ' . ucfirst($color_translate) ];
                    $warning += [ count($warning) => $quantity['0'] ];

                }

            }

            for ($i = 0; $i != count($addon_info); $i++) {

                $quantity = get_sql_data::find_all_data('SELECT * FROM addon WHERE ID=' . $addon_info[$i], [ 0 => 'quantity', 1 => 'min_quantity', 2 => 'typ' ]);

                if ($quantity['0'] < $quantity['1']){

                    $warning += [ count($warning) => 'Typ ' . ucfirst($quantity['2']) ];
                    $warning += [ count($warning) => $quantity['0'] ];

                }

            }

            return ($return_warning) ? $warning : count($warning) / 2;

        }

	}
	
?>