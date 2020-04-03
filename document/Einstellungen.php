<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290
			
	*/
	
	// Einstellungen.php
	// Zeigt und setzt Einstellungen
	
	// Inkludiert einstellungen_include.php
	// Beinhaltet Funktionen
	include_once('./document/include/einstellungen_include.php');

    // Setzt die Variablen
    settings::set_variable($_GET['printer']);

    if (isset($_POST['ID']))
        settings::chgStatus($_POST['ID'], $_POST['typ']);

	echo settings::get_header();

	echo settings::get_general_settings();

	echo settings::get_assign_toner();

    echo settings::get_assign_addon();

	echo settings::get_warning_toner();

    echo settings::get_warning_addon();

?>