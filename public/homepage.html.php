<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Homepage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/homepage.css">
    <link rel="stylesheet" href="/public/product.css">
    <!-- Link per i loghi usati nel menu e nella barra di ricerca-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- 
      Link importati per usare la libreira jquery
    -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
  </head>
  <body>
    <header>
      <div class="inner-width">
        <i class="menuTendinaBottoni fas fa-bars"></i>
        <nav class="menuNavigazione">
          <a href="/homepage"><i class="fas fa-home home"></i> Home</a>
          <a href="#"><i class="fas fa-align-left about"></i> About</a>
          <?php
            //Se esiste la sessione user, allora mostro il pulsante per eseguire il logout, altrimenti mostro il pulsante per login o registrazione
            if(isset($_SESSION['user'])):
          ?>
            <a href="/logout"><i class="fas fa-users login"></i> Logout</a>
          <?php
            else:
          ?>
            <a href="/login"><i class="fas fa-users login"></i> Login</a>
            <a href="/register"><i class="fas fa-users login"></i> Registrazione</a>
          <?php endif;?>
          <a href="#"><i class="fas fa-headset contatto"></i> Contatto</a>
        </nav>
      </div>
    </header>
    <?php

      if(isset($_GET['happen']))
      {
        //Vedo quale messaggio d'errore devo stampare a video
        if($_GET['happen'] == "waitingAccount")
        {
          echo '
                <center>
                  <div class="successo" id="info">
                    <span class="btnChiusura" onclick="chiudi()">&times;</span>
                    <p>
                      Ti sei registrato correttamente!<br><br>
                      Controlla la tua email per verificare il tuo account.
                    </p>
                  </div>
                </center>
              ';

        }elseif($_GET['happen'] == "accountVerificato")
        {
          echo '
                <center>
                  <div class="successo" id="info">
                    <span class="btnChiusura" onclick="chiudi()">&times;</span>
                    <p>
                      Complimenti, '.$_SESSION['user'].' hai completato la tua registrazione!
                    </p>
                  </div>
                </center>
              ';

        }elseif($_GET['happen'] == "nonAccedere")
        {
          echo '
                <center>
                  <div class="errore" id="info">
                    <span class="btnChiusura" onclick="chiudi()">&times;</span>
                    <p>
                      Non puoi accedere a questa pagina!
                    </p>
                  </div>
                </center>
              ';

        }elseif($_GET['happen'] == "erroreAccount")
        {
          echo '
                <center>
                  <div class="errore" id="info">
                    <span class="btnChiusura" onclick="chiudi()">&times;</span>
                    <p>
                      C\' è stato un problema durante la verifica dell\'account.
                    </p>
                  </div>
                </center>
              ';

        }
      }else //Se esiste la sessione vuol dire che l'utente si è loggato, quindi do un messaggio di avvenuto login
      if(isset($_SESSION['user']))
      {
        echo '
                <center>
                  <div class="successo" id="info">
                    <span class="btnChiusura" onclick="chiudi()">&times;</span>
                    <p>
                      Ciao, '.$_SESSION['user'].' benvenuto nel sito!
                    </p>
                  </div>
                </center>
              ';
      }

     

    ?>

            <div class="riquadroProdotto">
              <h1>Scarpe Marcello Burlone</h1>
              <p>Lorem ipsum dolor sit amet</p>
              <div class="immagineProdotto"></div>
                <div class="coloreProdotto">
                  <span class="blue active" data-color="#7ed6df" data-pic="url(/public/images/1.png)"></span>
                  <span class="green" data-color="#badc58" data-pic="url(/public/images/2.png)"></span>
                  <span class="yellow" data-color="#f9ca24" data-pic="url(/public/images/3.png)"></span>
                  <span class="rose" data-color="#ff7979" data-pic="url(/public/images/4.png)"></span>
                </div>
              <div class="infoProdotto">
                <div class="prezzoProdotto">$90</div>
                <a href="#" class="carrello">Add to Cart</a>
              </div>
            </div>
          
    



    <!-- 
      Script per il menu a tendina che compare se il sito viene visualizzato sul telefono
    -->
    <script type="text/javascript">
      $(".menuTendinaBottoni").click( function()
        {
          $(this).toggleClass("fa-times");
          $(".menuNavigazione").toggleClass("active");
        }
      );
    </script>

    <!-- 
      Script per l'animazione del cambio dell'immagine e del colore
    -->
     <script>
      $(".coloreProdotto span").click(function(){
        $(".coloreProdotto span").removeClass("active");
        $(this).addClass("active");
        $("body").css("background",$(this).attr("data-color"));
        $(".prezzoProdotto").css("color",$(this).attr("data-color"));
        $(".carrello").css("color",$(this).attr("data-color"));
        $(".immagineProdotto").css("background-image",$(this).attr("data-pic"));
      });
    </script>

        <!-- 
          Script per chiudere il testo del messaggio
        -->
        <script type="text/javascript">
          
          function chiudi()
          {
            document.getElementById('info').style.display='none';
          }

        </script>
    
  </body>
</html>
