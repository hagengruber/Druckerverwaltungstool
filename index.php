<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// index.php
	// Ruft die run() Methode in der run.php auf
	
	// Bindet die run.php ein
	include_once('./class/run.php');
	
	// Bindet weitere Klasse ein
	include_once('./class/sql/get_sql_data.php');
	include_once('./class/sql/put_sql_data.php');
	include_once('./class/snmp/get_toner.php');
	include_once('./class/snmp/snmp_bestand.php');
	include_once('./class/snmp/snmp_name.php');
	include_once('./class/snmp/snmp_number.php');
	
	// Ruft die run() Methode auf
	run();
	
?>