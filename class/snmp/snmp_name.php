<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			12.09.2019
		
		Letztes Änderungsdatum: 	12.09.2019
		
	*/
	
	// snmp_name.php
	// Dokumentenbeschreibung
	
	// Funktion get_name
	// Diese Funktion gibt den Druckernamen anhand der IP-Adresse zurück
	function get_name($ip){
		
		// SNMP-String wird in die Variable $name_unform gespeichert
		$name_unform = snmpwalk($ip, 'public', 'iso.3.6.1.2.1.25.3.2.1.3.1');
		// Da nur der Name zurückgegeben werden soll, muss die Variable $name_unform noch formatiert werden
		// Der formatierte Name wird in die Variable $name gespeichert
		$name = '';
		
		// Array $name_unform wird in einen String konvertiert
		$name_unform = implode(',', $name_unform);
		
		// Ab der 9. Stelle des Strings $name_unform soll gehandelt werden
		$counter = 9;
		
		// String aufbau: STRING "NAME DES DRUCKERS"
		// Ab der 9. Stelle beginnt der Name des Drucker
		// Also wird ab der 9. Stelle jedes Zeichen des Strings in die Variable $name gespeichert, solang bis ein " erreicht wird
		while($name_unform[$counter] != '"'){
			
			$name .= $name_unform[$counter++];
			
		}
		
		// $name wird zurückgegeben
		return $name;
		
	}
	
?>