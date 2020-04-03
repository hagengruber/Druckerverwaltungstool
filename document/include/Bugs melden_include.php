<?php
	
	/*
		
		Author: 					Hagengruber Florian
								f.hagengruber@schock.de
								+49 9921 600 290
								
		Erstellungsdatum:			14.10.2019
		
		Letztes Änderungsdatum: 	14.10.2019
		
	*/
	
	// Bugs melden
	// Meldet Bugs
	
	class bug{
		
		public static function send_mail($text) {
			
			$mail = new EMail;

			$mail->Server = "schock-de.mail.protection.outlook.com";
			$mail->Username = '';
			$mail->Password = '';
			$mail->SetFrom("noreply@schock.de","Druckerverwaltungstool");
			$mail->AddTo('f.hagengruber@schock.de',"Hagengruber Florian");
			$mail->Subject = "Neuer Bug";
			
			$mail->Message = $text;

			$mail->ContentType = "text/html"; 

			$success = $mail->Send(); // 1 -> Erfolgreich gesendet
			
		}

		public static function put_form(){
			
			// ###################### VARIABLEN DEKLARIEREN ######################
				
				// Erzeuge Objekte
				// Objekte zur Übersichtlichkeit
				
				
				
			// ###################### VARIABLEN DEKLARIEREN ENDE ######################
			
			echo '
				
				<center>
				
					<div class="table_box">
						
						<table>
						
							<tr>
								<th> <img src="images/bug.png" height="150px"> </th>
							
								<th>
									
									<form action="" method="POST">
										
										<textarea name="text" rows="10" cols="50" style="font-family: Arial; margin: 10px 0 10px 50px; padding: 5px 5px 5px 5px; border-radius: 10px; border: 1px solid black;" placeholder="Beschreibung des Bugs eintippen"></textarea>
										
										<input type="submit" name="report" value="Report senden" class="simple_button">
										
									</form>
									
								</th>
							
							</tr>
						
						</table>
					
					</div>
					
				</center>
				
			';
			
		}
		
	}
	
	class EMail
	{
		
	  const newline = "\r\n";

	  private
		$Port, $Localhost, $skt;

	  public
		$Server, $Username, $Password, $ConnectTimeout, $ResponseTimeout,
		$Headers, $ContentType, $From, $To, $Cc, $Subject, $Message,
		$Log;

	  function __construct()
	  {
		$this->Server = "127.0.0.1";
		$this->Port = 25;
		$this->Localhost = "localhost";
		$this->ConnectTimeout = 30;
		$this->ResponseTimeout = 8;
		$this->From = array();
		$this->To = array();
		$this->Cc = array();
		$this->Log = array();
		$this->Headers['MIME-Version'] = "1.0";
		$this->Headers['Content-type'] = "text/plain; charset=iso-8859-1";
	  }

	  private function GetResponse()
	  {
		stream_set_timeout($this->skt, $this->ResponseTimeout);
		$response = '';
		while (($line = fgets($this->skt, 515)) != false)
		{
	 $response .= trim($line) . "\n";
	 if (substr($line,3,1)==' ') break;
		}
		return trim($response);
	  }

	  private function SendCMD($CMD)
	  {
		fputs($this->skt, $CMD . self::newline);

		return $this->GetResponse();
	  }

	  private function FmtAddr(&$addr)
	  {
		if ($addr[1] == "") return $addr[0]; else return "\"{$addr[1]}\" <{$addr[0]}>";
	  }

	  private function FmtAddrList(&$addrs)
	  {
		$list = "";
		foreach ($addrs as $addr)
		{
		  if ($list) $list .= ", ".self::newline."\t";
		  $list .= $this->FmtAddr($addr);
		}
		return $list;
	  }

	  function AddTo($addr,$name = "")
	  {
		$this->To[] = array($addr,$name);
	  }

	  function AddCc($addr,$name = "")
	  {
		$this->Cc[] = array($addr,$name);
	  }

	  function SetFrom($addr,$name = "")
	  {
		$this->From = array($addr,$name);
	  }
	  function Send()
	  {
		$newLine = self::newline;

		//Connect to the host on the specified port
		$this->skt = fsockopen($this->Server, $this->Port, $errno, $errstr, $this->ConnectTimeout);

		if (empty($this->skt))
		  return false;

		$this->Log['connection'] = $this->GetResponse();

		//Say Hello to SMTP
		$this->Log['helo']     = $this->SendCMD("EHLO {$this->Localhost}");

		//Request Auth Login
		$this->Log['auth']     = $this->SendCMD("AUTH LOGIN");
		$this->Log['username'] = $this->SendCMD(base64_encode($this->Username));
		$this->Log['password'] = $this->SendCMD(base64_encode($this->Password));

		//Email From
		$this->Log['mailfrom'] = $this->SendCMD("MAIL FROM:<{$this->From[0]}>");

		//Email To
		$i = 1;
		foreach (array_merge($this->To,$this->Cc) as $addr)
		  $this->Log['rcptto'.$i++] = $this->SendCMD("RCPT TO:<{$addr[0]}>");

		//The Email
		$this->Log['data1'] = $this->SendCMD("DATA");

		//Construct Headers
		if (!empty($this->ContentType))
		  $this->Headers['Content-type'] = $this->ContentType;
		$this->Headers['From'] = $this->FmtAddr($this->From);
		$this->Headers['To'] = $this->FmtAddrList($this->To);
		if (!empty($this->Cc))
		  $this->Headers['Cc'] = $this->FmtAddrList($this->Cc);
		$this->Headers['Subject'] = $this->Subject;
		$this->Headers['Date'] = date('r');

		$headers = '';
		foreach ($this->Headers as $key => $val)
		  $headers .= $key . ': ' . $val . self::newline;

		$this->Log['data2'] = $this->SendCMD("{$headers}{$newLine}{$this->Message}{$newLine}.");

		// Say Bye to SMTP
		$this->Log['quit']  = $this->SendCMD("QUIT");

		fclose($this->skt);

		return substr($this->Log['data2'],0,3) == "250";
		
	  }
	  
	}
	
?>
