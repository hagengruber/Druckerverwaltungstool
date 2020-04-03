<?php
	
	/*
		
		Author: 					Hagengruber Florian
									+49 9921 600 290
									f.hagengruber@schock.de
			
	*/
	
	// zubehör verwalten.php
	// Verwaltet Toner
	
	// Inkludiert toner_verwalten_include.php
	// Beinhaltet Funktionen
	include_once('./document/include/zubehör_verwalten_include.php');
	
	if(isset($_POST['add_toner']))
		manage_toner::add_toner($_POST['toner_info']);

	if(isset($_POST['delete_toner']))
		manage_toner::delete_toner($_POST['toner_id']);

	// Speichert in $aPrinter alle IDs der inventarisierten Drucker
	$aToner = get_sql_data::find_all_data('SELECT * FROM toner', [ 0 => 'ID' ]);

	manage_toner::showHeader();

	for ($i = 0; $i != count($aToner); $i++) {

		manage_toner::setVariable($aToner[$i]);
		manage_toner::showToner();

	}

	manage_toner::showForm();

	if(isset($_POST['add_addon']))
		manage_addon::add_addon($_POST['addon_info']);

	if(isset($_POST['delete_addon']))
		manage_addon::delete_addon($_POST['addon_id']);

	manage_addon::showHeader();

	$aAddon = get_sql_data::find_all_data('SELECT * FROM addon', [ 0 => 'ID' ]);

	for ($i = 0; $i != count($aAddon); $i++) {

		manage_addon::putAddon($aAddon[$i]);

	}

	manage_addon::showForm();

	manage_toner::showScript();

?>
