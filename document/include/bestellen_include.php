<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// bestellen_include.php
	// Beinhaltet Funktionen von Bestellen.php
	
	class order{

	    static $iId;
	    static $sName;
	    static $sIp;
	    static $sLocation;

	    static $black;
	    static $yellow;
	    static $cyan;
	    static $magenta;

	    public static function error_message() {

            echo '
                
                <script>
                    alert("Benutzereingabe bei Menge falsch");
                </script>
                
            ';

        }

        public static function add_order($aPost){

            // Benutzereingabe prüfen
            if (!isset($aPost['quantity']))
                $error = true;

            if ($aPost['quantity'] <= 0)
                $error = true;

            if (isset($error)) {

                self::error_message();
                return -1;

            }

            $typ = $aPost['kind'];

            // In $cookie werden alle Waren gespchert
            // Wenn schon andere Waren gespeichert werden,
            // werden diese in $cookie gespchert, damit diese nicht überschrieben werden
            $cookie = '';

			// Wenn schon waren im Warenkorb sind
			if(isset($_COOKIE['order']))
				// Speichere Waren in $cookie
				$cookie = $_COOKIE['order'];

			// Speichere Waren in Cookie
			setcookie('order', $cookie . $aPost['id'] . ',' . $aPost['quantity'] . ',' . $typ . ',' . $_GET['printer'] . ';');
			// Aktualisiere die Seite, damit Cookies wirksam sind
			header('Location: index.php?site=Bestellen&printer=' . $_GET['printer']);
			
		}

		public static function set_var($id){

            // Gibt einen Array mit den gewünschten Inhalten zurück
            $aPrinterInfo = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $id, [ 0 => 'name', 1 => 'ip', 2 => 'locationID' ]);

            self::$iId = $id;
            self::$sName = $aPrinterInfo['0'];
            self::$sIp = $aPrinterInfo['1'];

            // LocationID beinhaltet nur einen Fremdschlüssel
            $aLocation = get_sql_data::find_all_data('SELECT * FROM location WHERE ID=' . $aPrinterInfo['2'], [ 0 => 'name' ]);
            self::$sLocation = $aLocation['0'];

        }

		public static function show_header(){

			echo '
				
				<center>
				
					<form action="" mehtod="POST">

						<input value="Zum Warenkorb" type="submit" class="submit_button_submenu" style="float: right;">
						<input type="hidden" name="site" value="Warenkorb">
						<input type="hidden" name="printer" value="' . self::$iId . '">
						
					</form>
					
					<div class="druckereigenschaften_location">
						
						' . ucfirst(self::$sLocation) . '
						
					</div>
					
					<div class="table_box">
					
						<table>
						
							<tr>
								<th> <img src="images/drucker_icon.png" height="150px" style="float: left; margin-top: 40px;"> </th>
							
						<th>
						
						<table class="table">
						
							<a target="_blank" href="http://' . self::$sIp . '">' . self::$sName . '</a>
						
			';

			if(isset($_COOKIE['order'])) {

			    $printer = explode(';', $_COOKIE['order']);

                unset($printer[count($printer) - 1]);

                // Erstelle einen zweidimensionalen Array
                // Diese enthalten die TonerID und die Anzahl
                $toner_orders = [];

                for ($i = 0; $i != count($printer); $i++)
                    $toner_orders += [ count($toner_orders) => explode(',', $printer[$i]) ];

                if ($_GET['printer'] != $toner_orders['0']['3']) {

                    $aPrinterInfo = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $toner_orders['0']['3'], [ 0 => 'name', 1 => 'ip' ]);

                    echo '

                        <script>
                            
                            document.getElementsByTagName("center")[1].innerHTML = \'<div class="table_box"> <table> <tr> <th> <img src="images/warenkorb_icon.png" height="150px" style="float: left;"> </th> <th> <table class="table"> Fehler: Noch offene Bestellung für den Drucker ' . $aPrinterInfo['0'] . '\';
                            
                        </script>
                        
                    ';

                    return -1;

                }

            }

		}

		public static function set_toner(){

            // Speichert alle gewünschten Werte in einen zweidimensionalen Array
            $aTonerInfo = get_sql_data::find_data_array('SELECT * FROM toner WHERE printerID=' . self::$iId, [ 0 => 'ID', 1 => 'color' ]);

            // Setzt die Variablen für die Toner
            for ($i = 0; $i != count($aTonerInfo); $i++){

                switch ($aTonerInfo[$i]['1']) {

                    case 'black':
                        self::$black = $aTonerInfo[$i]['0'];
                        break;

                    case 'yellow':
                        self::$yellow = $aTonerInfo[$i]['0'];
                        break;

                    case 'magenta':
                        self::$magenta = $aTonerInfo[$i]['0'];
                        break;

                    case 'cyan':
                        self::$cyan = $aTonerInfo[$i]['0'];
                        break;

                }

            }

        }

        public static function show_toner(){

            // Gibt Header der Tabelle aus
            echo '
				
				<table class="table">
					
					<tr>
						<th> Bezeichnung </th>
						<th> Typ </th>
						<th> Farbe </th>
						<th> Kontakt </th>
						<th> Menge </th>
					</tr>
					
			';

            // Speichert alle Farben in den Array
            $aIDToner = [

                0 => self::$black,
                1 => self::$cyan,
                2 => self::$magenta,
                3 => self::$yellow

            ];

            // Solange nicht jeder Toner behandelt wurde
            for ($i = 0; $i != count($aIDToner); $i++) {

                // Wenn eine ID zu dem jeweiligen Toner existiert
                if ($aIDToner[$i] != '') {

                    // Speichert alle gwünschten Einrtäge in den Array
                    $aTonerInfo = get_sql_data::find_all_data('SELECT * FROM toner WHERE ID=' . $aIDToner[$i], [ 0 => 'name', 1 => 'contactID', 2 => 'quantity', 3 => 'color' ]);

                    // ContactID ist nur ein Fremdschlüssel
                    $aContact = get_sql_data::find_all_data('SELECT * FROM contact WHERE ID=' . $aTonerInfo['1'], [ 0 => 'name' ]);

                    // Speichert den namen des Kontaktes
                    $aTonerInfo['1'] = $aContact['0'];

                    // Speichert den namen auf Deutsch
                    switch ($aTonerInfo['3']){

                        case 'black':
                            $aTonerInfo['3'] = 'Schwarz';
                            break;

                        case 'yellow':
                            $aTonerInfo['3'] = 'Gelb';
                            break;

                        case 'cyan':
                            $aTonerInfo['3'] = 'Cyan';
                            break;

                        case 'magenta':
                            $aTonerInfo['3'] = 'Magenta';
                            break;

                    }

                    // Gibt Infos aus
                    echo ' <tr> <td>' . $aTonerInfo['0'] . '</td> <td> Toner </td> <td> ' . $aTonerInfo['3'] . '</td> <td> ' . $aTonerInfo['1'] . '</td> <td> ' . $aTonerInfo['2'] . '</td> ';

                    // Gibt Info für Bestellung aus
                    echo '
                        
                        <form action="" method="POST">
                        
                            <td>
                                
                                <input type="number" min="1" placeholder="Menge angeben" required="" name="quantity">
                                    
                            </td>
                            
                            <input type="hidden" name="id" value="' .  $aIDToner[$i] . '">
                            <input type="hidden" name="kind" value="0">
                            
                            <td>
                                
                                <input type="submit" name="add_order" value="Zur Bestellung hinzufügen" class="simple_button">
                                
                            </td>
                        
                         </form>
                        
                        </tr>
                        
                    ';

                }

            }

		}

        public static function show_addon(){

	        // Speichert alle Infos in den zweisimensionalen Array $aAddonInfo
            $aAddonInfo = get_sql_data::find_data_array('SELECT * FROM addon WHERE printerID=' . self::$iId, [ 0 => 'ID', 1 => 'typ', 2 => 'contactID', 3 => 'quantity', 4 => 'name' ]);

            // Solange nicht jeder Toner behandelt wurde
            for ($i = 0; $i != count($aAddonInfo); $i++) {

                    // ContactID ist nur ein Fremdschlüssel
                    $aContact = get_sql_data::find_all_data('SELECT * FROM contact WHERE ID=' . $aAddonInfo[$i]['2'], [ 0 => 'name' ]);

                    // Speichert den namen des Kontaktes
                    $aAddonInfo[$i]['2'] = $aContact['0'];

                    // Gibt Infos aus
                    echo ' <tr> <td>' . $aAddonInfo[$i]['4'] . '</td> <td> ' . $aAddonInfo[$i]['1'] . '</td> <td> / </td> <td> ' . $aAddonInfo[$i]['2'] . '</td> <td> ' . $aAddonInfo[$i]['3'] . '</td> ';

                    // Gibt Info für Bestellung aus
                    echo '
                        
                        <form action="" method="POST">
                        
                            <td>
                                
                                <input type="number" min="1" placeholder="Menge angeben" required="" name="quantity">
                                    
                            </td>
                            
                            <input type="hidden" name="id" value="' .  $aAddonInfo[$i]['0'] . '">
                            <input type="hidden" name="kind" value="1">
                            
                            <td>
                                
                                <input type="submit" name="add_order" value="Zur Bestellung hinzufügen" class="simple_button">
                                
                            </td>
                        
                         </form>
                        
                        </tr>
                        
                    ';

            }

        }
		
	}
?>