<?php
  //INICIA SESSÃO
  session_start();
  Verifica();
function Verifica() {

  //SCRIPT CONEXÃO BD
  include "conexao/conexao.php";
  $conexao = Conexao();
  //TEMPO ESTOURADO
  include "tempo_config.php";
  $estoura = Estoura();

  //VERIFICA SESSÃO
  if(isset($_SESSION['acesso'])) {
    //REGISTRA DADOS DINAMICAMENTE
    foreach($_SESSION['acesso'] as $indice => $valor) {
      $$indice = $valor;
    }
    //SELECIONA UNIXTIME
    $sql="SELECT unixtime FROM tb_bibengc_logado WHERE user_logado='$usuario' ORDER BY unixtime DESC;";
    $select=mysql_query($sql);
    $dados = mysql_fetch_array($select);
    //VERIFICA TEMPO DA SESSÃO ESTOROU
    if(mysql_num_rows($select) <= 0) {
      header("LOCATION: login.php?erro=Login finalizado!");
    }
    //ID SESSÃO
    $id = session_id();
    //IP USUARIO
    $IP = $_SERVER['REMOTE_ADDR'];
    //UNIXTIME DB
    $unixtime = $dados['unixtime'];
    //CHAVE ATUAL
    $chave_atual = md5($usuario.$id.$IP.$unixtime);
    //VERIFICA CHAVE
    if($chave_atual != $chave) {
    header("LOCATION: login.php?erro=Login inválido!");
    }
    //TEMPO ATUAL
    $unixtime=strtotime('now');

    //RENOVA TEMPO DA SESSÃO
    $sql= "UPDATE tb_bibengc_logado SET unixtime='$unixtime' WHERE user_logado='$usuario';";
	$query= mysql_query($sql);
    $conexao = Fechar();

    //CRIA CHAVE DE SEGURANÇA
    $chave = md5($usuario.$id.$IP.$unixtime);

    //REGISTRA DADOS NA SESSÃO
    $_SESSION['acesso'] = array("usuario" => $usuario, "chave" => $chave, "permissao" => $permissao);
  }
  else {
    header("LOCATION: login.php?erro=Login inexistente!");
  }
}
?>
