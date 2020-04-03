<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			19.09.2019
		
		Letztes Änderungsdatum: 	19.09.2019
		
	*/
	
	// print_barcode.php
	// Druckt Barcodes
	
	echo '<center><img style="width: 200px; margin-top: 30vh;" src="barcode.php?text='.$_GET['id'].'&codetype=code128&orientation=horizontal&size=20&print=true"/></center>';
	
?>