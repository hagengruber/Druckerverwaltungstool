<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// event.php
	// Bearbeitet alle Events
	
	if(file_exists('./include/event_include.php')) {
		
		include_once('./include/event_include.php');
        include_once ('../class/sql/put_sql_data.php');

	} else {
		
		include_once('./document/include/event_include.php');
		
	}

	switch($_GET['event']) {
		
		case 'logout':
			// Wenn Eventanfrage "logout" angefragt wird, rufe logout() auf
			event::logout();
		break;

		default:
		break;
	
	}

	if (isset($_POST['settings']))
        event::update_printer($_POST['settings']);

?>