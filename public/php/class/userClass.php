<?php
	
    session_start();
	//classe che contiene le funzioni di login e registrazione al sito e le funzioni di verifica dell'account dopo la registrazione
	class userClass
	{
		/*
			Variabili private da usare nel costruttore, vengono tutte poste uguali a null, in modo che quando instanzierò la classe da login.php, posso passare alcune variabili del costruttore in base alle mie esigenza, lasciando vuote altre
		*/
		private $pdo;			//Varibaile per la connessione al db
		private $nomeTabella;	//Nome della tabella del db dove voglio lavorare la inserisco nel costruttore in modo da riutilizzare la classe in più script
		private $user;			//Nome dell'utente inserito 
		private $psw;			//Password inserita dall'utente
		private $email;			//Email inserita dall'utente
		
		public function __construct($pdo = null, $nomeTabella = null, $user = null, $psw = null,$email = null)
		{
			/*
				if(isset($var)) --> controllo che la variabile sia stat inserita
			*/
			if(isset($pdo))								$this->pdo = $pdo;					
			if(isset($nomeTabella))						$this->nomeTabella = $nomeTabella;
			if(isset($user))							$this->user = $user;
			if(isset($psw))								$this->psw = $psw;
			if(isset($email))							$this->email = $email;				

		}
		
        /*
			function getUser()

			Variabile utilizzate: $pdo, $nomeTabella, $email

			Ritorna l'utente associato all'email
		*/
        public function getUser()
        {
        	//Query da eseguire per selezionare l'utente 
        	$sql = "SELECT utente FROM ".$this->nomeTabella." WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':email',$this->email);
            $stmt->execute();
            
            //Variabile che contiene l'utente associato all'email
            $result = $stmt->fetchAll();
            
            //Estraggo dalla variabile, il campo della colonna utente
            foreach($result as $row)
            {
            	$user = $row['utente'];
            }
        	
            return $user;
        }
		/*
			function verificaUtente()

			Variabile utilizzate: $pdo, $nomeTabella, $user, $psw

			Ritorna true se l'utente esiste nel db altrimenti false
		*/
		
		public function verificaUtente()
		{
			$autenticato = false;		//Variabile per controllare che l'utente sia stato trovato
			
			try
			{

				$sql = "SELECT * FROM `".$this->nomeTabella."` WHERE `utente` = :user and `password` = :psw and `verificato` = :verificato ";
				$stmt = $this->pdo->prepare($sql);
				$stmt->bindValue(':user',$this->user);
				$stmt->bindValue(':psw',$this->psw);
				$stmt->bindValue(':verificato',1);
				$stmt->execute();

				//Vedo quante righe sono state estratte dalla query
				$righeTrovate = $stmt->rowCount();

				//Se sono state estratte delle righe, allora vuol dire che l'utente esiste
				if($righeTrovate>0)
					$autenticato = true;

			} catch (PDOException $e) 
			{
				echo 'Database error: '. $e->getMessage(). ' in '.$e->getFile(). ' line: '.$e->getLine();
			}

			return $autenticato;
		}


		/*
			function controlloUserEmail()

			Variabile utilizzate: $user, $email

			Controllo singolo su utente e su email per ritornare un errore più specifico
		

			ritorna true se è stato già trovato un nome o un email, altrimenti false
		*/
		private function controlloUserEmail()
		{
			$controllo = false;

			$error = "";	//Messaggio d'errore che verrà ritornato dalla funzione

			try 
			{
				/*
					-------------------------------Controllo utente--------------------------
				*/
				//Uso il metodo con il bindValue per riuscire a contare il numero delle righe estratte dalla query
				$sql = "SELECT * FROM ".$this->nomeTabella." WHERE utente = :user ";
				$stmt = $this->pdo->prepare($sql);
				$stmt->bindValue(':user',$this->user);
				$stmt->execute();
				//Vedo quante righe sono state estratte dalla query
				$righeTrovate = $stmt->rowCount();

				//Se sono state estratte delle righe, allora vuol dire che l'email o lo username già esistono
				if($righeTrovate>0)
				{
					$error .= "Username già esistente<br>";
					$controllo = true;
				}

				/*
					------------------------------Controllo email-------------------------
				*/
				//Uso il metodo con il bindValue per riuscire a contare il numero delle righe estratte dalla query
				$sql = "SELECT * FROM ".$this->nomeTabella." WHERE email = :email ";
				$stmt = $this->pdo->prepare($sql);
				$stmt->bindValue(':email',$this->email);
				$stmt->execute();
				//Vedo quante righe sono state estratte dalla query
				$righeTrovate = $stmt->rowCount();

				//Se sono state estratte delle righe, allora vuol dire che l'email o lo username già esistono
				if($righeTrovate>0)
				{
					$error .= "Email già esistente";
					$controllo = true;
				}

			} catch (PDOException $e) 
			{
				echo 'Database error: '. $e->getMessage(). ' in '.$e->getFile(). ' line: '.$e->getLine();
			}
			

			//Ritorno un array associativo per poter usare tutte e due le variabili 
			return ['controllo' => $controllo, 'errore' => $error];

		}

		public function aggiungiUtente()
		{
			//controllo se il nome utente o l'email sono già stati presi
			$controllo = $this->controlloUserEmail();

			//Prendo la variabile controllo ritornata dalla funzione, se non esiste nè una mail nè un utente allora inserisco l'utente nel db
			if(!$controllo['controllo'])
			{
				try
				{
					//genero il token da usare per la verifica dell'account via mail
					$token = "QWERTYUIOPLKJHGFDSAZXCVBNMqwertyuioplkjhgfdsazxcvbnm1234567890()";
					$token = str_shuffle($token);
					$token = substr($token, 0,10);
					//Uso i ':' al posto dei valori per una questione di sicurezza
					$sql = "INSERT INTO ".$this->nomeTabella." SET
							utente = :user,
							password = :psw,
							email = :email,
							verificato = :verificato,
							token = :token";
					$stmt = $this->pdo->prepare($sql);
					//assegno i valori da inserire nel db
					$stmt->bindValue(':user',$this->user);
					$stmt->bindValue(':psw',$this->psw);
					$stmt->bindValue(':email',$this->email);
					$stmt->bindValue(':verificato',0);
					$stmt->bindValue(':token',$token);
					$stmt->execute();

					//Invio l'email all'utente per verificare l'account
					$this->inviaVerificaEmail($token);

					//Utente registrato correttamente, ma ancora non creo nessuna sessione dato che deve verificare il suo account
					header('location: /homepage/waitingAccount'); // --> lo rimando quindi ad un ipotetica homepage mostrando il messaggio di verificare l'account

				} catch (PDOException $e) 
				{
					echo 'Database error: '. $e->getMessage(). ' in '.$e->getFile(). ' line: '.$e->getLine();
				}
			}else
			{
				//Prendo l'errore ritornato dalla funzione
				$error = $controllo['errore'];

				//Lo rimando alla pagina di registrazione stampando l'errore
				header('location: /register/'.$error);
			}
			
		}

		
		/*
			function inviaVerificaEmail()

			Variabile utilizzate: $email

			invia una mail all'utente per verificare il proprio account
		*/

		private function inviaVerificaEmail($token)
		{
			//Corpo della mail
			$body = '
					<html>
					<head>
						<meta charset="utf-8">
					</head>
					<body>

						<div class="container" style="margin: black 1px solid">

							<a href="www.progettooop.altervista.org/confermaEmail/'.$token.'/'.$this->email.'" target="_blank">Confirm your email</a>

						</div>

					</body>
					</html>	
					';
			//SETTO GLI HEADER CHE SERVONO PER INVIARE L'EMAIL
			$headers = array(
			'Authorization: keyCodeSendgrid',
			'Content-Type: application/json'
			);

			//ARRAY DI DATI (email destinatario, corpo del messaggio,ecc..)
			$data = array(
		    	"personalizations" => array(
				array(
					"to" => array(
							array(
								"email" => $this->email,
								"name" => $this->user
							)
							
						)
				)
			),
			"from" => array(
				"email" =>  "progettoScuola@gmail.com"
			),
			"subject" => "Verifica il tuo account!",
			"content" => array(
				array(
					"type" => "text/html",
					"value" => $body
				)
			)
			);

		 	$ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
		    curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    $response = curl_exec($ch);
		    curl_close($ch);


		}

		/*
			function verificaAccount()

			Variabile utilizzate: email e token passate come parametri dalla pagina login.php

		*/
		public function verificaAccount($getEmail,$getToken)
		{
			$esito = false;
			try 
			{
				//Se ho trovato una riga corrispondente che contiene il token allora setto la variabile verificato = 1
					$sql = "UPDATE ".$this->nomeTabella." SET
							verificato = :verificato WHERE
							token = :token and
							email = :email";
					$stmt = $this->pdo->prepare($sql);
					$stmt->bindValue(':verificato',1);
					$stmt->bindValue(':token',$getToken);
					$stmt->bindValue(':email',$getEmail);
					$stmt->execute();

					//Se dalla query ritorna almeno una riga, vuol dire che ho aggiornato il campo verificato e quindi pongo $esito = true
					if($stmt->rowCount() > 0)
					{
						$_SESSION['user'] = $user; 								//Creo la sessione da usare poi nel sito
						$esito = true;
					}
					

			} catch (PDOException $e) 
			{
				echo 'Database error: '. $e->getMessage(). ' in '.$e->getFile(). ' line: '.$e->getLine();
			}
			
			return $esito;
		}


	}

?>
