<?php
	
	// druckerübersucht_include.php
	// Funktionen für druckerübersucht
	
	// In der Klasse Warning werden alle Warnungen und Fehler gespeichert
	class warning{

		static $counter_error_messages = 0;
		static $counter_warning_messages = 0;
		
	}
	
	class overview{
		
		static $id;
		static $name;
		static $contact;
		static $customID;
		static $location;
		static $ip;
		static $tonerID;
		static $color;

		// Funktion put_header
		// Gibt Header für Ausgabe zurück
		public static function put_header() {
			
			return '
				
				<form action="" method="GET"> <input type="hidden" name="site" value="Meldungen"> <input name="click" type="submit" id="click" style="color: transparent; background-color: transparent; border: none;"> </form>
				
				<table style="width: 80%; text-align: center; border-collapse:collapse">
			
					<tr id="messages">
						
						<td class="messages_td" style="height: 60px;" id="messages_one" onclick="document.getElementById(\'click\').click()"> </div> </td>
						<td class="messages_td" style="height: 60px;" id="messages_two" onclick="document.getElementById(\'click\').click()"> </td>
						
					</tr>
				
				</table>
				
				<table style="width: 80%; text-align: center; border-collapse:collapse">
				
			';
			
		}
		
		// Funktion set_var
		// Setzt Variablen
		public static function set_var($id) {
			
			// Speichere in $doc alle Spaltennamen, die gefunden werden sollen
			$doc = [
				
				0 => 'name',
				1 => 'ip',
				2 => 'customID',
				3 => 'locationID',
				4 => 'contactID',
				
			];

			$printer_info = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $id, $doc);
			
			// Speichert die Ergebnise in die Variablen
			self::$id = $id;
			self::$name = $printer_info['0'];
			self::$ip = $printer_info['1'];
			self::$customID = $printer_info['2'];
			
			/*
			if ($id == 1) {
				
				self::$name = 'PRN021';
				self::$ip = '0.0.0.0';
				self::$customID = '0254953321';
				self::$location = 'Verpackung';
				self::$contact = 'OffIts';
				
			} else {
				
				self::$name = 'PRN056';
				self::$ip = '0.0.0.1';
				self::$customID = '01985469';
				self::$location = 'Finanzbuchhaltung';
				self::$contact = 'OffIts';
				
			}
			*/
			
			// In $printer_info['3'] ist nur ein Fremdschlüssel gespeichert
			// Die benötigten Daten werden nun anhand des Fremdschlüssels geholt
			// Speichere in $doc alle Spaltennamen, die gefunden werden sollen
				$doc = [ 0 => 'name' ];
			// In $search werden alle gefundenen Daten gespeichert
				$search = get_sql_data::find_all_data('SELECT name FROM location WHERE ID=' . $printer_info['3'], $doc);
			// Wenn nichts gefunden wurde
			if(!isset($search['0']))
				// Setzte die Stelle 0 des Arrays search auf False
				$search = [ 0 => 'false' ]; 
			
			// Speichere die erste Stelle des Arrays search in die Variable location
			self::$location = $search['0'];
			
			// In $printer_info['4'] ist nur ein Fremdschlüssel gespeichert
			// Die benötigten Daten werden nun anhand des Fremdschlüssels geholt
			// Speichere in $doc alle Spaltennamen, die gefunden werden sollen
			$doc = [ 0 => 'name' ];
			// In $search werden alle gefundenen Daten gespeichert
			$search = get_sql_data::find_all_data('SELECT name FROM contact WHERE ID=' . $printer_info['4'], $doc);
			// Wenn nichts gefunden wurde
			if(!isset($search['0']))
				// Setzte die Stelle 0 des Arrays search auf False
				$search = [ 0 => false ];
			
			// Speichere die erste Stelle des Arrays search in die Variable contact
			self::$contact = $search['0'];
			
			
		}
		
		public static function get_tonerlevel() {

			return [ 0 => [ 0 => 'Cyan', 1 => '10'] ];
			
		}
		
		public static function set_toner_var($color) {

			// Speichere die Farbe des Toners in $toner_color
			self::$color = $color;

			$toner_id = get_sql_data::find_all_data('SELECT * FROM toner WHERE printerID=' . self::$id . ' AND color=\'' . self::$color . '\'', [ 0 => 'ID' ]);

			// $toner_id = [ 0 => self::$id ];

			if (!isset($toner_id['0']))
			    return false;

            // Speichere in tonerID die aktuelle Toner ID
            self::$tonerID = $toner_id['0'];

		}
		
		public static function put_printer_control() {

            // In $aTonerLevel werden alle Toner des Druckers und deren Stände gespeichert
            $aTonerLevel = get_toner::get_toner_level(self::$ip);

            for ($i = 0; $i != count($aTonerLevel); $i++) {

                if ($aTonerLevel[$i]['0'] == self::$color) {

                    $aControlTonerColor = get_sql_data::find_all_data('SELECT * FROM toner WHERE printerID=' . self::$id . ' AND COLOR=\'' . self::$color . '\'', [ 0 => 'ID', 1 => 'level' ]);

                    if (isset($aControlTonerColor['0'])) {

                        if ($aTonerLevel[$i]['1'] > $aControlTonerColor['1']) {

                            warning::$counter_error_messages++;

                        } else {

                            put_sql_data::update_data('toner', 'ID', $aControlTonerColor['0'], 'level', $aTonerLevel[$i]['1']);

                        }

                    }

                }

            }

		}
		
		public static function put_body() {
			
			if(self::$location == 'false')
				self::$location = '- Standort nicht gefunden -';
			
			echo '
				
				<td>
				
				<a href="index.php?site=Druckereigenschaften&printer=' . self::$id . '">
				
					<span class="printer_box">
					
						<span id="' . self::$id . '" class="printer_box_location"> ' . ucfirst(self::$location) . ' </span>
						
						<div class="printer_box_image">
								
								<img src="./images/drucker_icon.png" class="img">
								
								<div class="printer_box_text" style="height: 1px; top: 1px;">
									
									' . self::$name;

									$warningToner =  get_toner::get_toner_return(self::$ip);
									$error = get_sql_data::get_toner_warning(self::$id, false);
									
									if($error != 0) {
										
										warning::$counter_warning_messages++;
										
										// Ändere Style von Class printer_box in JavaScript
										echo '
											
											<script>
												
												var element = document.getElementById("' . self::$id . '");
												element.setAttribute("style", "animation: blink_warning infinite 2s");
												
											</script>
											
										';
										
									} elseif($warningToner) {
										
										// Ändere Style von Class printer_box in JavaScript
										echo '
											
											<script>
												
												var element = document.getElementById("' . self::$id . '");
												element.setAttribute("style", "border-top: 3px solid #B7B336");
												
											</script>
											
										';
										
									}
								
								echo '
			
								</div>
							
						</div>
					
					</span>
					
				</a>
				
				</td>
				
			';
			
		}
		
		public static function put_script() {
			
			echo '
				
				<script>
					
					document.getElementById("messages_one").innerHTML = " <input class=\"messages_button\" type=\"submit\" value=\"Fehler ( ' . warning::$counter_error_messages . ' )\"> "
					document.getElementById("messages_two").innerHTML = " <input class=\"messages_button\" type=\"submit\" value=\"Warnungen ( ' . warning::$counter_warning_messages . ' )\"> "
					
				</script>
				
				</table>
			
			';
			
		}

		public static function put_warning() {
			
		    if (isset($_COOKIE['order'])) {
				
				$printer = explode(';', $_COOKIE['order']);

				unset($printer[count($printer) - 1]);

				// Erstelle einen zweidimensionalen Array
				// Diese enthalten die TonerID und die Anzahl
				$toner_orders = [];

				for ($i = 0; $i != count($printer); $i++)
					$toner_orders += [ count($toner_orders) => explode(',', $printer[$i]) ];
				
		        echo '
		            
		            <div class="warning_outer">
		                
		                <div class="warning_head"> Ups... </div>
                        
                        Scheint so, als ob du noch Artikel im <a href="index.php?site=Warenkorb&printer=' . $toner_orders['0']['3'] . '">Warenkorb</a> hättest
                        
                    </div>
		            
		        ';

            }

        }

	}
	
?>