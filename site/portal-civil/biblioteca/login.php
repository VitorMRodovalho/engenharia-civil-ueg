<?php
  //INICIA SESSÃO
  session_start();
  //CRIA CHAVE DE VERIFIÇÃO
  $_SESSION['token'] = uniqid(time());

  //ERRO DE LOGIN
  if(isset($_GET["erro"])) {
    echo "<br><p align=\"top\"><b>Não foi possível efetuar o login: </b>".$_GET['erro']."</p>";
  }
?>
<html>
  <head>
    <title>Login - Bem Vindo Usuário!</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos/style.css" rel="stylesheet" type="text/css">
  </head>
  <body topmargin="0" marginwidth="0" onload="document.login.usuario.focus()" ondragstart="return false" oncontextmenu="return false" onselectstart="return false">
    <form name="login" method="post" action="valida.php" enctype="multipart/form-data">
        <table width="100%" align="top">
          <tr>
            <th colspan="2" style="background-color: #CDCDCD"><div align="left">
				<span style="font-weight: 400"><font size="4">Login</font></span></div></th>
          </tr>
          <tr>
            <td width="10%" class="texto">Usuário</td>
            <td width="87%"><input type="text" name="usuario" autocomplete="off"></td>
          </tr>
          <tr>
            <td class="texto">Senha</td>
            <td><input type="password" name="senha" autocomplete="off"></td>
          </tr>
          <tr>
            <td colspan="2">
              <div align="center">
                <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                <input type="submit" name="submit" value="Entrar">
              </div></td>
          </tr>
          <tr>
            <td colspan="2">
              <!-- <div align="center"><a href="esquece_senha.php">esqueceu a senha</a></div> -->
            </td>
          </tr>
        </table>
    </form>
  </body>
</html>