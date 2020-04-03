<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			16.09.2019
		
		Letztes Änderungsdatum: 	16.09.2019
		
	*/
	
	// toner zuweisen.php
	// Weisst Toner Drucker hinzu
	
	// Bindet put_data.php ein
	include_once('./class/sql/put_data.php');
	include_once('./class/sql/get_data.php');
	
	if(isset($_POST['add_toner'])){
		
		// get_free() lieferte eine freie ID zurück
		$id = get_free('toner','Toner_id');
		
		$line = '`Toner_id`, `Toner_Name`, `Toner_Farbe`, `Kontakt_id`, `Toner_anzahl`, `is_free`';
		$new_line = $id . ', \'' . $_POST['name'] . '\', \'' . $_POST['color'] . '\', 0, 0, 1';
		
		put_data('toner', $line, $new_line);
		
	}
	
	// get_data.php für SQL Abfrage einbinden
	include_once('./class/sql/get_data.php');
	// get_all_data('toner', 'toner_id') aufrufen, um alle Informationen für alle toner zu speichern
	// In $all_toner_id werden alle toner IDs alls Array gespeichert
	$all_toner_id = get_all_data('toner', 'Toner_id');
	// get_all_data('toner', 'Toner_Name') aufrufen, um alle Informationen für alle toner zu speichern
	// In $all_Name werden alle toner Namen alls Array gespeichert
	$all_Name = get_all_data('toner', 'Toner_Name');
	// get_all_data('toner', 'Toner_Farbe') aufrufen, um alle Informationen für alle toner zu speichern
	// In $all_farbe werden alle Toner IDs alls Array gespeichert
	$all_farbe = get_all_data('toner', 'Toner_Farbe');
	// get_all_data('toner', 'Kontakt_id') aufrufen, um alle Informationen für alle toner zu speichern
	// In $all_kontakt werden alle Toner IDs alls Array gespeichert
	$all_kontakt = get_all_data('toner', 'Kontakt_id');
	// get_all_data('toner', 'Toner_anzahl') aufrufen, um alle Informationen für alle toner zu speichern
	// In $all_anzahl werden alle Toner IDs alls Array gespeichert
	$all_anzahl = get_all_data('toner', 'Toner_anzahl');
	
	// Im Array $doc werden alle benötigten Infos gespeichert
	$doc = [
		
		0 => 'Name',
		1 => 'Drucker_id',
		2 => 'Kontakt_id',
		3 => 'Bestellung_id',
		4 => 'Zubehoer_id',
		5 => 'Standort',
		6 => 'IP'
		
	];
	
	// find_data('drucker', 'Drucker_id', $_GET['printer'], $doc)
	// Gibt anhand einer ID alle Einträge als Array in der jeweilgen Spalte zurück
	$erg = find_data('drucker', 'Drucker_id', $_GET['printer'], $doc);
	
	if(isset($_POST['chgStatus'])){
		
		if($_POST['status'] == 'Ja'){
			
			$toner_id = 0;
			update_data('toner', 'Toner_id', $_POST['toner_id'], 'is_free', 0);
			
		} else {
			
			$toner_id = $_POST['toner_id'];
			update_data('toner', 'Toner_id', $_POST['toner_id'], 'is_free', 1);
			
		}
		
		switch($_POST['toner_farbe']){
			
			case 'Schwarz':
				$data = 'Toner_black_id';
			break;
			
			case 'Gelb':
				$data = 'Toner_yellow_id';
			break;
			
			case 'Magenta':
				$data = 'Toner_magenta_id';
			break;
			
			case 'Cyan':
				$data = 'Toner_cyan_id';
			break;
			
		}
		
		update_data('drucker', 'Drucker_id', $erg['1'], $data, $toner_id);
		
	}
	
	echo '
		
		<center>
		
			<div class="druckereigenschaften_location">
					
				' . $erg['5'] . '
					
			</div>
			
			<div class="table_box">
				
				<table>
				
					<tr>
					
						<th> <img src="images/drucker_icon.png" height="150px" style="float: left; margin-top: 40px;"> </th>
					
						<th>
				
							<table class="table">
							
								<tr>
									<th> Tonername </th>
									<th> Tonerfarbe </th>
									<th> Status </th>
								</tr>
		
	';
	
	$counter = 0;
	
	while($counter != count($all_toner_id)){
		
		switch($all_farbe[$counter]){
			
			case 'Schwarz':
				$doc = [ 0 =>  "Toner_black_id"];
				$a = find_def_data('drucker', 'SELECT * FROM drucker WHERE Drucker_id = ' . $erg['1'], 'Toner_black_id', $all_toner_id[$counter], $doc);
			break;
			
			case 'Gelb':
				$doc = [ 0 =>  "Toner_yellow_id"];
				$a = find_def_data('drucker', 'SELECT * FROM drucker WHERE Drucker_id = ' . $erg['1'], 'Toner_yellow_id', $all_toner_id[$counter], $doc);
			break;
			
			case 'Magenta':
				$doc = [ 0 =>  "Toner_magenta_id"];
				$a = find_def_data('drucker', 'SELECT * FROM drucker WHERE Drucker_id = ' . $erg['1'], 'Toner_magenta_id', $all_toner_id[$counter], $doc);
			break;
			
			case 'Cyan':
				$doc = [ 0 =>  "Toner_cyan_id"];
				$a = find_def_data('drucker', 'SELECT * FROM drucker WHERE Drucker_id = ' . $erg['1'], 'Toner_cyan_id', $all_toner_id[$counter], $doc);
			break;
			
		}
		
		if(count($a) == 0){
			
			$status = 'Nein';
			
		} else {
			
			$status = 'Ja';
			
		}
		
		$doc = [ 0=> 'is_free' ];
		$sql = 'SELECT * FROM toner WHERE toner_id=' . $all_toner_id[$counter];
		
		$is_free = find_def_data_all('toner', $sql, $doc);
		
		if($is_free['0'] == '1' && $status == 'Nein'){
			
			
			
		} else {
			
			echo '
			
				<form action="" method="POST">
				
					<tr>
					
						<td> ' . $all_Name[$counter] . ' </td>
						<td> ' . $all_farbe[$counter] . ' </td>
						<td> <input value="' . $status . '" type="submit" name="chgStatus"> </td>
					
					</tr>
					
					<input type="hidden" name="status" value="' . $status . '">
					<input type="hidden" name="toner_id" value="' . $all_toner_id[$counter] . '">
					<input type="hidden" name="toner_farbe" value="' . $all_farbe[$counter] . '">
					
				</form>
				
			';
			
		}
		
		
		
		
		$counter++;
		
	}
	
	echo '
						
					</table>
					
				</div>
				
			</div>
			
		</center>
		
	';
	
?>
