<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			12.09.2019
		
		Letztes Änderungsdatum: 	12.09.2019
		
	*/
	
	// login.php
	// Stellt das Anmeldeformular zur Verfügung
	
	
?>

</div>

<div class="login_box">
	
	<center>
	
		<div class="login_box_inner">
			
			<form action="index.php?login=true" method="POST">
				
				<?php
					
					// Wenn ein Fehler bei der Anmeldung besteht, existiert $error
					// Dann gib Fehlermedlung aus
					if(isset($error)){
						
						echo ' <input type="text" name="username" required="" id="login_box_error" class="login_box_input" placeholder="Benutzernamen eingeben"> <br> ';
					
					} else {
						
						echo ' <input type="text" name="username" required="" class="login_box_input" placeholder="Benutzernamen eingeben"> <br> ';
					
					}
					
				?>
				
				<input type="password" name="password" required="" class="login_box_input" placeholder="Passwort eingeben"> <br>
				<input class="login_box_submit" type="submit" value="Anmelden">
			
			</form>
			
		</div>
	
	</center>
	
</div>