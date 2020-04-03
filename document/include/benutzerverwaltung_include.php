<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
		
	*/
	
	// benutzerverwaltung_include.php
	// Beinhaltet Funktionen von Benutzerverwaltung.php
	
	class benutzerverwaltung{
		
		// Fügt in der SQL Datenbank einen neuen Benutzer hinzu
		public static function add_user($add_user){
			
			// ###################### VARIABLEN DEKLARIEREN ######################
			
				// In $id wird eine freier Benutzer-ID gespeichert
				$id = get_sql_data::get_free('user', 'ID');
				// SQL Abfrage
				$line = '`ID`, `name`';
				$new_line = $id . ', \'' . $_POST['add_user'] . '\'';
				
			// ###################### VARIABLEN DEKLARIEREN ENDE ######################
			
			// In put_data werden die Daten in eine SQL Tabelle geschrieben
			put_sql_data::put_data('user', $line, $new_line);
			
		}
		
		// Löscht in der SQL Datenbank einen Benutzer
        public static function delete_user(){
			
			// del_data löscht den Benutzer
			put_sql_data::del_data('user', 'ID', $_POST['del_user']);
			
		}
		
		// Gibt alle Benutzer in der SQL Datenbank an
        public static function show_all_users(){

            // In dem Array $name werden alle Benutzer-ID's und alle Benutzernamen gespeichert
            $aUserInfo = get_sql_data::find_data_array('SELECT * FROM user', [ 0 => 'ID', 1 => 'name' ]);

			// Gib alle Benutzer aus
			echo '
				
				<center>
		
					<div class="table_box">
						
						' . /* Hier wird das benutzer_editieren Bild angezeigt */ '
						<table>
						
							<tr>
								<th> <img src="images/benutzer_editieren.png" height="150px"> </th>
							
						<th>
						
						<table class="table">
									
									<tr>
										<th> Name </th>
										<th> Löschen </th>
									</tr>
			
			';
			
			for($i = 0; count($aUserInfo) != $i; $i++){
				
				// Gib Eintrag aus
				echo '
					
					<form action="" method="POST">
					
						<input type="hidden" name="del_user" value="' . $aUserInfo[$i]['0'] . '">
						
						<tr>
							<td>
				
								' . $aUserInfo[$i]['1'] . '
						
							</td>
							<td> <input type="submit" value="X" class="simple_button"> </td>
							
						</tr>
					
					</form>
					
				';
				
			}
			
		}

        public static function show_user_form(){
			
			echo '
			
				<form action="" method="POST">
					
					<tr>
					
						<td> <input name="add_user" required="" placeholder="Benutzernamen eingeben"> </td>
						
						<td> <input value="Hinzufügen" type="submit" class="simple_button"> </td>
						
					</tr>
					
				</form>
				
			';
			
		}
		
	}

?>