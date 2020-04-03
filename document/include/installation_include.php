<?php
	
	/*
		
		Author: 					Hagengruber Florian
									florian@hagengruber.dev
									github.com/implod3
		
	*/

	class installation{
		
		public static function ini_installation() {
			
			if(isset($_POST['auth'])) {
				
				if($_POST['auth'] == 'none') {
					
					copy('./class/header/header_un.php', './class/header/header.php');
					
				} else {
					
					copy('./class/header/header_ad.php', './class/header/header.php');
					put_sql_data::update_data('dc', '', '', 'DC_server', '"' . $_POST['ip'] . '"');
					
				}
				
				unlink('./installation.dat');
				
				self::show_finish();
				exit;
				
			}
			
			self::show_gui();
			exit;
			
		}
		
		public static function show_gui() {
			
			echo '
				
				<html>
					
					<head>
						
						<title> Druckerverwaltungstool - Installation </title>
						<link rel="stylesheet" href="./style/installation.css">
						
					</head>
					
					<body>
						
						<div class="cap"> Druckerverwaltungstool - Installation </div>
						
						<form action="" method="POST">
							
							<div class="step"> Schritt 1 </div>
							
							<input type="radio" name="auth" value="ActiveDirectory">
							<lable for="ActiveDirectory"> Authentifizierung über\'s Active Directory </lable>
							<br>
							<input checked="" type="radio" name="auth" value="none">
							<lable for="none"> Keine Authentifizierung </lable>
							
							<div class="point_second"> Wenn Authentifizierung über\'s Active Directory: </div>
							
							<input name="ip" placeholder="IP-Adresse des AD-Servers eingeben">
							<br>
							<input class="submit" type="submit" value="Fertig!">

						</form>
						
					</body>
					
				</html>
				
			';
			
		}
		
		public static function show_finish() {
			
			echo 'Die Datei "druckerverwaltungstool.sql" bitte noch in die Datenbank importieren, danach die Seite einfach aktualisieren!"';
			
		}
		
	}
	
?>