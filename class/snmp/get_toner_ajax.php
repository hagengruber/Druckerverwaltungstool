<?php

	// get_toner.php
	// Gibt Tonerbestände aus

	include_once('./snmp_number.php');
	include_once('./snmp_bestand.php');

    class get_toner {

        public static function get_toner_level($ip) {

            // Speichert in $aColorPrinter alle Farben, die der Drucker drucken kann ( für mehr Details, siehe snmp_number.php )
            $aColorPrinter = get_toner_number($ip);

            $aColor = [

                0 => 'cyan',
                1 => 'yellow',
                2 => 'magenta',
                3 => 'black'

            ];

            $aReturn = [];

            for ($i = 0; $i != count($aColor); $i++) {

                if (isset($aColorPrinter[$aColor[$i]])) {

                    // Speichere den Bestand des Toners in Prozent in die Variable $level
                    $level = get_toner_bestand($ip, $aColorPrinter[$aColor[$i]]);

                    // Wenn $level größer oder gleich 0 ist, dann stimmt der Wert und es ist kein Fehler aufgetreten
                    // Wenn nicht, dann stimmt die OID Nummer des Toners nicht
                    if ($level >= 0) {

                       $aReturn += [ count($aReturn) => [ 0 => $aColor[$i], 1 => $level ] ];

                    }

                }

            }

            return $aReturn;

        }

        public static function get_toner_return($ip) {

            // Speichert in $ausgabe alle Farben und OIDs, die der Drucker drucken kann ( für mehr Details, siehe snmp_number.php )
            $ausgabe = get_toner_number($ip);
			
            // $warning wird auf true gesetzt, wenn Toner unter 5% ist
            $warning = false;

            $counter = 0;
            $color = [

                0 => 'cyan',
                1 => 'yellow',
                2 => 'magenta',
                3 => 'black'

            ];

            while ($counter != count($color)) {

                if (isset($ausgabe[$color[$counter]])) {

                    // Speichere den Bestand des Toners in Prozent in die Variable $bestand ( für die Farbe Cyan )
                    // $bestand = get_toner_bestand($ip, $ausgabe[$color[$counter]]);

					for ($i = 0; $i != count($color); $i++) {
						
						// Da snmp momentan nicht zur verfügung steht, ist dieser teil fest
						switch($color[$counter]) {
							
							case 'cyan':
								$bestand = round(get_toner_bestand($ip, $ausgabe[$color[$counter]]));
								break;
							
							case 'yellow':
								$bestand = round(get_toner_bestand($ip, $ausgabe[$color[$counter]]));
								break;
								
							case 'magenta':
								$bestand = round(get_toner_bestand($ip, $ausgabe[$color[$counter]]));
								break;
								
							case 'black':
								$bestand = round(get_toner_bestand($ip, $ausgabe[$color[$counter]]));
								break;
							
						}
						
					}
					
                    // $bestand = 0;
                    // $warning wird auf true gesetzt, wenn Toner unter 5% ist
                    if ($bestand <= 20 && $bestand >= 0)
                        $warning = true;

                    // Gib nun den Bestand aus

                    // Wann soll Warnung ausgegeben werden?
					
                    if ($bestand >= 0) {

                        echo '
                    
                            <div class="druckereigenschaften_bestand" title=" ' . $bestand . '%">
                                
                                <div class="druckereigenschaften_bestand_inline" style="background-color: ' . $color[$counter] . '; width: ' . ($bestand - 0.1) . '%;"></div>
                                
                            </div>
                        
                        ';

                    } else {

                        switch ($color[$counter]) {

                            case 'black':
                                $color_g = 'Schwarz';
                                break;
                            case 'magenta':
                                $color_g = 'Magenta';
                                break;
                            case 'yellow':
                                $color_g = 'Gelb';
                                break;
                            case 'cyan':
                                $color_g = 'Cyan';
                                break;

                        }

                        echo '
                        
                            Toner ' . $color_g . ' nicht kompatibel
                        
                        ';

                    }

                }

                $counter++;

            }

            // Wenn die Variable $bestand nicht deklariert ist, wird eine Fehermeldung ausgegeben
            if (!isset($bestand)) {

                echo 'Keine Toner gefunden';

            }

            // Warnung wird zuückgegeben
            return $warning;

        }

    }
	
	header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
	echo get_toner::get_toner_return($_POST['ip']);
	exit;

?>