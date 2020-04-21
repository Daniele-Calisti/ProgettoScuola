<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>َLogin Form</title>
    <link rel="stylesheet" href="/public/login/style.css">
  </head>
  <body>

      <form class="box" action="/loadingLogin" method="post">
        
            <?php

                if(isset($_GET['happen']))
                {
                  //Vedo quale messaggio d'errore devo stampare a video
                  if($_GET['happen'] == "campiVuotiLogin")
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

                  }
                  if($_GET['happen'] == "noMatch")
                  {
                    echo '
                          <center>
                            <div class="errore" id="info">
                              <span class="btnChiusura" onclick="chiudi()">&times;</span>
                              <p>
                                Username o password incorretti
                              </p>
                            </div>
                          </center>
                        ';

                  }
                }

            ?>
        
        <h1>Login</h1>
        <input type="text" name="user" placeholder="Username">
        <input type="password" name="psw" placeholder="Password">
        <input type="submit" name="login" value="Login">

        <p>
          Non hai ancora un account? <a href="/register">Clicca qui</a>
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
