<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			13.09.2019
		
		Letztes Änderungsdatum: 	13.09.2019
		
	*/
	
	// druckereigenschaften_include.php
	// Funktionen für Druckereigenschaften.php
	
	class properties{
		
		static $id;
		static $name;
		static $ip;
		static $location;
		static $toner_cyan;
		static $toner_magenta;
		static $toner_black;
		static $toner_yellow;

        public static function set_var($id) {
			
			self::$id = $id;

			// find_all_data gibt anhand einer ID alle Einträge als Array in der jeweilgen Spalte zurück
			$aPrinterInfo = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . self::$id, [ 0 => 'name', 1 => 'ip', 2 => 'locationID' ]);
			
			// Speichert den Namen in self::$name
			self::$name = $aPrinterInfo['0'];
			// Speichert die IP in self::$ip
			self::$ip = $aPrinterInfo['1'];

			// Speichert den Namen des Standortes in self::$location
			$location = get_sql_data::find_all_data('SELECT * FROM location WHERE ID=' . $aPrinterInfo['2'], [ 0 => 'name' ]);
			self::$location = $location['0'];

			$aLevel = get_toner::get_toner_level(self::$ip);

			// Zerteilt den $level-Array und speichert die Infos
			for($i = 0; $i != count($aLevel); $i++){
				
				$sColor = $aLevel[$i]['0'];
				$iLevel = $aLevel[$i]['1'];
				
				switch($sColor){
					
					case 'cyan':
						self::$toner_cyan = [
							
							0 => $sColor,
							1 => $iLevel
							
						];
					break;
					
					case 'black':
						self::$toner_black = [
							
							0 => $sColor,
							1 => $iLevel
							
						];
					break;
					
					case 'yellow':
						self::$toner_yellow = [
							
							0 => $sColor,
							1 => $iLevel
							
						];
					break;
					
					case 'magenta':
						self::$toner_magenta = [
							
							0 => $sColor,
							1 => $iLevel
							
						];
					break;
					
				}
				
			}
			
		}

        public static function put_header() {
			
			echo '
				
				<div style="margin-top: 450px;">
				
				<center>
					
					<form action="./index.php" mehtod="POST">

						<input value="Einstellungen" type="submit" class="submit_button_submenu" style="float: right;">
						<input type="hidden" name="site" value="Einstellungen">
						<input type="hidden" name="printer" value="' . self::$id . '">
						
					</form>
					
					<form action="./index.php" mehtod="POST">

						<input value="Bestellen" type="submit" class="submit_button_submenu_bestellung" style="float: right;">
						<input type="hidden" name="site" value="Bestellen">
						<input type="hidden" name="printer" value="' . self::$id . '">
						
					</form>
					
					<form action="./index.php" mehtod="POST">

						<input value="Zubehör ausgeben" type="submit" class="submit_button_submenu_ausgeben" style="float: right;">
						<input type="hidden" name="site" value="Zubehör ausgeben">
						<input type="hidden" name="printer" value="' . self::$id . '">
						
					</form>
					
					<div class="druckereigenschaften_location">
						
						' . ucfirst(self::$location) . '
						
					</div>
					
					<div class="druckereigenschaften_box">
						
						<img src="images/drucker_icon.png" height="150px" style="float: left;">
						
						<div class="druckereigenschaften_text">
						
							<a target="_blank" href="http://' . self::$ip . '">' . self::$name . '</a>
						
			';
			
		}

        public static function put_toner() {
			
			$black = false;
			$cyan = false;
			$magenta = false;
			$yellow = false;
			
			$control = [
				
				0 => self::$toner_cyan['1'],
				1 => self::$toner_black['1'],
				2 => self::$toner_magenta['1'],
				3 => self::$toner_yellow['1']
				
			];
			
			for($i = 0; $i != count($control); $i++){
				
				switch($i){
					
					case 0:
					$color = 'cyan';
					$color_out = 'cyan';
					break;
					
					case 1:
					$color = 'black';
					$color_out = 'schwarz';
					break;
					
					case 2:
					$color = 'magenta';
					$color_out = 'magenta';
					break;
					
					case 3:
					$color = 'yellow';
					$color_out = 'gelb';
					break;
					
				}
				
				if($control[$i] >= 0){
					
					if($control[$i] <= 5){
						
						echo '
					
							<div class="druckereigenschaften_bestand" id="warning" title=" ' . $control[$i] . '%">
								
								<div class="druckereigenschaften_bestand_inline" style="background-color: ' . $color. '; width: ' . $control[$i] . '%;"></div><div style="float: right;"></div>
								
							</div>
						
						';
						
					} else {
						
						echo '
					
							<div class="druckereigenschaften_bestand" title=" ' . $control[$i] . '%">
								
								<div class="druckereigenschaften_bestand_inline" style="background-color: ' . $color . '; width: ' . $control[$i] . '%;"></div><div style="float: right;"></div>
								
							</div>
						
						';
						
					}
					
				} else {
					
					echo 'Toner ' . ucfirst($color_out) . ' nicht kompatibel';
					
				}
				
			}
			
			echo ' </div> ';
			
		}

        public static function put_toner_warning() {
			
			$warning_toner = 0;
			
			// In $warning_toner wird die warnung gespeichert

			$warning_toner = get_sql_data::get_toner_warning(self::$id, true);

			if(isset($warning_toner['0'])) {

                echo ' Warnung: <table style="border: 1px solid black; padding: 5px; margin-top: 5px; text-align: left;"> ';
				
				for($i = 0; $i != count($warning_toner); $i++){
					
					echo ' <tr><th> ' . ucfirst($warning_toner[$i++]) . ': ' . $warning_toner[$i] . ' Stück </th></tr>';
					
				}
				
				echo ' </table> ';
				
			}
			
			echo '
					
					</div>
					
				</center>
				
			';
		
		}
		
	}
	
?>