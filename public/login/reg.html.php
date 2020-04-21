<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>َRegistration Form</title>
    <link rel="stylesheet" href="/ProgettoAttampato/generic/public/login/style.css">
  </head>
  <body>

    <form class="box" action="/loadingLogin" method="post">
      <?php

          if(isset($_GET['happen']))
          {
            //Vedo quale messaggio d'errore devo stampare a video
            if($_GET['happen'] == "campiVuotiRegistrazione")
            {
              echo '
                    <center>
                      <div class="errore" id="info">
                        <span class="btnChiusura" onclick="chiudi()">&times;</span>
                        <p>
                          Compila tutti i campi per continuare 
                        </p>
                      </div>
                    </center>
                  ';

            }else{
              echo '
                    <center>
                      <div class="errore" id="info">
                        <span class="btnChiusura" onclick="chiudi()">&times;</span>
                        <p>
                          '.$_GET['happen'].'
                        </p>
                      </div>
                    </center>
                  ';
            }
          }

      ?>
      <h1>Registrazione</h1>
      <input type="text" name="email" placeholder="Email" >
      <input type="text" name="user" placeholder="Username">
      <input type="password" name="psw" placeholder="Password">
      <input type="submit" name="reg" value="Login">
        <p>
          Sei già registrato? <a href="/login">Clicca qui</a>
        </p>
      </form>



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
