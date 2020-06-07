<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			12.09.2019
		
		Letztes Änderungsdatum: 	12.09.2019
		
	*/
	
	// snmp_number.php
	// Dokumentenbeschreibung
	
	// Funktion get_toner_number
	// Diese Funktion gibt jeweiligen OIDs mit den Farben zurück, die Tonerbesände gespeichert haben
	function get_toner_number($ip) {
		
		// Die möglichen IODs, in den Tonerkapazitäten gespeichert werden, werden in einen Array gespeichert
		$oids = array(
	
			"0" => ".1.3.6.1.2.1.43.11.1.1.6.1.1",
			"1" => ".1.3.6.1.2.1.43.11.1.1.6.1.2",
			"2" => ".1.3.6.1.2.1.43.11.1.1.6.1.3",
			"3" => ".1.3.6.1.2.1.43.11.1.1.6.1.4",
			"4" => ".1.3.6.1.2.1.43.11.1.1.6.1.5",
			"5" => ".1.3.6.1.2.1.43.12.1.1.4.1.1",
			"6" => ".1.3.6.1.2.1.43.12.1.1.4.1.2",
			"7" => ".1.3.6.1.2.1.43.12.1.1.4.1.3",
			"8" => ".1.3.6.1.2.1.43.12.1.1.4.1.4"
			
		);
		
		// In $end wird die größe des Array $oids gespeichert
		$end = count($oids);
		$i = 0;
		// In $return werden die Farben gespeichert, die der Drucker drucken kann und die jeweiligen IODS
		$return = [];
		
		// Solange noch unbehandelte Datensätze in $oids sind ( noch nicht getestete IODs )
		while($end != $i){
			
			// Speichere in die Variable $temp den SNMP-Array mit der jeweils ausgewählten OID Nummer
			// Erhöhe dann den Zähler $i um 1
			// @$temp = snmpwalk($ip, 'public', $oids[$i++]);
			
			$session = new SNMP(SNMP::VERSION_1, $ip, "public");
			
			$temp = $session->walk($oids[$i++]);
			
			$session->close();
			
			// Konvertiere den Array $temp in einen String
			$temp = implode(',', $temp);
			
			// Vergleiche den String $temp mit dem String 'toner'
			// Speichere das Ergebnis in die Variable $erg
			$erg = stripos($temp, 'toner');
			
			// Vergleiche den String $temp mit dem String 'Cartridge'
			// Speichere das Ergebnis in die Variable $erg_Cartridge
			$erg_Cartridge = stripos($temp, 'Cartridge');
			
			// Vergleiche den String $temp mit dem String 'Kassette'
			// Speichere das Ergebnis in die Variable $erg_Kassette
			$erg_Kassette = stripos($temp, 'Kassette');
			
			$erg_color = stripos($temp, 'black');
			
			if(!$erg_color >= 1)
				$erg_color = stripos($temp, 'black');
			if(!$erg_color >= 1)
				$erg_color = stripos($temp, 'cyan');
			if(!$erg_color >= 1)
				$erg_color = stripos($temp, 'yellow');
			if(!$erg_color >= 1)
				$erg_color = stripos($temp, 'magenta');
				
			
			// Wenn der verleich positiv war, ist das gesuchte Wort in der Variablen gespeichert
			// Wenn Suchergebnise gefunden wurden ( heisst das in der jeweiligen OID Nummer Datensätzen mit dem Stichwort Toner oder Cartridge gespeichert sind )
			if(strlen($erg) >= 1 || $erg_Cartridge >= 1 || $erg_Kassette >= 1 || $erg_color >= 1) {
				
				// Prüft, ob in $temp ein Stichwort mit dem Inhalt 'schwarz' ist
				$erg = stripos($temp, 'schwarz');
			
				// Wenn Ja
				if(strlen($erg) >= 1) {
					
					// Zuerst wird der Zähler $i um 1 verringert
					// Die OID Nummer wird in die Variable $num_black gespeichert
					$num_black = $oids[--$i];
					// Der 
					// $nam_black = $temp;
					// Der Zähler $i wird erhöht
					$i++;
					
					// Speichert die letzte Zahl der OID Nummer in die Variabel $last
					$last = $num_black[strlen(--$num_black) - 1];
					
					// Fügt der Variable $return den Eintrag "schwarz" mit dem Inhalt $last hinzu
					$return += [ "black" => $last];
					
				}
				
				// Prüft, ob in $temp ein Stichwort mit dem Inhalt 'black' ist
				$erg = stripos($temp, 'black');
				
				if(strlen($erg) >= 1){
					
					// Zuerst wird der Zähler $i um 1 verringert
					// Die OID Nummer wird in die Variable $num_black gespeichert
					$num_black = $oids[--$i];
					// $nam_black = $temp;
					// Der Zähler $i wird erhöht
					$i++;
					
					// Speichert die letzte Zahl der OID Nummer in die Variabel $last
					$last = $num_black[strlen($num_black) - 1];
					
					// Fügt der Variable $return den Eintrag "schwarz" mit dem Inhalt $last hinzu
					$return += [ "black" => $last];
					
				}
				
				// Prüft, ob in $temp ein Stichwort mit dem Inhalt 'cyan' ist
				$erg = stripos($temp, 'cyan');
				
				if(strlen($erg) >= 1){
					
					// Zuerst wird der Zähler $i um 1 verringert
					// Die OID Nummer wird in die Variable $num_cyan gespeichert
					$num_cyan = $oids[--$i];
					// $nam_cyan = $temp;
					// Der Zähler $i wird erhöht
					$i++;
					
					// Speichert die letzte Zahl der OID Nummer in die Variabel $last
					$last = $num_cyan[strlen(--$num_cyan) - 1];
					
					// Fügt der Variable $return den Eintrag "cyan" mit dem Inhalt $last hinzu
					$return += [ "cyan" => $last];
					
				}
				
				// Prüft, ob in $temp ein Stichwort mit dem Inhalt 'magenta' ist
				$erg = stripos($temp, 'magenta');
				
				if(strlen($erg) >= 1){
					
					// Zuerst wird der Zähler $i um 1 verringert
					// Die OID Nummer wird in die Variable $num_cyan gespeichert
					$num_cyan = $oids[--$i];
					// $nam_cyan = $temp;
					// Der Zähler $i wird erhöht
					$i++;
					
					// Speichert die letzte Zahl der OID Nummer in die Variabel $last
					$last = $num_cyan[strlen(--$num_cyan) - 1];
					
					// Fügt der Variable $return den Eintrag "magenta" mit dem Inhalt $last hinzu
					$return += [ "magenta" => $last];
					
				}
				
				// Prüft, ob in $temp ein Stichwort mit dem Inhalt 'gelb' ist
				$erg = stripos($temp, 'gelb');
				
				if(strlen($erg) >= 1){
					
					// Zuerst wird der Zähler $i um 1 verringert
					// Die OID Nummer wird in die Variable $num_gelb gespeichert
					$num_gelb = $oids[--$i];
					// $nam_cyan = $temp;
					// Der Zähler $i wird erhöht
					$i++;
					
					// Speichert die letzte Zahl der OID Nummer in die Variabel $last
					$last = $num_gelb[strlen(--$num_gelb) - 1];
					
					// Fügt der Variable $return den Eintrag "magenta" mit dem Inhalt $last hinzu
					$return += [ "yellow" => $last];
					
				}
				
				// Prüft, ob in $temp ein Stichwort mit dem Inhalt 'gelb' ist
				$erg = stripos($temp, 'yellow');
				
				if(strlen($erg) >= 1){
					
					// Zuerst wird der Zähler $i um 1 verringert
					// Die OID Nummer wird in die Variable $num_gelb gespeichert
					$num_gelb = $oids[--$i];
					// $nam_cyan = $temp;
					$i++;
					
					// Speichert die letzte Zahl der OID Nummer in die Variabel $last
					$last = $num_gelb[strlen(--$num_gelb) - 1];
					
					// Fügt der Variable $return den Eintrag "gelb" mit dem Inhalt $last hinzu
					$return += [ "yellow" => $last];
					
				}
				
			}
			
		}
		
		$session->close();
		
		// Gibt $return zurück
		return $return;
		
	}
	
?>