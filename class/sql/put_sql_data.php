<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			12.09.2019
		
		Letztes Änderungsdatum: 	12.09.2019
		
	*/
	
	// put_data.php
	// Baut eine SQL Verbindung auf und schreibt Daten in eine Tabelle
	
	class put_sql_data{
	
		public static function put_data($table, $exist_table, $new_table){

			// put_data
			// Baut eine SQL Verbindung auf und schreibt Daten in eine Tabelle
			
			// In $db_link wird eine mysql Verbindung gespeichert
			$db_link = mysqli_connect("localhost", "root", "", "Druckerverwaltungstool");
			
			$sql = "
			
				INSERT INTO `" . $table . "`
				( 
				" . $exist_table . "
				) 
				VALUES
				(
				" . $new_table . "
				);
			
			";
			
			// In db_erg wird das Ergebnis der SQL Abfrage gespeichert
			$db_erg = mysqli_query( $db_link, $sql );
			
		}

		public static function del_data($table, $name_of_row, $searchword){
			
			// del_data
			// Baut eine SQL Verbindung auf und löscht Daten
			
			// In $db_link wird eine mysql Verbindung gespeichert
			$db_link = mysqli_connect("localhost", "root", "", "Druckerverwaltungstool");
			
			$sql = "
			
				DELETE FROM " . $table . "
				WHERE " . $name_of_row . " = '" . $searchword . "'
			
			";
			
			// In db_erg wird das Ergebnis der SQL Abfrage gespeichert
			$db_erg = mysqli_query( $db_link, $sql );
			
		}

		public static function update_data($table, $keyword, $keyword_value, $data, $data_value){
			
			// update_data
			// Baut eine SQL Verbindung auf und upddatet einzelne Daten
			
			// In $db_link wird eine mysql Verbindung gespeichert
			$db_link = mysqli_connect("localhost", "root", "", "Druckerverwaltungstool");
			
			if($keyword == '') {
				
				$sql = " UPDATE " . $table . " SET " . $data . " = " . $data_value; 
				
			} else {
				
				$sql = "
				
					UPDATE " . $table . " SET " . $data . " = " . $data_value . " WHERE " . $keyword . " = " . $keyword_value . " 
				
				";
				
			}
			
			// In db_erg wird das Ergebnis der SQL Abfrage gespeichert
			$db_erg = mysqli_query( $db_link, $sql );
			
		}
	
	}
	
?>