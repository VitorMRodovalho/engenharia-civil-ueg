<?php
//REDIMENSIONAMENTO DE PAGINA
  $pagina_redimen = "biblioteca.php";

//CONFIGURAÇÃO DO TEMPO DE PERMANENCIA
  function Estoura() {

//TEMPO DE PERMANENCIA: MINUTOS
/*--------------------------*/
    $tempo_valido = 20;
/*--------------------------*/

    //ULTIMO TEMPO VALIDO P/ USUARIO ONLINE
    $unixtime_valido = strtotime('NOW')-(60*$tempo_valido);

    //DELETA TEMPOS ESTOURADOS
    $sql = "DELETE FROM tb_bibengc_logado WHERE unixtime < '$unixtime_valido';";
    $query = mysql_query($sql);
  }
?>
