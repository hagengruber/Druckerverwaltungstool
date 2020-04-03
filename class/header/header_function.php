<?php
	
	function header_login(){
		
		// header_login()
		// Header für Login
		
		// Erstellt HTML Dokument
		echo '
			
			<html>
				
				' . /* Head Bereich */ '
				<head>
					
					' . /* Setzt den Titel */ '
					<title> Druckerverwaltungstool - Login </title>
					' . /* Bindet die Style.css ein */ '
					<link rel="stylesheet" type="text/css" href="./style/style.css">
				
				</head>
				
				' . /* Body Bereich */ '
				' . /* "onload" wird ausgeführt, wenn die Seite mit allen externen Medien geladen ist */ '
				' . /* Wenn dies passiert, wird die Funktion animation() per JavaScript ausgeführt */ '
				<body onload="animation()">
					
					' . /* DIV Container, die das Ladegif anzeigen */ '
					<div id="myload">
						<div id="load">
							<img id="loadGif" src="./images/loading.gif">
						</div>
					</div>
					
					' . /* JavaScript */ '
					<script>
						
						' . /* Funktion animation() [ wird beim vollständigen Laden der Website aufgerufen - siehe <body> ] */ '
						function animation(){
							
							' . /* Ruft die Funktion delani() auf mit einer Verzögerung von 1er Sekunde auf */ '
							' . /* Grund ist, dass die Ladeanimation mindestens 1ne Sekunde angezeit werden soll, auch wenn der Browser die externen Medien wie Bilder noch im Cache hat */ '
							setTimeout(delAni, 1000);
							
							' . /* Funktion delAni() */ '
							function delAni(){
								
								' . /* Setzt den Inhalt des DIV Containers mit der ID myload auf "" */ '
								' . /* Lässt das Ladegif "verschwinden" */ '
								document.getElementById("myload").innerHTML = "";
							  
							}
						
						}
					
					' . /* Ende JavaScript */ '
					</script>
		';
		
	}
	
	function header_logged(){
		
		// header_logged
		// Header für schon eingeloggte Benutzer
		
		// Nur, wenn $_GET['site'] existiert
		if(isset($_GET['site'])) {
			
			$site = $_GET['site'];
			
			// Erstellt HTML Dokument
			echo '
				
				<html>
					
					' . /* Head Bereich */ '
					<head>
						
						' . /* Setzt Titel, in dem per GET der Inhalt von $site angezeigt wird */ '
						<title> Druckerverwaltungstool - ' . $site . ' </title>
						
						' . /* Bindet Style.css ein */ '
						<link rel="stylesheet" type="text/css" href="./style/style.css">
					
					</head>
					
					' . /* Body Bereich */ '
					<body>
						
						' . /* Überschrift */ '
						' . /* Beinhaltet auf das Hintergrundbild */ '
						<div class="header">
							
							' . /* Zeigt Überschrift */ '
							<div class="heading"> Druckerverwaltungstool </div>
							' . /* Zeigt Seitentitel per GET */ '
							<div class="site_title"> ' . $site . ' </div>
						
						</div>
						
						' . /* Abstand zwischen Überschrift und Button */ '
						<br>
						
						' . /* Container "menu" */ '
						' . /* Beinhaltet das Menü */ '
						<div class="menu">
							
							<div class="menu_black">  </div>
							
							' . /* Zeigt das Bild rechts oben an */ '
							<img src="./images/menu.png" class="image">
							
							' . /* Container "menu_inner */ '
							' . /* Beinhaltet das tatsächliche Menü */ '
							<div class="menu_inner">
								
								' . /* Alle Elemente sind zentriert */ '
								<center>
									
									' . /* Erstellt eine Tabelle, welche die einzelnen Inhalte sauber anzeigt */ '
									' . /* Die Höhe ist dabei 90%, die Breite 60%. Der Text steht in der Mitte der Tabelle */ '
									<table style="height: 90%; width: 60%; text-align: center;">
										
										' . /* Container "menu_text" */ '
										' . /* Beinhaltet die einzelnen Elemente */ '
										<div class="menu_text">
											
											' . /* Erstellt einen neue Spalte */ '
											<tr>
												
												' . /* Erstellt eine neue Zelle */ '
												' . /* Der Rechte Rand wird, für Trennung, weiß angezeigt. Die Breite ist 20% */ '
												<td style="border-right: 1px solid white; width: 20%;">
													
													' . /* Sendet eine POST Anfrage zu index.php */ '
													<form action="./index.php" method="GET">
														
														' . /* Der Button */ '
														<input value="Druckerübersicht" type="submit" class="menu_button" id="Druckerübersicht">
														<input type="hidden" name="site" value="Druckerübersicht">
														
													</form>
													
													<form action="./index.php" method="GET">

														<input value="Drucker verwalten" type="submit" class="menu_button" id="Drucker verwalten">
														<input type="hidden" name="site" value="Drucker verwalten">
														
													</form>
													
													<form action="./index.php" method="GET">
													
														<input value="Zubehör verwalten" type="submit" class="menu_button" id="Zubehör verwalten">
														<input type="hidden" name="site" value="Zubehör verwalten">
														
													</form>
													
													
													
													<form action="./index.php" method="GET">

														<input value="Offene Bestellungen" type="submit" class="menu_button" id="Offene Bestellungen">
														<input type="hidden" name="site" value="Offene Bestellungen">
														
													</form>
													
													<form action="./index.php" method="GET">

														<input value="Kontakte verwalten" type="submit" class="menu_button" id="Kontakte verwalten">
														<input type="hidden" name="site" value="Kontakte verwalten">
														
													</form>
												
												' . /* Zeile wird beendet */ '
												</td>
												
												' . /* Erstellt eine neue Zelle */ '
												' . /* Der Rechte Rand wird, für Trennung, weiß angezeigt. Die Breite ist 20% */ '
												' . /* Gleich wie oben */ '
												<td style="border-right: 1px solid white; width: 20%;">
													
													<form action="./index.php" method="GET">
														
														<input value="Benutzerverwaltung" type="submit" class="menu_button" id="Benutzerverwaltung">
														<input type="hidden" name="site" value="Benutzerverwaltung">
														
													</form>
													
													<form action="./index.php" method="GET">

														<input value="Hilfe" type="submit" class="menu_button" id="Hilfe">
														<input type="hidden" name="site" value="Hilfe">
														
													</form>
													
												</td>
												
												' . /* Erstellt eine neue Zelle */ '
												' . /* Der Rechte Rand wird, für Trennung, weiß angezeigt. Die Breite ist 20% */ '
												<td style="border-right: 1px solid white; width: 20%;">
												
													<form action="./index.php" method="GET">

														<input value="Abmelden" type="submit" class="menu_button">
														<input type="hidden" name="event" value="logout">
														<input type="hidden" name="site" value="event">
														
													</form>
													
												</td>
												
											</tr>
											
										</div>
								
									</table>
								
								</center>
								
							</div>
							
						</div>
				
				';
				
			if(file_exists('./document/' . $_GET['site'] . '.php') && $_GET['site'] != 'event' && $_GET['site'] != 'Bestellen' && $_GET['site'] != 'Warenkorb') {

				echo '
				
					
					<script>
						
						if(!document.getElementById("event")) {
							
							var i = document.getElementById("' . $site . '");
							if(i != null) { i.setAttribute("style", "color: white; border-bottom: 1px solid white;"); }
							
						}
						
						</script>
						
				';

			}
			
		}
		
	}
