<?php
	
	// druckerübersicht.php
	// Zeigt alle Drucker + Tonerbestände
	
	// druckerübersucht_include.php für Funktionen
	include_once('./document/include/druckerübersucht_include.php');
	
?>

<script>
	
	function Sleep(milliseconds) {
	   return new Promise(resolve => setTimeout(resolve, milliseconds));
	}
	
</script>

<input id="send_status" type="hidden" value="ok">

<center>

	<div class="home_border">
		
		<?php

			// In $all_printer_id werden alle Drucker-ID's gespeichert
			// $all_printer_id = [ 0 => 1, 1 => 2 ];

			$all_printer_id = get_sql_data::find_all_data('SELECT * FROM printer', [ 0 => 'ID' ]);

			// ##################### HEADER ###########################
				
				// Gibt Header aus
				echo overview::put_header();
				
			// ##################### HEADER END #######################

			// Counter für Tabellen
			$counter_table = 0;
			
			// ######################## PRINTER ########################

                for ($i = 0; $i != count($all_printer_id); $i++) {

                    // Setzte Variablen
                    overview::set_var($all_printer_id[$i]);

                    // ################### TONER ###################

                        // Setzt die Farben aller Toner + Bestand des Druckers
                        $tonerlevel = overview::get_tonerlevel();
						
                        // Solange nicht alle Toner behandelt wurden
                        for ($a = 0; $a != count($tonerlevel); $a++) {

                            // zuerst werden die benötigten Infos des Toners gespeichert
                            // wie Farbe und Toner-ID
                            overview::set_toner_var($tonerlevel[$a]['0']);
							
                            ######################### TONER CHANGE CONTROL #########################

                            // Prüft, ob Toner unathorisiert getauscht wurde
								// overview::put_printer_control();
								echo '
									
									<script>
									
										async function try_send() {
											
											console.log("Wait: ",' . overview::$id * 2000 . ', " ID: ", ' . overview::$id . ');
											await Sleep(' . overview::$id * 2000 . ');
											
											var el = document.getElementById("hidden' . overview::$id . '").value;
											
											while (el == "send_false") {
												
												var control_send = document.getElementById("send_status").value;
												
												if (control_send == "ok") {
													
													console.log("Send Data: ", ' . overview::$id . ');
													
													document.getElementById("send_status").value = "no";
													send(' . overview::$id . ', "' . overview::$ip . '");
													document.getElementById("hidden' . overview::$id . '").value = "ok";
													document.getElementById("send_status").value = "ok";
													
													console.log("Send ok: ", ' . overview::$id . ');
													
												} else {
													
													console.log("Wait", ' . overview::$id . ');
													await Sleep(1000);
													
												}
												
												var el = document.getElementById("hidden' . overview::$id . '").value;
												
											}
											
											console.log("Finish", ' . overview::$id . ');
											
										}
										
										try_send();
										
									</script>
								
								';
								
                            ######################### TONER CHANGE CONTROL END #####################


                        }

                    // ################### TONER END ###############

                    // ######################### GRAPHIC OUTPUT #########################

                        // Nun folgt die grafische Ausgabe

                        // Wenn der Vierte Drucker einer Reihe angezeigt werden soll
                        if($counter_table == 3){

                            echo ' </tr> <tr> ';
                            $counter_table = 0;

                        }

                        $counter_table++;

                        overview::put_body();

                    // ######################### GRAPHIC OUTPUT END #####################

                }

				// Die Adresse von $warning wird übergegen, sodass die Variablen in der Klasse genutzt werden können
                overview::put_script();

                // Prüft, ob im Warenkorb noch Elemente sind
                // Wenn ja, gib Warnung aus
                overview::put_warning();

			// ######################## PRINTER END ####################
			
		?>
		
	</div>
	
</center>