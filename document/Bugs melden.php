<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			14.10.2019
		
		Letztes Änderungsdatum: 	14.10.2019
		
	*/
	
	// Bugs melden
	// Meldet Bugs
	
	include_once('./document/include/Bugs melden_include.php');

	// Wenn ein Report gesendet wurde
	if(isset($_POST['report']))
		// Sende Reoprt per Email an f.hagengruber@schock.de
		bug::send_mail($_POST['text']);

	// Formular ausgeben
	bug::put_form();
	
?>
