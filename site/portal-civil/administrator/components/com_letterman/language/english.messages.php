<?php

define( "LM_SUBSCRIBE_SUBJECT", "Subscrição da Newsletter do ..." );
define( "LM_SUBSCRIBE_MESSAGE", 
"Olá [NAME],

Foi adicionado com sucesso à Newsletter do ...

Para confirmar a sua subscrição, por favor clique no link abaixo indicado.

www.???.??/[LINK]

Obrigado!



_________________________
[mosConfig_live_site]" );

define( "LM_UNSUBSCRIBE_SUBJECT", "Serviço de Newsletter [mosConfig_live_site]: Cancelamento da subscrição" );
define( "LM_UNSUBSCRIBE_MESSAGE", 
"Olá [NAME],

Foi cancelada a subscrição da Newsletter da [mosConfig_live_site].
Obrigado por usar os nossos serviços.

________________________
[mosConfig_live_site]" );

define( "LM_NEWSLETTER_FOOTER", 
"<br/><br/>___________________________________________________________<br/>
Recebeu esta Newsletter após ter subscrito<br/>
no serviço de Newsletter[mosConfig_live_site].<br/>
Para cancelar a subscrição clique aqui: [UNLINK]" );

/* Module */
define( "LM_FORM_NOEMAIL", "Por favor indique um endereço de e-mail válido." );
define( "LM_FORM_SHORTERNAME", "Por favor indique um nome de subscritor. Obrigado." );
define( "LM_FORM_NONAME", "Por favor indique um nome de subscritor. Obrigado." );
define( "LM_SUBSCRIBE", "Subscrever" );
define( "LM_UNSUBSCRIBE", "Cancelar subscrição" );
define( "LM_BUTTON_SUBMIT", "OK" );

/* Backend */
define( "LM_ERROR_NEWSLETTER_COULDNTBESENT", "Impossivel enviar Newsletter!" );
define( "LM_NEWSLETTER_SENDTO_X_USERS", "Newsletter enviada para {X} utilizadores" );
define( "LM_IMPORT_USERS", "Importar subscritores" );
define( "LM_EXPORT_USERS", "Exportar subscritores" );
define( "LM_UPLAOD_FAILED", "Falhou upload" );
define( "LM_ERROR_PARSING_XML", "Erro Parsing the XML File" );
define( "LM_ERROR_NO_XML", "Por Favor upload unicamente xml files" );
define( "LM_ERROR_EMAIL_ALREADY_ONLIST", "Este e-mail já consta na base de dados" );
define( "LM_SUCCESS_ON_IMPORT", "Importado com sucesso {X} Subscritores." );
define( "LM_IMPORT_FINISHED", "Importação terminada" );
define( "LM_ERROR_DELETING_FILE", "Erro ao apagar ficheiro" );
define( "LM_DIR_NOT_WRITABLE", "Impossivel escrever na directoria ".$GLOBALS['mosConfig_cachepath'] );
define( "LM_ERROR_INVALID_EMAIL", "Endereço de e-mail inválido" );
define( "LM_ERROR_EMPTY_EMAIL", "Endereço de e-mail vazio" );
define( "LM_ERROR_EMPTY_FILE", "Erro: Ficheiro vazio" );
define( "LM_ERROR_ONLY_TEXT", "Apenas texto" );

define( "LM_SELECT_FILE", "Por favor escolha um ficheiro" );
define( "LM_YOUR_XML_FILE", "Seu YaNC/Letterman XML exportação do ficheiro" );
define( "LM_YOUR_CSV_FILE", "CSV Importação de ficheiro" );
define( "LM_POSITION_NAME", "Posição do -Nome- coluna" );
define( "LM_NAME_COL", "Nome Coluna" );
define( "LM_POSITION_EMAIL", "Posição do -E-mail- coluna" );
define( "LM_EMAIL_COL", "E-mail Coluna" );
define( "LM_STARTFROM", "Iniciar importação da linha..." );
define( "LM_STARTFROMLINE", "Iniciar da linha" );
define( "LM_CSV_DELIMITER", "CSV Delimitador" );
define( "LM_CSV_DELIMITER_TIP", "CSV Delimitador: , ; or Tabulador" );

/* Newsletter Management */
define( "LM_NM", "Assistente de Newsletter" );
define( "LM_MESSAGE", "Mensagem" );
define( "LM_LAST_SENT", "Último envio" );
define( "LM_SEND_NOW", "Enviar agora" );
define( "LM_CHECKED_OUT", "Confirmar" );
define( "LM_NO_EXPIRY", "Terminar: Não expira" );
define( "LM_WARNING_SEND_NEWSLETTER", "Tem a certeza que pretende enviar a newsletter?\\nAviso: Se enviar o e-mail para um largo grupo de subscritores poderá demorar algum tempo!" );
define( "LM_SEND_NEWSLETTER", "Enviar Newsletter" );
define( "LM_SEND_TO_GROUP", "Enviar para o grupo" );
define( "LM_MAIL_FROM", "E-mail enviado de" );
define( "LM_DISABLE_TIMEOUT", "Desactivar timeout" );
define( "LM_DISABLE_TIMEOUT_TIP", "Check to prevend the script generating a timeout error. <br/><strong>Não funciona em modo de segurança!<strong>" );
define( "LM_REPLY_TO", "Responder para" );
define( "LM_MSG_HTML", "Mensagem (HTML-WYSIWYG)" );
define( "LM_MSG", "Mensagem (HTML-source)" );
define( "LM_TEXT_MSG", "Mensagem alternativa" );
define( "LM_NEWSLETTER_ITEM", "Conteúdo da Newsletter" );

/* Subscriber Management */
define( "LM_SUBSCRIBER", "Subscrever" );
define( "LM_NEW_SUBSCRIBER", "Nova Subscrição" );
define( "LM_EDIT_SUBSCRIBER", "Editar Subscrição" );
define( "LM_SELECT_SUBSCRIBER", "Seleccionar Subscritor" );
define( "LM_SUBSCRIBER_NAME", "Nome do subscritor" );
define( "LM_SUBSCRIBER_EMAIL", "E-mail do subscritor" );
define( "LM_SIGNUP_DATE", "Data de subscrição" );
define( "LM_CONFIRMED", "Confirmado" );
define( "LM_SUBSCRIBER_SAVED", "A informação do subscritor foi guardada" );
define( "LM_SUBSCRIBERS_DELETED", "Apagou com sucesso {X} subscritores" );
define( "LM_SUBSCRIBER_DELETED", "O subscritor foi apagado com sucesso." );

/* Frontend */
define( "LM_ALREADY_SUBSCRIBED", "Já subscreveu anteriormente a nossa Newsletter." );
define( "LM_NOT_SUBSCRIBED", "Ainda não subscreveu a nossa Newsletter." );
define( "LM_YOUR_DETAILS", "Os seus dados:" );
define( "LM_SUBSCRIBE_TO", "Subscrever a Newsletter" );
define( "LM_UNSUBSCRIBE_FROM", "Cancelar a subscrição da Newsletter" );
define( "LM_VALID_EMAIL_PLEASE", "Por favor indique um endereço de e-mail válido!" );
define( "LM_SAME_EMAIL_TWICE", "O e-mail indicado já consta na base de dados!" );
define( "LM_ERROR_SENDING_SUBSCRIBE", "Uma mensagem de subscrição poderá não ser enviada:" );
define( "LM_SUCCESS_SUBSCRIBE", "O seu e-mail foi adicionado à nossa Newsletter." );
define( "LM_RETURN_TO_NL", "Voltar para as Newsletters" );
define( "LM_ERROR_UNSUBSCRIBE_OTHER_USER", "Lamentamos, mas não poderá eliminar outros subscritores da lista" );
define( "LM_ERROR_SENDING_UNSUBSCRIBE", "Uma mensagem de cancelamento da subscrição poderá não ser enviada:" );
define( "LM_SUCCESS_UNSUBSCRIBE", "O seu e-mail foi removido da nossa Newsletter" );
define( "LM_SUCCESS_CONFIRMATION", "A sua subscrição foi confirmada com sucesso" );
define( "LM_ERROR_CONFIRM_ACC_NOTFOUND", "A subscrição associada ao link de confirmação não foi encontrada." );

define( "LM_CONFIRMED_ACCOUNTS_ONLY", "Apenas subscrições confirmadas?" );
define( "LM_CONFIRMED_ACCOUNTS_ONLY_TIP", "Enviar Newsletter para <strong>confirmada</strong> subscritores apenas. Subscritores que não tenham confirmado a sua subscrição não receberão a nossa Newsletter." );

define( "LM_NAME_TAG_USAGE", "Pode usar o Tag<strong>[NAME]</strong> no conteúdo da Newsletter para enviar Newsletters personalizadas. <br/>Quando enviar a Newsletter, [NAME] será substituido pelo nome do subscritor." );

define( "LM_USERS_TO_SUBSCRIBERS", "Make Users to subscribers" );
define( "LM_ASSIGN_USERS", "Subscrever Utilizadores" );
?>
