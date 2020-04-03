<?php
	
	/*
		
		Author: 					Hagengruber Florian
									f.hagengruber@schock.de
									+49 9921 600 290
			
	*/
	
	// Kontakte verwalten.php
	
	// Inkludiert kontakte_verwalten_include.php
	// Beinhaltet Funktionen
	include_once('./document/include/kontakte_verwalten_include.php');
	
	if (isset($_POST['add_contact']))
		manage_contact::add_contact($_POST['name'], $_POST['adress'],$_POST['phonenumber'], $_POST['website']);

	if (isset($_POST['delete_contact']))
		manage_contact::delete_contact($_POST['contactID']);

	manage_contact::show_contact();
	
?>
