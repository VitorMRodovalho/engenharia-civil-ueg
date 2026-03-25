<?
  //FINALIZA SESSÃO LOGIN
  if(isset($_SESSION['acesso'])) {

    //EXCLUI TEMPO LOGIN
    $conexao = Conexao();
    $usuario=$_SESSION['acesso']['usuario'];
    $sql= "DELETE FROM tb_bibengc_logado WHERE user_logado='$usuario'";
    $logout= mysql_query($sql) or die('erro');
    $conexao = Fechar();

    //DESREGISTRA ACESSO
    session_unregister('acesso');
    echo "<br><p align=\"center\"><b>Logout: </b>Sessão finalizada</p>";
  }
?>

