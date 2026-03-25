<?PHP
/*
  biblioteca_conf.php
*/

/* banco de dados */
   $DB_SGBD="mysql";
   $DB_HOST="REDACTED";
   $DB_PORT="";
   $DB_USER="REDACTED";
   /*
     N�o se esque�a de colocar a senha!
   */
   $DB_PASS="REDACTED";
   $DB_DATA="REDACTED";
   
/* localização */
   $MI_LANG="pt_BR";
   $MI_LOCALE="pt_BR.iso8859-1";
   $MI_CHAR="iso-8859-1";
   
/* diretórios */
   $MI_HOME_DIR="estante";
   $MI_HTTP_DIR="../biblioteca";
   $MI_LIBS_DIR=$MI_HOME_DIR."/rotinas";

/* nome da biblioteca */
   $MI_NAME="Biblioteca do Centro Acad�mico de Engenharia Civil - UEG";
   $MI_SUBT="Uso interno da Engenharia Civil";
   /*
     14 dias � uma sugest�o, mude se assim desejar
   */
   $MI_DAYS=7;
?>
