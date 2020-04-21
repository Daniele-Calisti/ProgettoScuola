<?php
	
	//stabilisco la connesione al database
	include 'dbConn.php';
	
	//Includo la classe per il login
	include 'class/userClass.php';

	//se l'utente arriva dalla pagina della login
	if(isset($_POST['login']))
	{
		//prendo i dati inseriti
		$user = $_POST['user'];
		$password = md5($_POST['psw']);	//Password criptata in modo da non essere leggibile nel db
		if(empty($user) || empty($password))
		{
			//Ritorno alla pagina login con errore campi non riempiti
			header('location: /login/campiVuotiLogin');
		}else
		{
			$userFunc = new userClass($pdo,'credenziali',$user,$password);	//variabile per l'utente, può eseguire il login oppure registrarsi

			$autenticato = $userFunc->verificaUtente();

			if($autenticato)
			{
				//utente loggato, viene creata una sessione contenente il nome utente (che sarà univoco per ogni utente del sito), per eventuali operazioni.
				$_SESSION['user'] = $user;
				header('location: /homepage'); // --> lo rimando quindi ad un ipotetica homepage
			}
			else
				//Utente non loggato, viene rimandato alla pagina di login con messaggio d'errore
				header('location: /login/noMatch');

		}		
	}elseif(isset($_POST['reg']))	//Se viene dalla pagina di registrazione
	{
		//prendo i dati inseriti
		$user = $_POST['user'];
		$password = md5($_POST['psw']);	//Password criptata in modo da non essere leggibile nel db
		$email = $_POST['email'];
		if(empty($user) || empty($password) || empty($email))
		{
			//Ritorno alla pagina login con errore campi non riempiti
			header('location: /register/campiVuotiRegistrazione');
		}else
		{
			$userFunc = new userClass($pdo,'credenziali',$user,$password,$email);	//variabile per l'utente, può eseguire il login oppure registrarsi
			$userFunc->aggiungiUtente();
			
		}
	}else
	{
		//L'utente è arrivato attraverso l'url senza passare per la pagina di login o registrazione quindi lo rimando alla pagina di login
		header('location: /login');
	}

?>
