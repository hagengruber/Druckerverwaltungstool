<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			13.09.2019
		
		Letztes Änderungsdatum: 	13.09.2019
		
	*/
	
	// druckereigenschaften.php
	// Zeigt Druckereigenschaften
	
	// Inkludiert druckereigenschaften_include.php - beinhalet Funktionen
	include_once('./document/include/druckereigenschaften_include.php');
	
	// Setzte Druckervariablen
    properties::set_var($_GET['printer']);

	// Gib Dokumntenheader aus
    properties::put_header();
	
	// Gib Toner aus
    properties::put_toner();
	
	// Gib Warnungen aus
    properties::put_toner_warning();
	
?>