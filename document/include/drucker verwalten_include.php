<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// drucker verwalten_include.php
	// Beinhaltet Funktionen von drucker verwalten.php
	
	class manage_printer{

	    public static $iPrinterID;
	    public static $sPrinterName;
	    public static $sContactName;
	    public static $sLocationName;
	    public static $sIP;
	    public static $iCustomID;

	    public static function error() {

	        echo '
	            
	            <script>
	                alert("Fehlerhafte Benutzereingabe: Bitte Name, Kontakt und IP-Adresse angeben");
	            </script>
	            
	        ';

	        return 0;

        }

        // add_printer speichert einen neunen Drucker in die SQL Datenbank
        public static function add_printer($sInfo){

            $aInfo = explode(';', $sInfo);

			if($aInfo['0'] == '') { self::error(); return -1; }
			if($aInfo['1'] == '') { self::error(); return -1; }
            if($aInfo['2'] == '')
                $aInfo['2'] = 'NULL';
			if($aInfo['3'] == '') { self::error(); return -1; }
			if($aInfo['4'] == '')
                $aInfo['4'] = 0;
	
			// ###################### VARIABLEN DEKLARIEREN ######################

                $aIsLocation = get_sql_data::find_all_data('SELECT * FROM location WHERE name=\'' . $aInfo['2'] . '\'', [ 0 => 'ID' ]);

                if (isset($aIsLocation['0'])) {

                    $aInfo['2'] = $aIsLocation['0'];

                } elseif($aInfo['2'] != 'NULL') {

                    // get_free() lieferte eine freie ID zurück
                    $id_location = get_sql_data::get_free('location','ID');

                    $line = '`ID`, `name`';
                    $new_line = $id_location . ', \'' . $aInfo['2'] . '\'';

                    // Speichert einen neuen Drucker in die SQL Datenbank
                    put_sql_data::put_data('location', $line, $new_line);

                    $aInfo['2'] = $id_location;

                }

				// get_free() lieferte eine freie ID zurück
				$id = get_sql_data::get_free('printer','ID');
				
				$line = '`ID`, `name`, `ip`, `customID`, `locationID`, `contactID`';
				$new_line = $id . ', \'' . $aInfo['0'] . '\', \'' . $aInfo['3'] . '\', ' . $aInfo['4'] . ', ' . $aInfo['2'] . ', ' . $aInfo['1'];

			// ###################### VARIABLEN DEKLARIEREN ENDE ######################

			// Speichert einen neuen Drucker in die SQL Datenbank
			put_sql_data::put_data('printer', $line, $new_line);

		}
		
		// Löscht einen Drucker aus der SQL Datenbank
        public static function delete_printer($printer_id){

            /*
                Um einen Drucker vollständig und korrekt zu löschen, muss jeder Fremdschlüsseleintrag gelöscht werden
                da sonst die referienzelle Integrität verletzt wird
            */
            self::del_fk($printer_id);

			// Löscht einen Drucker aus der SQL Datenbank
			put_sql_data::del_data('printer', 'ID', $printer_id);
			
		}

		public static function del_fk($iID) {

            // Löscht jeden Fremdschlüssel des Druckers
            // Drucker kann einen Fremdschlüssel in der Tabelle toner haben
            put_sql_data::update_data('toner', 'printerID', $iID, 'printerID', 'NULL');
            put_sql_data::update_data('addon', 'printerID', $iID, 'printerID', 'NULL');

        }

		public static function showHeader(){

            echo '
				
				<center>
				
				<div class="table_box">
					
					<table>
					
						<tr>
							<th> <img src="images/drucker_icon.png" height="150px"> </th>
						
					<th>
					
					<table class="table">
								
								<tr>
									<th> Name </th>
									<th> Kontakt </th>
									<th> Standort </th>
									<th> IP-Adresse </th>
									<th> Zusatz </th>
									<th> Löschen </th>
								</tr>

			';

        }

		public static function setVariable($iPrinterID) {

            // In $aPrinterInfo werden alle relevanten Infos für den aktuellen Drucker gespeichert
            $aPrinterInfo = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $iPrinterID, [ 0 => 'name', 1 => 'contactID', 2 => 'locationID', 3 => 'ip', 4 => 'customID' ]);

            self::$iPrinterID = $iPrinterID;
            self::$sPrinterName = $aPrinterInfo['0'];
            self::$sIP = $aPrinterInfo['3'];
            self::$iCustomID = $aPrinterInfo['4'];

            // Contact und Location sind Fremdschlüssel
            $aLocation = get_sql_data::find_all_data('SELECT * FROM location WHERE ID=' . $aPrinterInfo['2'], [ 0 => 'name' ]);
            $aContact = get_sql_data::find_all_data('SELECT * FROM contact WHERE ID=' . $aPrinterInfo['1'], [ 0 => 'name' ]);

            self::$sLocationName = $aLocation['0'];
            self::$sContactName = $aContact['0'];

        }

        public static function showPrinter() {

            echo '
                
                <tr>
                
                    <td> ' . self::$sPrinterName . ' </td>
                    <td> ' . self::$sContactName . ' </td>
                    <td> ' . ucfirst(self::$sLocationName) . ' </td>
                    <td> ' . self::$sIP . ' </td>
                    <td> ' . self::$iCustomID . ' </td>
                    
                    <td> <input type="submit" value="X" onclick="put(\'' . self::$iPrinterID . '\')" name="del_printer" class="simple_button"> </td>
                    
                </tr>
                
            ';

        }

        public static function showForm() {

            echo '
				
				<tr>
			       
			        <td> <input placeholder="Druckernamen eingeben" name="name" id="name" required=""> </td>
				    
				    <td>
				        
				        <select name="contact" id="contact">
				
			';

            $aContact_ID = get_sql_data::find_all_data('SELECT * FROM contact', [ 0 => 'ID']);
            $aContact_Name = get_sql_data::find_all_data('SELECT * FROM contact', [ 0 => 'name' ]);

            for ($i = 0; $i != count($aContact_ID); $i++) {

                echo ' <option value="' . $aContact_ID[$i] . '"> ' . $aContact_Name[$i] . ' </option> ';

            }

            echo '
				
				                <td> <input placeholder="Standort eingeben" name="location" id="location"> </td>
								<td> <input placeholder="IP eingeben" name="ip" id="ip" required=""> </td>
								<td> <input placeholder="Zusatz, wie ID, eingeben" name="customID" id="customID" type="number"> </td>
								<td> <input type="submit" value="Drucker hinzufügen" onclick="addPrinter()" name="add_printer" class="simple_button"> </td>
								</tr>
							
							</table>
							
						</div>
						
					</div>
					
				</center>
            
            ';

        }

        public static function showScript() {

            echo '</table>';

            echo '
                
               <form action="" method="post">
                    
                    <div id="form"></div>
                    <input id="submit" name="del_printer" type="submit" style="background-color: transparent; border: none; color: transparent;">
                    
               </form>
               
               <form action="" method="post">
                    
                    <div id="form_add"></div>
                    <input id="add_printer" name="add_printer" type="submit" style="background-color: transparent; border: none; color: transparent;">
                    
               </form>
                
                <script>
                    
                    function put(i) {
                        
                        document.getElementById("form").innerHTML = \' <input type="hidden" name="printer_id" value="\'+i+\'"> \';
                        document.getElementById("submit").click();
                        
                    }
                    
                </script>
                
                <script>
                    
                    function addPrinter() {
                        
                        var name = document.getElementById("name").value;
                        var contact = document.getElementById("contact").value;
                        var location = document.getElementById("location").value;
                        var ip = document.getElementById("ip").value;
                        var customID = document.getElementById("customID").value;
                        
                        document.getElementById("form_add").innerHTML = \' <input type="hidden" name="printer_info" value="\'+name+\';\'+contact+\';\'+location+\';\'+ip+\';\'+customID+\'"> \';
                        document.getElementById("add_printer").click();
                        
                    }
                    
                </script>
                
            ';

        }

	}
?>