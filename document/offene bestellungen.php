<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// offene estellungen.php
	// Zeigt offene Bestellungen, bucht diese ein oder löscht sie
	
	include_once('./document/include/offene_bestellungen_include.php');

	// Bestellung ist angekommen
    if (isset($_POST['order_serie']))
        $aOutput = openOrders::addOrder($_POST['order_serie']);

	// Zeigt Header
    openOrders::showHeader();

    // Zeigt alle Bestellungen
    openOrders::showOrders();

    if (isset($aOutput)) {

        for ($i = 0; $i != count($aOutput); $i++)
            echo $aOutput[$i] . '<br>';

    }

?>