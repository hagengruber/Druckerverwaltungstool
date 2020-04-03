<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
	*/
	
	// benutzerverwaltung.php
	// Gibt alle Benutzer aus und erlaubt, neue Benutzer hinzuzufügen
	
	// Inkludiert benutzerverwaltung_include.php
	// Beinhaltet Funktionen
	include_once('./document/include/benutzerverwaltung_include.php');
	
	// Wenn Benutzer anderen Benutzer hinzufügen will, existiert eine POST-Anfrage namens "add_user"
	if(isset($_POST['add_user']))
		benutzerverwaltung::add_user($_POST['add_user']);
	
	// Wenn Benutzer anderen Benutzer löschen will, existiert eine POST-Anfrage namens "del_user"
	if(isset($_POST['del_user']))
		benutzerverwaltung::delete_user($_POST['del_user']);

	// Gibt alle Benutzer aus
	benutzerverwaltung::show_all_users();
	// Gibt Vorlage zum Benutzer hinzufügen aus
	benutzerverwaltung::show_user_form();
	
?>