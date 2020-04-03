<?php
	
	/*
		
		Author: 					Hagengruber Florian
									f.hagengruber@schock.de
									+49 9921 600 290
		
	*/
	
	// kontakte_verwalen_include.php
	
	class manage_contact{

		public static function add_contact($name, $adress, $phonenumber, $website){
			
			// get_free() lieferte eine freie ID zurück
			$id = get_sql_data::get_free('contact','ID');
			
			$line = '`ID`, `name`, `adress`, `phonenumber`, `website`';
			$new_line = $id . ', \'' . $name . '\', \'' . $adress . '\', \'' . $phonenumber . '\', \'' . $website . '\'';
			
			put_sql_data::put_data('contact', $line, $new_line);
			
		}

		public static function delete_contact($iID) {

			// Erst prüfen, ob Kontakt ein Fremdschlüssel in einer Tabelle ist

			if (self::is_fk($iID)) {

				echo ' <script> alert("Fehler: Kontakt ist noch in Verwendung. Prüfen: Zubehör, Drucker, Offene Bestellungen"); </script> ';
				return -1;

			}

			put_sql_data::del_data('contact', 'ID', $iID);

		}

		public static function is_fk($iID) {

			$aControl = get_sql_data::find_all_data('SELECT * FROM orders WHERE contactID=' . $iID, [ 0 => 'contactID' ]);
			$aControl += get_sql_data::find_all_data('SELECT * FROM addon WHERE contactID=' . $iID, [ 0 => 'contactID' ]);
			$aControl += get_sql_data::find_all_data('SELECT * FROM printer WHERE contactID=' . $iID, [ 0 => 'contactID' ]);
			$aControl += get_sql_data::find_all_data('SELECT * FROM toner WHERE contactID=' . $iID, [ 0 => 'contactID' ]);

			return isset($aControl['0']) ? true : false;

		}

		public static function show_contact(){

			$aContactInfo = get_sql_data::find_data_array('SELECT * FROM contact', [ 0 => 'ID', 1 => 'name', 2 => 'adress', 3 => 'phonenumber', 4 => 'website']);
			
			echo '
				
				<center>
				
				<div class="table_box">
					
					<table>
					
						<tr>
							<th> <img src="images/kontakt_icon.png" height="150px"> </th>
						
					<th>
					
					<table class="table">
								
								<tr>
									<th> Name </th>
									<th> Adresse </th>
									<th> Telefonnummer </th>
									<th> Website </th>
									<th> Löschen </th>
								</tr>

			';
			
			for($i = 0; $i != count($aContactInfo); $i++){
				
				echo '
					
					<form action="" method="post">
					
						<tr>
						
							<td> ' . $aContactInfo[$i]['1'] . ' </td>
							<td> ' . $aContactInfo[$i]['2'] . ' </td>
							<td> ' . $aContactInfo[$i]['3'] . ' </td>
							<td> ' . $aContactInfo[$i]['4'] . ' </td>
							<td> <input type="submit" class="simple_button" name="delete_contact" value="X"> </td>
							<input type="hidden" name="contactID" value="' . $aContactInfo[$i]['0'] . '">
						
						</tr>
				
					</form>
				
				';
				
			}
			
			echo '
								
								<tr>
								<form action="" method="POST">
								<td> <input placeholder="Kontaktnamen eingeben" name="name" required=""> </td>
								<td> <input placeholder="Adresse eingeben" name="adress"> </td>
								<td> <input placeholder="Telefonnummer eingeben" name="phonenumber" type="tel"> </td>
								<td> <input placeholder="Website eingeben" name="website" type="url" value="https://"> </td>
								
								<td> <input type="submit" value="Kontakt hinzufügen" name="add_contact" class="simple_button"> </td>
								<input type="hidden" value="Kontakte verwalten" name="site">
								</tr>
							</table>
							
						</div>
						
					</div>
					
				</center>
				
			';
			
		}
		
	}
	
?>
