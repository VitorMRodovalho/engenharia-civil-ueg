<?php
        // Abre conexao
        function Conexao(){
        
            $host = "REDACTED";
            $user = "REDACTED";
            $pass = "REDACTED";
            $bd = "REDACTED";
            
            @$conecta = mysql_connect("$host", "$user", "$pass") or die ("<b>Erro na conex�o: </b>".mysql_error());
            @$banco = mysql_select_db("$bd") or die ("<b>Erro na configura��o do Banco de Dados: </b>".mysql_error());
        }

         // Fecha conexao
        function Fechar(){

            mysql_close();
        }

        // Libera consulta da memoria
        function Liberar($Query){

            mysql_free_result($Query);
        }
?>
