<?php

    /*

        Author: 					Hagengruber Florian
                                    f.hagengruber@schock.de
                                    +49 9921 600 290

    */

    // warenkorb_include.php
    // Beinhaltet die Funktionen für Warenkorb.php

    class cart{

        public static function delete_offer() {

            setcookie('order', 'false', time() -1500);
            header('Location: ./index.php');
            exit;

        }

        public static function add_offer() {

            // Nun wird die Bestellung geordnet in einem Array gespeichert
            // Da das Cookie am Ende noch ein ; enthält, wird in explode() ein leerer Eintrag im Array erstellt
            // unset löscht diesen Eintrag
            $orders = explode(';', $_COOKIE['order']);
            unset($orders[count($orders) - 1]);

            // Erstellt einen weiteren Array, in dem jeweils andere Arrays gespeichert werden sollen
            // Diese enthalten die TonerID und die Anzahl
            $toner_orders = [];
            for ($i = 0; $i != count($orders); $i++)
                $toner_orders += [ count($toner_orders) => explode(',', $orders[$i]) ];

            // der Kontakt wird definiert
            $contactID = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $_GET['printer'], [ 0 => 'contactID' ]);

            $line = 'ID, vDate, quantity, contactID, order_serie, tonerID, addonID';
            $order_serie = get_sql_data::get_free('orders', 'order_serie');
            $date = date('Y-m-d');

            // Jede Bestellung wird nun in die Datenbank gespeichert
            for ($i = 0; $i != count($toner_orders); $i++) {

                $id = get_sql_data::get_free('orders', 'ID');

                if ($toner_orders[$i]['2'] == 0) {

                    $new_line = '' . $id . ', \'' . $date . '\', ' . $toner_orders[$i]['1'] . ', '. $contactID['0'] . ', ' . $order_serie . ', ' . $toner_orders[$i]['0'] . ', NULL';

                } else {

                    $new_line = '' . $id . ', \'' . $date . '\', ' . $toner_orders[$i]['1'] . ', '. $contactID['0'] . ', ' . $order_serie . ', NULL, ' . $toner_orders[$i]['0'] . '';

                }

                put_sql_data::put_data('orders', $line, $new_line);

            }

            self::delete_offer();

        }

        public static function put_header() {

            echo '
            
                <center>
            
                    <div class="table_box">
                
                        <table>
                        
                            <tr>
                                <th> <img src="images/warenkorb_icon.png" height="150px" style="float: left;"> </th>
                            
                                <th>
                
                                    <table class="table">
                                    
        ';

        }

        public static function put_offer() {

            if (!isset($_COOKIE['order'])) {

                echo ' <span style="color: grey;"> Momentan sind keine Waren im Warenkorb </span> ';
                return 0;

            }

            $printer_info = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $_GET['printer'], [ 0 => 'name', 1 => 'ip', 2 => 'customID' ]);

            echo ' <br> <span style="font-size: 150%;"> Bestellung für <a href="http://' . $printer_info['1'] . '" target="_blank">' . $printer_info['0'] . '</a> <br><br> Druckerzusatz: ' . $printer_info['2'] . ' </span> <br><br> ';

            echo '
                
                <table class="table">
                
                    <tr>
                        <th> Name </th>
                        <th> Farbe </th>
                        <th> Menge </th>
                        <th> Zusatz </th>
                    </tr>
                
            ';

            // Bestellungen sind folgendermaßen in Cookie gespeichert:
            // IDToner,Anzahl,Typ;IDToner,Anzahl,Typ; usw...
            // Bestellungen werden in Array gespeichert

            $orders = explode(';', $_COOKIE['order']);

            // Da das Cookie am Ende noch ein ; enthält, wird in explode() ein leerer Eintrag im Array erstellt
            // unset löscht diesen Eintrag
            unset($orders[count($orders) - 1]);

            // Erstelle einen zweidimensionalen Array
            // Diese enthalten die TonerID und die Anzahl
            $toner_orders = [];

            for ($i = 0; $i != count($orders); $i++)
                $toner_orders += [ count($toner_orders) => explode(',', $orders[$i]) ];

            // Ausgabe des Warenkorbs
            for ($i = 0; $i != count($toner_orders); $i++){

                if ($_GET['printer'] != $toner_orders[$i]['3']) {

                    $aPrinterInfo = get_sql_data::find_all_data('SELECT * FROM printer WHERE ID=' . $toner_orders[$i]['3'], [ 0 => 'name', 1 => 'ip' ]);

                    echo '

                        <script>
                            
                            document.getElementsByTagName("center")[1].innerHTML = \'<div class="table_box"> <table> <tr> <th> <img src="images/warenkorb_icon.png" height="150px" style="float: left;"> </th> <th> <table class="table"> Fehler: Noch offene Bestellung für den Drucker ' . $aPrinterInfo['0'] . '\';
                            
                        </script>
                        
                    ';

                    return -1;

                }

                if ($toner_orders[$i]['2'] == 0) {

                    // Beschaffung der Informationen für die Ausgabe
                    // Benötigte Infos.: Name, Farbe
                    $toner_info = get_sql_data::find_all_data('SELECT * FROM toner WHERE ID=' . $toner_orders[$i]['0'], [ 0 => 'name', 1 => 'color', 2 => 'customID' ]);

                    // Farben auf Deutsch übersetzen
                    switch ($toner_info['1']) {

                        case 'black':
                            $toner_info['1'] = 'Schwarz';
                            break;

                        case 'yellow':
                            $toner_info['1'] = 'Gelb';
                            break;

                        case 'cyan':
                            $toner_info['1'] = 'Cyan';
                            break;

                        case 'magenta':
                            $toner_info['1'] = 'Magenta';
                            break;

                    }

                } else {

                    // Beschaffung der Informationen für die Ausgabe
                    // Benötigte Infos.: Name, Farbe
                    $toner_info = get_sql_data::find_all_data('SELECT * FROM addon WHERE ID=' . $toner_orders[$i]['0'], [ 0 => 'name', 1 => 'typ',  2 => 'customID' ]);

                }

                echo '
                    
                        <tr>
                            <td> '. $toner_info['0'] . '</td>
                            <td> ' . $toner_info['1'] . '</td>
                            <td> '. $toner_orders[$i]['1'] . '</td>
                            <td> '. $toner_orders[$i]['2'] . '</td>
                        </tr>
                        
                ';

            }

            echo '
                        
                            </table>
                            
                            <form action="" method="POST">
                                <input type="submit" name="add_ofer" value="Bestellung bestätigen" class="simple_button">
                            </form>
                            
                            <form action="" method="POST">
                                <input type="submit" name="delete_ofer" value="Bestellung abbrechen" class="simple_button">
                            </form>
                            
                        </div>
                        
                    </div>
                    
                </center>
                
            ';

        }

    }

?>