<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// Zubehör ausgeben.php
	// Gibt alle Zubehöre für den Drucker aus und gibt Zubehör aus

    // Inkludiert zubehör_ausgeben_include.php
    // Beinhaltet Funktionen
	include_once('./document/include/zubehör_ausgeben_include.php');

	if(isset($_POST['delete']))
		$text = put_addon::delete($_POST['delete'], $_POST['typ']);

    put_addon::put_header();

    put_addon::put_all_toner();

    put_addon::put_all_addon();

    if (isset($text))
        echo $text;
	
?>