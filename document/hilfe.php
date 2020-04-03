<?php
	
	/*
		
		Author: 					Hagengruber Florian
									+49 9921 600 290
									f.hagengruber@schock.de
			
	*/

?>
<link rel="stylesheet" type="text/css" href="../style/help.css">

<center>

<div class="table_box">

	<table>

		<tr>
			<th> <img src="images/help.png" height="150px"> </th>

			<th style="width: 100%;">

                <div class="ques_out">

                    <div class="ques_ask" onclick="addAnswer(1)">

                        Welcher Benutzer darf sich anmelden?

                    </div>

                        <div class="ques_ans_in" id="1">

                            Im Menü befindet sich ein Unterpunkt "Benutzerverwaltung". <br>
                            Dort können alle Benutzer eingetragen werden, die sich anmelden dürfen.

                        </div>

                </div>

                <div class="ques_out">

                    <div class="ques_ask" onclick="addAnswer(2)">

                        Welchen Grund kann es geben, dass sich ein Toner nicht deaktivieren lässt?

                    </div>

                    <div class="ques_ans_in" id="2">

                        Toner lassen sich nicht deaktivieren, wenn diese gerade in einer offenen Bestellung sind. <br>
                        Wenn man diese verbucht, ist das deaktivieren des Elements wieder möglich.

                    </div>

                </div>

                <div class="ques_out">

                    <div class="ques_ask" onclick="addAnswer(3)">

                        Wie lassen sich die Einstellungen eines Druckers ändern?

                    </div>

                    <div class="ques_ans_in" id="3">

                        Um die Einstellungen eines Druckers zu ändern, einfach auf der Druckerübersicht den gewünschten Drucker anklicken. <br>
                        Die Einstellungen des Druckers findet man dann unter "Einstellungen".<br>

                    </div>

                </div>

                <div class="ques_out">

                    <div class="ques_ask" onclick="addAnswer(4)">

                        Wie weist man einen Drucker Zubehör zu?

                    </div>

                    <div class="ques_ans_in" id="4">

                        Dazu muss das Zubehör schon existieren.<br>
                        Nachdem man dieses angelegt hat, klickt man auf der Druckerübersicht auf den gewünschten Drucker.<br>
                        Danach in den Untermenüpunkt "Einstellungen".<br>
                        Nun erscheint unter anderem eine Liste mit allen noch verfügbaren Zubehören.

                    </div>

                </div>

        <script>

            function addAnswer(t) {

                if(document.getElementById(t).className === "ques_ans_in") {

                    document.getElementById(t).className = 'ques_ans_out';

                } else {
					
					document.getElementById(t).className = 'ques_ans_in';
					
				}

            }

        </script>