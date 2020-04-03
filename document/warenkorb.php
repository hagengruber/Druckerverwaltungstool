<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// warenkorb.php
	// Stellt Bestellungen fertig oder bricht sie ab

    // Inkludiert warenkorb_include.php
    // Beihaltet Funktionen
    include_once('./document/include/warenkorb_include.php');

    // print_r($_COOKIE);

    // Wenn die Bestellungen gelöscht werden sollen
	if(isset($_POST['delete_ofer']))
        cart::delete_offer();

	// Wenn die Bestellungen in die Datenbank geschrieben werden sollen
	if(isset($_POST['add_ofer']))
        cart::add_offer();

	// Gibt header aus
    cart::put_header();

	// Gibt die unbestätigten Bestellungen aus
    cart::put_offer();
	
?>