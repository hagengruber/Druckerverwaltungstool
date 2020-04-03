<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	//Einstellungen_include.php
	// Einstellungen zeigen und setzten
	
	class settings{

        static $iId;
        static $sName;
        static $sIp;
        static $iCustomID;
        static $sLocation;

        public static function set_variable($id){

            $aInfoPrinter = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $id, [ 0 => 'ID', 1 => 'name', 2 => 'ip', 3 => 'customID', 4 => 'locationID'  ]);

			// Setzte VARIABLEN
			self::$iId = $aInfoPrinter['0'];
			self::$sName = $aInfoPrinter['1'];
			self::$sIp = $aInfoPrinter['2'];
			self::$iCustomID = $aInfoPrinter['3'];

			$location = get_sql_data::find_all_data('location', 'SELECT * FROM location WHERE ID=' . $aInfoPrinter['4'], [ 0 => 'name' ]);

			self::$sLocation = $location['0'];

		}

        public static function get_header(){
			
			$header = '
				
				<center>
				
					<div class="table_box">
						
						<table>
						
							<tr>
								<th> <img src="images/einstellung.png" height="150px"> </th>
							
								<th style="padding-left: 150px; width: 500px; text-align: left;">
									
			';
			
			return $header;
			
		}

        public static function get_general_settings(){

			$form = '
				
				<br>
				
				<span style="margin-left: 10px;"> Allgemeine Einstellungen </span>
				
				<hr>
				
			';
			
			$form .= '
				
				<table style="width: 500px; text-align: left;">
				
					<tr>
						<th>  </th>
						<th>  </th>
						<th>  </th>
					</tr>
					
					<tr>
						<th> Name: </th>
						<th> <input value="' . self::$sName . '" id="name" style="font-size: 100%; padding: 5px;"> </th>
						<th> <input placeholder="Druckernamen eingeben" onclick="httpRequest(\'name\', \'' . self::$iId . '\', 0)" type="submit" value="&#10004;" style="font-size: 100%;"> </th>
					</tr>
					
					<tr>
						<th> Zusatz: </th>
						<th> <input value="' . self::$iCustomID . '" id="customID" style="font-size: 100%; padding: 5px;"> </th>
						<th> <input placeholder="CustomID eingeben" onclick="httpRequest(\'customID\', \'' . self::$iId . '\', 1)" type="submit" value="&#10004;" style="font-size: 100%;"> </th>
					</tr>
					
                </table>
				
			';
			
			$form .= '
				
				<script>
					
					function httpRequest(n, id, idf){
						
						var name = document.getElementById(n).value;
						
						var request = new XMLHttpRequest();
						var file = "./document/event.php";
						
						request.open("POST", file, true);
						request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
						request.send("settings=" + name + "," + id + "," + idf);
						request.close();
						
					}
					
				</script>
				
			';
			
			return $form;
			
		}

        public static function get_assign_toner() {

			$form = '
				
				<br><br>
				
				<span style="margin-left: 10px;"> Toner zuordnen </span>
				
				<hr>
				
			';

			$aTonerInfo = get_sql_data::find_data_array('SELECT * FROM toner', [ 0 => 'ID', 1 => 'color', 2 => 'printerID', 3 => 'name' ]);

			$form .= '
			
				<table style="width: 500px; text-align: left;">
				
					<tr>
						<th> Tonername </th>
						<th> Tonerfarbe </th>
						<th> Status </th>
					</tr>
			
			';

			for ($i = 0; $i != count($aTonerInfo); $i++) {

			    if ($aTonerInfo[$i]['2'] != '') {

                    if ($aTonerInfo[$i]['2'] == $_GET['printer']) {

                        $out = true;
                        $status = 'Ja';

                    } else {

                        $out = false;

                    }

                } else {

			        $out = true;
                    $status = 'Nein';

                }

			    if ($out) {

			        switch ($aTonerInfo[$i]['1']) {

                        case 'black':
                            $aTonerInfo[$i]['1'] = 'Schwarz';
                            break;
                        case 'cyan':
                            $aTonerInfo[$i]['1'] = 'Cyan';
                            break;
                        case 'magenta':
                            $aTonerInfo[$i]['1'] = 'Magenta';
                            break;
                        case 'yellow':
                            $aTonerInfo[$i]['1'] = 'Gelb';
                            break;

                    }

                    $form .= '
					
							<tr>
							
								<td> ' . $aTonerInfo[$i]['3'] . ' </td>
								<td> ' . $aTonerInfo[$i]['1'] . ' </td>
								<td>
								    <form action="" method="POST">
								        <input value="' . $status . '" type="submit" name="chgStatus">
								        <input type="hidden" name="ID" value="' . $aTonerInfo[$i]['0'] . '">
								        <input type="hidden" name="typ" value="0">
								    </form>
                                </td>
							
							</tr>
							
							
						
					';

                }

            }

			$form .= '
				
				</table>
				
			';

			return $form;
			
		}

        public static function get_assign_addon() {

            $form = '
				
				<br><br>
				
				<span style="margin-left: 10px;"> Zubehör zuordnen </span>
				
				<hr>
				
			';

            $aAddonInfo = get_sql_data::find_data_array('SELECT * FROM addon', [ 0 => 'ID', 1 => 'typ', 2 => 'printerID', 3 => 'name' ]);

            $form .= '
			
				<table style="width: 500px; text-align: left;">
				
					<tr>
						<th> Name </th>
						<th> Typ </th>
						<th> Status </th>
					</tr>
			
			';

            for ($i = 0; $i != count($aAddonInfo); $i++) {

                if ($aAddonInfo[$i]['2'] != '') {

                    if ($aAddonInfo[$i]['2'] == $_GET['printer']) {

                        $out = true;
                        $status = 'Ja';

                    } else {

                        $out = false;

                    }

                } else {

                    $out = true;
                    $status = 'Nein';

                }

                if ($out) {

                    $form .= '
					
							<tr>
							
								<td> ' . $aAddonInfo[$i]['3'] . ' </td>
								<td> ' . $aAddonInfo[$i]['1'] . ' </td>
								<td>
								    <form action="" method="POST">
								        <input value="' . $status . '" type="submit" name="chgStatus">
								        <input type="hidden" name="ID" value="' . $aAddonInfo[$i]['0'] . '">
								        <input type="hidden" name="typ" value="1">
								    </form>
                                </td>
							
							</tr>
							
					';

                }

            }

            $form .= '
				
				</table>
				
			';

            return $form;

        }

        public static function chgStatus($id, $typ) {

            if ($typ == 0) {

                $table = 'toner';

            } else {

                $table = 'addon';

            }

            $status = get_sql_data::find_all_data('SELECT * FROM ' . $table . ' WHERE ID=' . $id, [ 0 => 'printerID' ]);

            if (empty($status['0'])) {

                put_sql_data::update_data($table, 'ID', $id, 'printerID', $_GET['printer']);

            } elseif ($status['0'] != $_GET['printer']){

                $chg = false;

            } elseif ($status['0'] == $_GET['printer']){

                put_sql_data::update_data($table, 'ID', $id, 'printerID', 'NULL');

            }

        }

        public static function get_warning_toner() {

        $form = '
				
				<br><br>
				
				<span style="margin-left: 10px;"> Warnung bei Toneranzahl ausgeben </span>
				
				<hr>
				
				<table>
				
			';

        $color = [

            0 => 'cyan',
            1 => 'yellow',
            2 => 'magenta',
            3 => 'black'

        ];

        $color_translate = [

            0 => 'Cyan',
            1 => 'Gelb',
            2 => 'Magenta',
            3 => 'Schwarz'

        ];

        for ($i = 0; $i != count($color); $i++) {

            $result = get_sql_data::find_all_data('SELECT * FROM toner WHERE printerID=' . $_GET['printer'] . ' AND color=\'' . $color[$i] . '\'', [ 0 => 'ID', 1 => 'min_quantity' ]);

            if (isset($result['0'])){

                $form .= '
			        
                        <tr>

                            <td style="padding-right: 10px;"> ' . $color_translate[$i] . ': </td>
                            <td> <input type="number" value="' . $result['1'] . '" id="' . $color[$i] . '" style="font-size: 100%; padding: 5px;"> <input onclick="httpRequest(\'' . $color[$i] . '\', \'' . $result['0'] . '\', 2)" type="submit" value="&#10004;" style="font-size: 100%;"> </td>

                        </tr>
                        
                    ';

            }

        }

        return $form;

    }

        public static function get_warning_addon() {

            $form = '
				
				</table>
				
				<br><br>
				
				<span style="margin-left: 10px;"> Warnung bei Zubehöranzahl ausgeben </span>
				
				<hr>
				
				<table>
				
			';

            $aAddon_info = get_sql_data::find_data_array('SELECT * FROM addon WHERE printerID=' . self::$iId, [ 0 => 'ID', 1 => 'name', 2 => 'min_quantity' ]);

            for ($i = 0; $i != count($aAddon_info); $i++) {

                $form .= '
                
                    <tr>

                        <td style="padding-right: 10px;"> ' . $aAddon_info[$i]['1'] . ': </td>
                        <td> <input type="number" value="' . $aAddon_info[$i]['2'] . '" id="' . $aAddon_info[$i]['0'] . '" style="font-size: 100%; padding: 5px;"> <input onclick="httpRequest(\'' . $aAddon_info[$i]['0'] . '\', \'' . $aAddon_info[$i]['0'] . '\', 3)" type="submit" value="&#10004;" style="font-size: 100%;"> </td>

                    </tr>
                    
                ';

            }

            return $form;

        }
		
	}

?>