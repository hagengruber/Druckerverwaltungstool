<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290
		
	*/
	
	// drucker verwalten.php
	// verwaltet drucker
	
	// Inkludiert drucker verwalten_include.php
	// Beinhaltet die Funktionen
	include_once('./document/include/drucker verwalten_include.php');
	
	// Wenn ein neuer Drucker hinzugefügt werden soll
	if(isset($_POST['add_printer']))
		// Fügt einen neuen Drucker in die SQL Datenbank
        manage_printer::add_printer($_POST['printer_info']);

	// Wenn ein Drucker gelöscht werden soll
	if(isset($_POST['del_printer']))
		// Löscht einen Drucker aus der SQL Datenbank
        manage_printer::delete_printer($_POST['printer_id']);

	// Speichert in $aPrinter alle IDs der inventarisierten Drucker
    $aPrinter = get_sql_data::find_all_data('SELECT * FROM printer', [ 0 => 'ID' ]);

    manage_printer::showHeader();

	for ($i = 0; $i != count($aPrinter); $i++) {

	    manage_printer::setVariable($aPrinter[$i]);
        manage_printer::showPrinter();

    }

	manage_printer::showForm();
    manage_printer::showScript();

?>