<?php
	
	/*
		
		Author: 					Hagengruber Florian
									+49 9921 600 290
									f.hagengruber@schock.de
		
	*/
	
	// toner_verwalten_incllude.php
	
	class manage_toner {

		public static $iID;
		public static $sName;
		public static $sContactName;
		public static $sColor;
		public static $iCustomID;
		public static $error_message = 'Fehlerhafte Benutzereingaben: Name, Farbe und Kontakt erforderlich';

		public static function error($message) {

			echo '
				
				<script>
					alert("' . $message . '");
				</script>
				
			';

			return 0;

		}

		public static function add_toner($sTonerInfo){

			$aTonerInfo = explode(';', $sTonerInfo);

			if ($aTonerInfo['0'] == '') { self::error(self::$error_message); return -1; }

			// get_free() lieferte eine freie ID zurück
			$id = get_sql_data::get_free('toner','ID');
			
			switch($aTonerInfo['1']){
				
				case 'Cyan':
					$aTonerInfo['1'] = 'cyan';
				break;
				
				case 'Schwarz':
					$aTonerInfo['1'] = 'black';
				break;
				
				case 'Magenta':
					$aTonerInfo['1'] = 'magenta';
				break;
				
				case 'Gelb':
					$aTonerInfo['1'] = 'yellow';
				break;

				default:
					self::error(self::$error_message);
					$error = true;
				break;
				
			}

			if (isset($error)) { self::error(self::$error_message); return -1; }

			if($aTonerInfo['2'] == "") { self::error(self::$error_message); return -1; }
			if($aTonerInfo['3'] == "")
				$aTonerInfo['3'] = 0;

			$line = '`ID`, `name`, `color`, `quantity`, `customID`, `min_quantity`, `level`, `vChange`, `printerID`, `contactID`';
			$new_line = $id . ', \'' . $aTonerInfo['0'] . '\', \'' . $aTonerInfo['1'] . '\', 0, ' . $aTonerInfo['3'] . ', 1, 100, FALSE, NULL, ' . $aTonerInfo['2'];
			echo $new_line;
			put_sql_data::put_data('toner', $line, $new_line);
			
		}

		public static function delete_toner($iToner_id){

			// Wenn Toner ein Fremschlüssel in orders ist, darf der Toner nicht gelöscht werden
			$bControl = self::is_fk($iToner_id);

			if (!$bControl) {

				put_sql_data::del_data('toner', 'ID', $iToner_id);

			} else {

				echo ' <script> alert("Toner kann nicht gelöscht werden, da er in einer offenen Bestellung steht"); </script> ';

			}

		}

		public static function is_fk($iID) {

			$aControl = get_sql_data::find_all_data('SELECT * FROM orders WHERE tonerID=' . $iID, [ 0 => 'tonerID' ]);

			return isset($aControl['0']) ? true : false;

		}

		public static function showHeader(){

			echo '
				
				<center>
				
				<div class="table_box">
					
					<table>
					
						<tr>
							<th> <img src="images/toner_icon.png" height="150px"> </th>
						
					<th>
					
					<table class="table">
								
								<tr>
									<th> Name </th>
									<th> Farbe </th>
									<th> Kontakt </th>
									<th> Zusatz </th>
									<th> Löschen </th>
								</tr>

			';

		}

		public static function setVariable($iID) {

			$aTonerInfo = get_sql_data::find_all_data('SELECT * FROM toner WHERE ID=' . $iID, [ 0 => 'name', 1 => 'contactID', 2 => 'color', 3 => 'customID' ]);

			self::$iID = $iID;
			self::$sName = $aTonerInfo['0'];
			self::$iCustomID = $aTonerInfo['3'];

			switch ($aTonerInfo['2']) {

				case 'magenta':
					self::$sColor = 'Magenta';
					break;
				case 'yellow':
					self::$sColor = 'Gelb';
					break;
				case 'black':
					self::$sColor = 'Schwarz';
					break;
				case 'cyan':
					self::$sColor = 'Cyan';
					break;

			}


			// contactID ist nur ein Fremdschlüssel
			$sContact = get_sql_data::find_all_data('SELECT * FROM contact WHERE ID=' . $aTonerInfo['1'], [ 0 => 'name' ]);

			self::$sContactName = $sContact['0'];

		}

		public static function showToner() {

			echo '
                
                <tr>
                
                    <td> ' . self::$sName . ' </td>
                    <td> ' . self::$sColor . ' </td>
                    <td> ' . self::$sContactName . ' </td>
                    <td> ' . self::$iCustomID . ' </td>
                    
                    <td> <input type="submit" value="X" onclick="put(\'' . self::$iID . '\')" name="del_toner" class="simple_button"> </td>
                    
                </tr>
                
            ';

		}

		public static function showForm() {

			echo '
				
				<tr>
			       
			        <td> <input placeholder="Tonernamen eingeben" name="name" id="name"> </td>
				    
				    <td>
				        
				        <select name="color" id="color">
				
							<option value="Schwarz"> Schwarz </option>
							<option value="Gelb"> Gelb </option>
							<option value="Cyan"> Cyan </option>
							<option value="Magenta"> Magenta </option>
			 			
			 			</select>
			 			
				<td>
				
					<select name="contact" id="contact">
				
			';

			$aContact = get_sql_data::find_data_array('SELECT * FROM contact', [ 0 => 'ID', 1 => 'name' ]);

			for($i = 0; $i != count($aContact); $i++) {

				echo ' <option value="' . $aContact[$i]['0'] . '"> ' . ucfirst($aContact[$i]['1']) . ' </option> ';

			}

			echo '
			
									</select>
									
								</td>
								
								<td> <input placeholder="Zusatz, wie ID, eingeben" name="customID" id="customID" type="number"> </td>
								<td> / </td>
								<td> <input type="submit" value="Toner hinzufügen" onclick="addToner()" name="add_toner" class="simple_button"> </td>
								</tr>
							
							
            
            ';

		}

		public static function showScript() {

			echo '
                
                </table>
                
               <form action="" method="post">
                    
                    <div id="form"></div>
                    <input id="submit" name="delete_toner" type="submit" style="background-color: transparent; border: none; color: transparent;">
                    
               </form>
               
               <form action="" method="post">
                    
                    <div id="form_add"></div>
                    <input id="add_toner" name="add_toner" type="submit" style="background-color: transparent; border: none; color: transparent;">
                    
               </form>
               
               <form action="" method="post">
                    
                    <div id="form_add_addon"></div>
                    <input id="add_addon" name="add_addon" type="submit" style="background-color: transparent; border: none; color: transparent;">
                    
               </form>
               
               <form action="" method="post">
                    
                    <div id="form_delete_addon"></div>
                    <input id="submit_addon" name="delete_addon" type="submit" style="background-color: transparent; border: none; color: transparent;">
                    
               </form>
                
                <script>
                    
                    function put(i) {
                        
                        document.getElementById("form").innerHTML = \' <input type="hidden" name="toner_id" value="\'+i+\'"> \';
                        document.getElementById("submit").click();
                        
                    }
                    
                    function put_addon(i) {
                        
                        document.getElementById("form_delete_addon").innerHTML = \' <input type="hidden" name="addon_id" value="\'+i+\'"> \';
                        document.getElementById("submit_addon").click();
                        
                    }
                    
                </script>
                
                <script>
                    
                    function addToner() {
                        
                        var name = document.getElementById("name").value;
                        var color = document.getElementById("color").value;
                        var contact = document.getElementById("contact").value;
                        var customID = document.getElementById("customID").value;
                        
                        document.getElementById("form_add").innerHTML = \' <input type="hidden" name="toner_info" value="\'+name+\';\'+color+\';\'+contact+\';\'+customID+\'"> \';
                        document.getElementById("add_toner").click();
                        
                    }
                    
                    function addAddon() {
                        
                        var name = document.getElementById("nameaddon").value;
                        var typ = document.getElementById("typaddon").value;
                        var contact = document.getElementById("contactaddon").value;
                        var customID = document.getElementById("customIDaddon").value;
                        
                        document.getElementById("form_add_addon").innerHTML = \' <input type="hidden" name="addon_info" value="\'+name+\';\'+typ+\';\'+contact+\';\'+customID+\'"> \';
                        document.getElementById("add_addon").click();
                        
                    }
                    
                </script>
                
            ';

		}

	}

	class manage_addon {

		public static $error_message = 'Fehlerhafte Benutzereingaben: Name, Typ und Kontakt erforderlich';

		public static function add_addon($sAddonInfo) {

			$aAddonInfo = explode(';', $sAddonInfo);

			if ($aAddonInfo['0'] == '') { manage_toner::error(self::$error_message); return -1; }
			if ($aAddonInfo['1'] == '') { manage_toner::error(self::$error_message); return -1; }
			if ($aAddonInfo['2'] == '') { manage_toner::error(self::$error_message); return -1; }

			// get_free() lieferte eine freie ID zurück
			$id = get_sql_data::get_free('addon','ID');

			if($aAddonInfo['3'] == "")
				$aAddonInfo['3'] = 0;

			$line = '`ID`, `name`, `quantity`, `contactID`, `printerID`, `typ`, `min_quantity`, `customID`';
			$new_line = $id . ', \'' . $aAddonInfo['0'] . '\', 0, ' . $aAddonInfo['2'] . ', NULL, \'' . $aAddonInfo['1'] . '\', 1, ' . $aAddonInfo['3'];

			put_sql_data::put_data('addon', $line, $new_line);

		}

		public static function delete_addon($iAddon_id){

			// Wenn Toner ein Fremschlüssel in orders ist, darf der Toner nicht gelöscht werden
			$bControl = self::is_fk($iAddon_id);

			if (!$bControl) {

				put_sql_data::del_data('addon', 'ID', $iAddon_id);

			} else {

				echo ' <script> alert("Zubehör kann nicht gelöscht werden, da er in einer offenen Bestellung steht"); </script> ';

			}

		}

		public static function is_fk($iID) {

			$aControl = get_sql_data::find_all_data('SELECT * FROM orders WHERE addonID=' . $iID, [ 0 => 'addonID' ]);

			return isset($aControl['0']) ? true : false;

		}

		public static function showHeader(){

			echo '

								<tr>
									<th> Name </th>
									<th> Typ </th>
									<th> Kontakt </th>
									<th> Zusatz </th>
									<th> Löschen </th>
								</tr>

			';

		}

		public static function putAddon($iID) {

			$aAddonInfo = get_sql_data::find_all_data('SELECT * FROM addon WHERE ID=' . $iID, [ 0 => 'name', 1 => 'typ', 2 => 'contactID', 3 => 'customID']);

			$aContact = get_sql_data::find_all_data('SELECT * FROM contact WHERE ID=' . $aAddonInfo['2'], [ 0 => 'name' ]);

			$aAddonInfo['2'] = $aContact['0'];

			echo '
                
                <tr>
                
                    <td> ' . $aAddonInfo['0'] . ' </td>
                    <td> ' . $aAddonInfo['1'] . ' </td>
                    <td> ' . $aAddonInfo['2'] . ' </td>
                    <td> ' . $aAddonInfo['3'] . ' </td>
                    
                    <td> <input type="submit" value="X" onclick="put_addon(\'' . $iID . '\')" name="del_addon" class="simple_button"> </td>
                    
                </tr>
                
            ';

		}

		public static function showForm() {

			echo '
				
				<tr>
			       
			        <td> <input placeholder="Zubehörnamen eingeben" name="nameaddon" id="nameaddon"> </td>
				    
				    <td> <input placeholder="Zubehörtyp eingeben" name="typaddon" id="typaddon"> <td>
				
					<select name="contact" id="contactaddon">
				
			';

			$aContact = get_sql_data::find_data_array('SELECT * FROM contact', [ 0 => 'ID', 1 => 'name' ]);

			for($i = 0; $i != count($aContact); $i++) {

				echo ' <option value="' . $aContact[$i]['0'] . '"> ' . ucfirst($aContact[$i]['1']) . ' </option> ';

			}

			echo '
			
									</select>
									
								</td>
								
								<td> <input placeholder="Zusatz, wie ID, eingeben" name="customIDaddon" id="customIDaddon" type="number"> </td>
								<td> / </td>
								<td> <input type="submit" value="Zubehör hinzufügen" onclick="addAddon()" name="addAddon" class="simple_button"> </td>
								</tr>
							
							
            
            ';

		}

	}

?>
