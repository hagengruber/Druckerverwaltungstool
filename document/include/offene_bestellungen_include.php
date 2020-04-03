<?php
	
	/*
		
		Author: 					Hagengruber Florian
								    f.hagengruber@schock.de
								    +49 9921 600 290

	*/
	
	// offene_bestellungen_include.php
	
	class openOrders{

	    public static function addOrder($iOrderSerie) {

	        // Speichert alle Infos der angekommenen Bestellserie
            $aOrder = get_sql_data::find_data_array('SELECT * FROM orders WHERE order_serie=' . $iOrderSerie, [ 0 => 'ID', 1 => 'quantity', 2 => 'tonerID', 3 => 'addonID' ]);
            $aOrderOutput = [];

            // Bestellung in Datenbank speichern
            for ($i = 0; $i != count($aOrder); $i++) {

                if ($aOrder[$i]['2'] == '') {

                    $aInfo = get_sql_data::find_all_data('SELECT * FROM addon WHERE ID=' . $iID = $aOrder[$i]['3'], [ 0 => 'typ' ]);
                    $sTable = 'addon';
                    $iID = $aOrder[$i]['3'];
                    $aOrderOutput += [ count($aOrderOutput) => 'Zubehör ' . ucfirst($aInfo['0']) . ' mit der ID b' . $iID . ' etikettieren' ];

                } else {

                    $aInfo = get_sql_data::find_all_data('SELECT * FROM toner WHERE ID=' . $iID = $aOrder[$i]['2'], [ 0 => 'color' ]);

                    switch ($aInfo['0']) {

                        case 'black':
                            $aInfo['0'] = 'Schwarz';
                            break;
                        case 'yellow':
                            $aInfo['0'] = 'Gelb';
                            break;
                        case 'magenta':
                            $aInfo['0'] = 'Magenta';
                            break;
                        case 'cyan':
                            $aInfo['0'] = 'Cyan';
                            break;

                    }

                    $sTable = 'toner';
                    $iID = $aOrder[$i]['2'];
                    $aOrderOutput += [ count($aOrderOutput) => 'Toner ' . ucfirst($aInfo['0']) . ' mit der ID a' . $iID . ' etikettieren' ];

                }

                $aQuantity = get_sql_data::find_all_data('SELECT * FROM ' . $sTable . ' WHERE ID=' . $iID, [ 0 => 'quantity' ]);

                $iQuantity = $aQuantity['0'] + $aOrder[$i]['1'];

                print_r($aOrderOutput);

                put_sql_data::update_data($sTable, 'ID', $iID, 'quantity', $iQuantity);

            }

            // Lösche Bestellungen von orders
            put_sql_data::del_data('orders', 'order_serie', $iOrderSerie);

            return $aOrderOutput;

        }

	    public static function showHeader() {

            echo '
					
                <center>
                
                <div class="table_box">
                    
                    <table>
                    
                        <tr>
                            <th> <img src="images/paket_icon.png" height="150px"> </th>
                        
				';

        }

        public static function showOrders() {

            // Zuerst werden alle Bestellungen in einem zweidimensionalem Array gespeichert
            $aOrderInfo = get_sql_data::find_data_array('SELECT * FROM orders', [ 0 => 'ID', 1 => 'vDate', 2 => 'quantity', 3 => 'contactID', 4 => 'order_serie', 5 => 'tonerID', 6 => 'addonID' ]);

            if (!isset($aOrderInfo['0'])) {

                echo '
                    
                    <table class="table">
                        
                        <tr>
                            <th> Datum </th>
                            <th> Tonername </th>
                            <th> Farbe </th>
                            <th> Menge </th>
                            <th> Drucker </th>
                            <th> Standort </th>
                        </tr>
                        
                         <tr>
                            <th> / </th>
                            <th> / </th>
                            <th> / </th>
                            <th> / </th>
                            <th> / </th>
                            <th> / </th>
                        </tr>
                        
                ';

                return 0;

            }

            // Jetzige order_serie
            $iOrderSerie = $aOrderInfo['0']['4'];

            echo '
                
                <form action="" method="POST">
                            
                    <table class="table">
                        
                        <tr>
                            <th> Datum </th>
                            <th> Tonername </th>
                            <th> Typ / Farbe </th>
                            <th> Menge </th>
                            <th> Drucker </th>
                            <th> Standort </th>
                            <th> <input type="submit" value="Bestellung angekommen" class="simple_button"></th>
                        </tr>
                        
                        <input type="hidden" name="order_serie" value="' . $iOrderSerie .'">
                
            ';

            // Gibt alle Bestellung aus
            for ($i = 0; $i != count($aOrderInfo); $i++) {

                if (!$aOrderInfo[$i]['4'] == $iOrderSerie) {

                    echo '
                        
                        <form action="" method="POST">
                                    
                            <table class="table">
                                
                                <tr>
                                    <th> Datum </th>
                                    <th> Tonername </th>
                                    <th> Typ / Farbe </th>
                                    <th> Menge </th>
                                    <th> Drucker </th>
                                    <th> Standort </th>
                                    <th> <input type="submit" value="Bestellung angekommen" class="simple_button"></th>
                                </tr>
                                
                                <input type="hidden" name="order_serie" value="' . $iOrderSerie .'">
                        
                    ';

                   $iOrderSerie = $iOrderSerie[$i]['4'];

               }

                if ($aOrderInfo[$i]['5'] != NULL) {

                    // Farbe speichern
                    $aTonerInfo = get_sql_data::find_all_data('SELECT * FROM toner WHERE ID=' . $aOrderInfo[$i]['5'], [ 0 => 'color', 1 => 'name', 2 => 'printerID' ]);

                    // Farbe auf Deutsch
                    switch ($aTonerInfo['0']) {

                        case 'black':
                            $aTonerInfo['0'] = 'Schwarz';
                            break;
                        case 'yellow':
                            $aTonerInfo['0'] = 'Gelb';
                            break;
                        case 'magenta':
                            $aTonerInfo['0'] = 'Magenta';
                            break;
                        case 'cyan':
                            $aTonerInfo['0'] = 'Cyan';
                            break;

                    }

                } else {

                    // Farbe speichern
                    $aTonerInfo = get_sql_data::find_all_data('SELECT * FROM addon WHERE ID=' . $aOrderInfo[$i]['6'], [ 0 => 'typ', 1 => 'name', 2 => 'printerID' ]);

                }

                // Drucker info
                $aPrinterInfo = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $aTonerInfo['2'], [ 0 => 'name', 1 => 'locationID' ]);

                $aLocation = get_sql_data::find_all_data('SELECT * FROM location WHERE ID=' . $aPrinterInfo['1'], [ 0 => 'name' ]);

                $aPrinterInfo['1'] = $aLocation['0'];

                echo '
                    
                     <tr>
                        <td> ' . $aOrderInfo[$i]['1'] . ' </td>
                        <td> ' . $aTonerInfo['1'] . ' </td>
                        <td> ' . $aTonerInfo['0'] . ' </td>
                        <td> ' . $aOrderInfo[$i]['2'] . ' </td>
                        <td> ' . $aPrinterInfo['0'] . ' </td>
                        <td> ' . ucfirst($aPrinterInfo['1']) . ' </td>
                    </tr>
                    
                ';

            }

        }
		
	}
	
?>