<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290
								
		Erstellungsdatum:			09.10.2019
		
		Letztes Änderungsdatum: 	02.12.2019
		
	*/
	
	// meldungen.php
	// Zeigt Meldungen

	include_once('./class/snmp/get_toner.php');
	include_once('./document/include/meldung_include.php');

	// Wenn Zustadsänderung angefordert wurde
	if(isset($_POST['sub'])) {

	    $aTonerInfo = explode(';', $_POST['toner_info']);

        // Update die Tabelle "toner"
        put_sql_data::update_data('toner', 'ID', $aTonerInfo['1'], 'level', $aTonerInfo['0']);

	}

	// Speichert die IDs von allen Druckern
	$aPrinterIDs = get_sql_data::find_all_data('SELECT * FROM printer', [ 0 => 'ID' ]);

	// Solange noch Drucker unbehandelt waren
	for($i = 0; $i != count($aPrinterIDs); $i++) {

        meldung::setPrinter($aPrinterIDs[$i]);

        meldung::setWarning();

        meldung::setError();

    }

    meldung::putBodyError();
    meldung::putBodyWarning();

?>