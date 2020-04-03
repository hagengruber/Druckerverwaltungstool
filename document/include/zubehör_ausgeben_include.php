<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// Toner_ausgeben_include.php
	
	class put_addon{

        public static function delete($iId, $iTyp){

            if ($iTyp == 0) {

                $table = 'toner';
                $name = 'Toner';
                $sID = 'a';

            } else {

                $table = 'addon';
                $name = 'Zubehör';
                $sID = 'b';

            }

            $quantity = get_sql_data::find_all_data('SELECT * FROM ' . $table . ' WHERE ID=' . $iId, [ 0 => 'quantity' ]);

			put_sql_data::update_data($table, 'ID', $iId, 'quantity', $quantity['0'] - 1);

			return $name . ' mit der ID ' .  $sID . $iId . ' ausgeben';
			
		}

        public static function put_header(){
			
			echo '
				
				<center>
				
				<div class="table_box">
					
					<table>
					
						<tr>
							<th> <img src="images/mitarbeiter_icon.png" height="150px"> </th>
						
					<th>
					
					<table class="table">
								
								<tr>
									<th> Name </th>
									<th> Typ </th>
									<th> Farbe </th>
									<th> Menge </th>
								</tr>
			';
			
		}

        public static function put_all_toner(){

		    // In $toner werden alle Toner gespeichert, die zum Drucker gehören
		    $toner = get_sql_data::find_all_data('SELECT * FROM toner WHERE printerID=' . $_GET['printer'], [ 0 => 'ID',  1 => 'name', 2 => 'color', 3 => 'quantity']);

		    for ($i = 0; $i != count($toner); $i++){

		        $toner_id = $toner[$i++];
		        $toner_name = $toner[$i++];

		        switch ($toner[$i++]){

                    case 'black':
                        $toner_color = 'Schwarz';
                        break;

                    case 'cyan':
                        $toner_color = 'Cyan';
                        break;

                    case 'magenta':
                        $toner_color = 'Magenta';
                        break;

                    case 'yellow':
                        $toner_color = 'Gelb';
                        break;

                }

		        $toner_quantity = $toner[$i];

                echo '
                    
                    <tr>
                        <form action="" method="POST">
                        <td> ' . $toner_name . ' </td>
                        <td> Toner </td>
                        <td> ' . $toner_color . ' </td>
                        <td> ' . $toner_quantity . ' </td>
                        
                ';

                if($toner_quantity == 0){

                    echo '</tr>';

                } else {

                    echo '
                        
                        <td> <input type="submit" value="Toner rausgeben" class="simple_button"> </td>
                        
                    ';

                }

                echo '
                    
                         <input type="hidden" name="delete" value="' . $toner_id . '">
                         <input type="hidden" name="typ" value="0">
                   
                     </form>
                    
                    </tr>
                    
                ';

            }

        }

        public static function put_all_addon(){

            // In $toner werden alle Toner gespeichert, die zum Drucker gehören
            $aAddon = get_sql_data::find_data_array('SELECT * FROM addon WHERE printerID=' . $_GET['printer'], [ 0 => 'ID',  1 => 'name', 2 => 'typ', 3 => 'quantity']);

            for ($i = 0; $i != count($aAddon); $i++) {

                $iAddonID = $aAddon[$i]['0'];
                $sAddonName = $aAddon[$i]['1'];

                $iAddonQuantity = $aAddon[$i]['3'];

                echo '
                    
                    <tr>
                        <form action="" method="POST">
                        <td> ' . $sAddonName . ' </td>
                        <td> ' . $aAddon[$i]['2'] . ' </td>
                        <td> / </td>
                        <td> ' . $aAddon[$i]['3'] . ' </td>
                        
                ';

                if($aAddon[$i]['3'] == 0){

                    echo '</tr>';

                } else {

                    echo '
                        
                        <td> <input type="submit" value="Zubehör rausgeben" class="simple_button"> </td>
                        
                    ';

                }

                echo '
                    
                         <input type="hidden" name="delete" value="' . $aAddon[$i]['0'] . '">
                         <input type="hidden" name="typ" value="1">
                   
                     </form>
                    
                    </tr>
                    
                ';

            }

        }

	}
	
?>