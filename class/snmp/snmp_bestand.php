<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			12.09.2019
		
		Letztes Änderungsdatum: 	12.09.2019
		
	*/
	
	// snmp_bestand.php
	// Dokumentenbeschreibung
	
	// Funktion get_toner_bestand
	// Gibt den prozenturalen Bestand des Toners zurück
	// In $num ist die letzte Nummer der OID gespeichert, die die gewünschte Farbe enthält
	function get_toner_bestand($ip, $num){

		// In $max_toner_unform wird der maximale Bestand des Toners gespeichert
		$max_toner_unform = snmpwalk($ip, 'public', '.1.3.6.1.2.1.43.11.1.1.8.1.' . $num);
		// Der Array $max_toner_unform wird in einen String konvertiert
		$max_toner_unform = implode(',', $max_toner_unform);
		
		// String $max_toner_unform soll erst ab den 9. Zeichensatz behandelt werden
		$counter = 9;
		// In $max_toner wird der tatsächliche maximale Bestand des Toners gespeichert
		$max_toner = '';
		
		// Solange $counter nicht größer als $max_toner_unform ist
		while($counter < strlen($max_toner_unform)){
			
			// Speichere den jeweiligen Zeichensatz in $max_toner
			$max_toner .= $max_toner_unform[$counter++];
			
		}

		// In $toner_unform wird der tatsächliche Bestand des Toners gespeichert
		$toner_unform = snmpwalk($ip, 'public', '.1.3.6.1.2.1.43.11.1.1.9.1.' . $num);
		// Der Array $toner_unform wird in einen String konvertiert
		$toner_unform = implode(',', $toner_unform);
		
		// String $max_toner_unform soll erst ab den 9. Zeichensatz behandelt werden
		$counter = 9;
		// In $max_toner wird der tatsächliche Bestand des Toners gespeichert
		$toner = '';
		
		// Solange $counter nicht größer als $toner_unform ist
		while($counter < strlen($toner_unform)){
			
			// Speichere den jeweiligen Zeichensatz in $toner
			$toner .= $toner_unform[$counter++];
			
		}

		// Nun wird der prozenturale Anteil des Bestandes im Toner berechnet und in $erg_prozent gespeichert
		$erg_prozent = ( 100 / $max_toner ) * $toner;
		
		// $erg_prozent wird zurückgegeben
		return $erg_prozent;
		
	}

?>