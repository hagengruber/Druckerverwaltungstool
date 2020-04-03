<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290

	*/
	
	// bestellen.php
	// Nimmt Bestellungen auf

    // Inkludiert bestellen_include.php
    // Beinhaltet funktionen
	include_once('./document/include/bestellen_include.php');

	// Wenn eine Ware in den Warenkorb gespeichert werden soll
	if(isset($_POST['add_order']))
		// Speichert eine Ware in den Warenkorb über Cookies
		order::add_order($_POST);

	print_r($_COOKIE);

	// Setzt Variablen des Druckers
    order::set_var($_GET['printer']);

    // Setzt alle Variablen der zugehörigen Toner
    order::set_toner();

	// Gibt Header des Dokuments aus
    order::show_header();

	// Gibt alle Toner des Druckers aus
    order::show_toner();

    // Gibt alle Zubehöre des Druckers aus
    order::show_addon();

?>