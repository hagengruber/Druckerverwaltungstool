<?php
	
	/*
		
		Author: 					Hagengruber Florian
									florian@hagengruber.dev
									github.com/implod3
									
	*/

	// installation.php
	// Prüft, ob die Anwendung installiert werden soll

	include_once('./document/include/installation_include.php');
	include_once('./class/sql/put_sql_data.php');
	
	if(file_exists('./installation.dat')) {
		
		installation::ini_installation();
		
	}
	
?>