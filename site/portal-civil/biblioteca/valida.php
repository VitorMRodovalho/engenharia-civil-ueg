<?php
  //INICIA SESSÃO
  session_start();
  //SCRIPT CONEXÃO BD
  include "conexao/conexao.php";
  include "tempo_config.php";
  
  // VERIFICA CHAVE CRIADA
  if(isset($_POST['token']) && isset($_SESSION['token'])&&  $_POST['token'] == $_SESSION['token']) {

    //DESRESTRITA FORM
    session_unregister('token');
    //REGISTRA DADOS DINAMICAMENTE
    foreach($_POST as $indice => $valor) {
      $$indice = $valor;
    }

  if(strlen($senha)>=6 && !empty($usuario) && !empty($senha)) {

    //FILTRA DADOS
    $usuario = str_replace("'", "", "$usuario");

    //CRIPTOGRAFA A SENHA COM O SALT DAS DUAS PRIMEIRAS LETRAS DO USUARIO
    //$Senha = substr(crypt($Senha,substr($Usuario, 0, 2)), 2);
    $senha = md5($senha);

    //CONECTA BD
    $conexao = Conexao();
    // FAZ A PESQUISA
    $sql ="SELECT id, usuario, senha, permissao FROM tb_bibengc_usuario WHERE usuario='$usuario' and senha='$senha';";
    $select = mysql_query($sql) or die ("<b>Erro ao Selecionar Dados</b>");

    if(mysql_num_rows($select) > 0) {

      //PEGA DADOS DB
      $dados = mysql_fetch_array($select);
      //PREPARA DADOS
      $id_usuario = $dados['id'];
      $usuario = $dados['usuario'];
      $permissao = $dados['permissao'];
      //ID SESSÃO
      $id = session_id();
      //IP USUARIO
      $IP = $_SERVER['REMOTE_ADDR'];
      //HORA ATUAL
      $unixtime = strtotime('now');

      //CRIA CHAVE DE SEGURANÇA
      $chave = md5($usuario.$id.$IP.$unixtime);
      // LIBERA PESQUISA
      $conexao = Liberar($select);
    
      //INCLUI DADOS tb_bibengc_LOGADO
      $sql = "INSERT INTO tb_bibengc_logado (user_logado, unixtime, perm_logado, id_user) VALUES ('$usuario','$unixtime','$permissao','$id_usuario');";
      $insert = @mysql_query($sql) or die ("<b>Erro ao Incluir Dados: </b>".mysql_error());

      if($insert) {

        //FECHA CONEXAO
        $conexao = Fechar();
      
        //REGISTRA DADOS NA SESSÃO
        $_SESSION['acesso'] = array("usuario" => $usuario, "chave" => $chave, "permissao" => $permissao);
        header("LOCATION: $pagina_redimen");
        }
      }
      else {
        header("LOCATION: login.php?erro=Usuários ou senha inexistentes!");
      }
    }
    else {
      header("LOCATION: login.php?erro=Usuários ou senha inválidos!");
    }
  }
  else {
    header("LOCATION: login.php?erro=Dados inexistentes, erro ao enviar!");
  }
?>
