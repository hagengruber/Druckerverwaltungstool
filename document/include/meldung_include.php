<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			10.10.2019
		
		Letztes Änderungsdatum: 	10.10.2019
		
	*/
	

	
	class meldung{

        public static $aWarning_Messages = [];
        public static $aError_Messages = [];
        public static $iID;
        public static $sName;
        public static $sIP;
        public static $sLocation;

        public static function setPrinter($id) {

            // Speichert alle Namen der Tabellen, deren Infos benötigt werden
            $doc = [

                0 => 'ID',
                1 => 'name',
                2 => 'ip',
                3 => 'locationID',
                4 => 'contactID'

            ];

            // Speichert alle Infos, die in $doc definiert wurden
            $aPrinterInfo = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $id, $doc);

            self::$iID = $aPrinterInfo['0'];
            self::$sName = $aPrinterInfo['1'];
            self::$sIP = $aPrinterInfo['2'];

            // Location ist nur ein Fremdschlüssel
            $aLocation = get_sql_data::find_all_data('SELECT * FROM location WHERE ID=' . $aPrinterInfo['3'], [ 0 => 'name' ]);
            self::$sLocation = $aLocation['0'];

        }

        public static function setWarning() {

            // In $aToner werden alle Toner-IDs gespeichert, die zum Drucker gehören
            $aToner = get_sql_data::find_all_data('SELECT * FROM toner WHERE printerID=' . self::$iID, [ 0 => 'ID' ]);

            // Solange nicht alle Toner abgearbeitet worden sind
            for ($i=0; $i != count($aToner); $i++) {

                // In $aTonerInfo werden die aktuellen Werte des Toners gespeichert
                $aTonerInfo = get_sql_data::find_all_data('SELECT * FROM toner WHERE ID=' . $aToner[$i], [ 0 => 'color', 1 => 'quantity', 2 => 'min_quantity', 3 => 'name' ]);

                // Wenn der Tonerbestand gleich oder kleiner dem mindestbestand ist
                if ($aTonerInfo['1'] <= $aTonerInfo['2']) {

                    switch ($aTonerInfo['0']) {

                        case 'cyan':
                            $sColor = 'Cyan';
                            break;
                        case 'black':
                            $sColor = 'Schwarz';
                            break;
                        case 'yellow':
                            $sColor = 'Gelb';
                            break;
                        case 'magenta':
                            $sColor = 'Magenta';
                            break;

                    }

                    // Speichere die Warnung in ein Zweidimensionales Array
                    self::$aWarning_Messages += [ count(self::$aWarning_Messages) => [ 0 => $sColor, 1 => $aTonerInfo['1'], 2 => $aTonerInfo['3'], 3 => self::$sName, 4 => self::$sLocation, 5 => 0 ] ];

                }

            }

            // In $aToner werden alle Toner-IDs gespeichert, die zum Drucker gehören
            $aAddon = get_sql_data::find_all_data('SELECT * FROM addon WHERE printerID=' . self::$iID, [ 0 => 'ID' ]);

            // Solange nicht alle Toner abgearbeitet worden sind
            for ($i=0; $i != count($aAddon); $i++) {

                // In $aTonerInfo werden die aktuellen Werte des Toners gespeichert
                $aAddonInfo = get_sql_data::find_all_data('SELECT * FROM addon WHERE ID=' . $aAddon[$i], [ 0 => 'typ', 1 => 'quantity', 2 => 'min_quantity', 3 => 'name' ]);

                // Wenn der Tonerbestand gleich oder kleiner dem mindestbestand ist
                if ($aAddonInfo['1'] <= $aAddonInfo['2'])
                    // Speichere die Warnung in ein Zweidimensionales Array
                    self::$aWarning_Messages += [ count(self::$aWarning_Messages) => [ 0 => $aAddonInfo['0'], 1 => $aAddonInfo['1'], 2 => $aAddonInfo['3'], 3 => self::$sName, 4 => self::$sLocation, 5 => 1 ] ];

            }

        }

        public static function setError() {

            // In $aTonerLevel werden alle Toner des Druckers und deren Stände gespeichert
            $aTonerLevel = get_toner::get_toner_level(self::$sIP);

            for ($i = 0; $i != count($aTonerLevel); $i++) {

                $aTonerDatabaseLevel = get_sql_data::find_all_data('SELECT * FROM toner WHERE printerID=' . self::$iID . ' AND COLOR=\'' . $aTonerLevel[$i]['0'] . '\'', [ 0 => 'level', 1 => 'ID']);

                if(isset($aTonerDatabaseLevel['0'])){

                    if ($aTonerDatabaseLevel['0'] < $aTonerLevel[$i]['1'] ) {

                        switch ($aTonerLevel[$i]['0']){

                            case 'cyan':
                                $sColor = 'Cyan';
                            break;
                            case 'black':
                                $sColor = 'Schwarz';
                                break;
                            case 'yellow':
                                $sColor = 'Gelb';
                                break;
                            case 'magenta':
                                $sColor = 'Magenta';
                                break;

                        }

                        self::$aError_Messages += [ count(self::$aError_Messages) => [ 0 => $sColor, 1 => self::$sName, 2 => self::$sLocation, 3 => $aTonerLevel[$i]['1'], 4 => $aTonerDatabaseLevel['1'] ] ];

                    }

                }

            }

        }

        public static function putBodyError() {

            echo '
                
                <center>
				
					<div class="table_box">
						
						<table>
						
							<tr>
								<th> <img src="images/meldung.png" height="150px"> </th>
							
								<th>
						
									<table class="table">
									
												<tr>
													<th> Fehler </th>
													<th> Beschreibung </th>
													<th> Betroffenes Gerät </th>
													<th> Standort </th>
													<th> Bestätigen </th>
												</tr>
                
            ';

            for ($i = 0; $i != count(self::$aError_Messages); $i++) {

                echo '
                    
                    <tr>
									
                        <td> Unautorisierter Tonerwechsel </td>
                        <td> Toner wurde ohne Bestätigung der IT-Abteilung getauscht </td>
                        <td> Drucker ' . self::$aError_Messages[$i]['1'] . ' (' . self::$aError_Messages[$i]['0'] . ') </td>
                        <td> ' . ucfirst(self::$aError_Messages[$i]['2']) . ' </td>
                        <td> <input type="submit" onclick="put(\'' . self::$aError_Messages[$i]['3'] . ';' . self::$aError_Messages[$i]['4'] . '\')" name="sub" value="Bestätigen"> </td>
                            
                    </tr>
                    
                ';

            }

            if (count(self::$aError_Messages) == 0) {

                echo '
                    
                    <tr>
									
                        <td> / </td>
                        <td> / </td>
                        <td> / </td>
                        <td> / </td>
                        <td> / </td>
                        
                    </tr>
                    
                ';

            }

            echo '</table>';

            echo '
                
               <form action="" method="post">
                    
                    <div id="form"></div>
                    <input id="submit" name="sub" type="submit" style="background-color: transparent; border: none; color: transparent;">
                    
               </form>
                
                <script>
                    
                    function put(i) {
                        
                        document.getElementById("form").innerHTML = \' <input type="hidden" name="toner_info" value="\'+i+\'"> \';
                        document.getElementById("submit").click();
                        
                    }
                    
                </script>
                
            ';

        }

        public static function putBodyWarning() {

            echo '
                
                <table class="table">
                
                            <tr>
                                <th> Warnung </th>
                                <th> Beschreibung </th>
                                <th> Betroffenes Gerät </th>
                                <th> Standort </th>
                            </tr>
                
            ';

            for ($i = 0; $i != count(self::$aWarning_Messages); $i++) {

                if (self::$aWarning_Messages[$i]['5'] == 0) {

                    echo '
                        
                        <tr>
                                        
                            <td> Tonerbestand gering </td>
                            <td> Der Bestand eines Toners wird gering (' . self::$aWarning_Messages[$i]['1'] . ' Stück) </td>
                            <td> Toner ' . self::$aWarning_Messages[$i]['2'] . ' (' . self::$aWarning_Messages[$i]['0'] . ') </td>
                            <td> ' . ucfirst(self::$aWarning_Messages[$i]['4']) . ' (' . self::$aWarning_Messages[$i]['3'] . ') </td>
                                
                        </tr>
                        
                    ';

                } else {

                    echo '
                        
                        <tr>
                                        
                            <td> Zubehörbestand gering </td>
                            <td> Der Bestand eines Zubehörs wird gering (' . self::$aWarning_Messages[$i]['1'] . ' Stück) </td>
                            <td> Zubehör ' . self::$aWarning_Messages[$i]['2'] . ' (' . self::$aWarning_Messages[$i]['0'] . ') </td>
                            <td> ' . ucfirst(self::$aWarning_Messages[$i]['4']) . ' (' . self::$aWarning_Messages[$i]['3'] . ') </td>
                                
                        </tr>
                        
                    ';

                }

            }

            if (count(self::$aWarning_Messages) == 0) {

                echo '
                    
                    <tr>
									
                        <td> / </td>
                        <td> / </td>
                        <td> / </td>
                        <td> / </td>
                        
                    </tr>
                    
                ';

            }

            echo '</table>';

        }

	}
?>