<?php

	//Includo la classe per il login
	include 'class/userClass.php';

	//stabilisco la connesione al database
	include 'dbConn.php';

		$userFunc = new userClass($pdo,'credenziali');	//Instanza della classe per verificare l'account dell'utente
		//Passo come parametri l'email e il token preso dal'url
		$esito = $userFunc->verificaAccount($_GET['email'],$_GET['token']);
		
		if($esito)
			header('location : /homepage/accountVerificato');		//Rimando ad una ipotetica homepage con messaggio di avvenuta verifica dell'account
		else
			header('location : /homepage/erroreAccount');			//Rimando ad un homepage con errore di verifica account

?>