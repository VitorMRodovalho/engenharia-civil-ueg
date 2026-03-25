<?php
//
// Copyright (C) 2003 Jan de Graaff
// All rights reserved.
//
// This program uses parts of the original Simpleboard Application
// 0.7.0b written by Josh Levine; http://www.joshlevine.net
//
// This source file is part of the SimpleBoard Component, a Mambo 4.5
// custom Component By Jan de Graaff - http://tsmf.jigsnet.com
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//new in 1.1 Stable
DEFINE('_GEN_FORUM_JUMP','Pesquisa rápida');
DEFINE('_COM_A_FORUM_JUMP','Autorizar pesquisa rápida');
DEFINE('_COM_A_FORUM_JUMP_DESC','Se você seleccionar &quot;Sim&quot; a selecção irá mostrar nas páginas do fórum um menu que permite rápido acesso a outro fórum ou categoria.');
//new in 1.1 RC1
DEFINE('_GEN_RULES','Regras');
DEFINE('_COM_A_RULESPAGE','Autorizar regras da página');
DEFINE('_COM_A_RULESPAGE_DESC','Se você seleccionar &quot;Sim&quot; um link no cabeçalho do menu irá mostrar as suas regras da página. Essa página poderá ser usada como regra do seu fórum e etc. Você pode alterar os conteúdos desse arquivo para abrir a sua página  rules.php no /mambo_root/components/com_simpleboard. <em>Terá sempre um backup desse seu arquivo pois irá re-escrever quando atualizar!</em>');
DEFINE('_MOVED_TOPIC','MOVIDO:');
DEFINE('_COM_A_PDF','Autorizar criação de PDF');
DEFINE('_COM_A_PDF_DESC','Sete &quot;Sim&quot; se você gostaria que os utilizadores pudessem fazer download de um simples documento PDF contendo o conteúdo desse tópico.<br />É um <u>simples</u> documento PDF; sem marcações, com layout simples e que contém apenas todo o texto desse tópico... <br /><strong>Nota:</strong><ul><li>requer Mambo 4.5.2 ou superior!</li><li>Não foi testado com 4.5.1 mal não há mal em tentar.. Mas não é suportado no 4.5.1 apesar de tudo.</li><li>Não funciona no 4.5.0 (testado)!</li></ul>');
DEFINE('_GEN_PDFA','Clique neste botão para criar um documento PDF desse tópico (abrirá uma nova janela).');
DEFINE('_GEN_PDF', 'PDF');
//new in 1.0.4 stable
DEFINE('_GEN_SUBSCRIPTIONS','Subscrição');
DEFINE('_VIEW_PROFILE','Clique para ver o perfil deste utilizador');
DEFINE('_VIEW_ADDBUDDY','Clique aqui para adicionar este utilizador à sua lista de contactos');
DEFINE('_POST_SUCCESS_POSTED','Sua mensagem foi gravada com sucesso');
DEFINE('_POST_SUCCESS_VIEW','[ Voltar à mensagem ]');
DEFINE('_POST_SUCCESS_FORUM','[ Voltar ao fórum ]');
//DEFINE('_POST_SUCCESS_HERE','here');
DEFINE('_POST_SUCCESS_THREAD_VIEW','Clique para voltar ao tópico');
DEFINE('_RANK_ADMINISTRATOR','Administrador');
DEFINE('_RANK_MODERATOR','Moderador');
DEFINE('_SHOW_LASTVISIT','Desde a última visita');
DEFINE('_COM_A_BADWORDS_TITLE','Filtro de palavras impróprias');
DEFINE('_COM_A_BADWORDS','Usar filtro de palavras impróprias');
DEFINE('_COM_A_BADWORDS_DESC','Selecionar para &quot;Simquot; se você deseja filtrar tópicos que contenham as palavras que definiu na configuração do Componente Badwords. Para usar esta função você deve ter Componente Badwords instalado!');
DEFINE('_COM_A_BADWORDS_NOTICE','* Esta mensagem está sendo censurada porque contém uma ou mais palavras configuradas pelo administrador como impróprias *');
DEFINE('_COM_A_PMS_TITLE','myPMS II & myPMS Professional');
DEFINE('_COM_A_COMBUILDER_TITLE','Community Builder (SOMENTE VERSÃO 1.0 beta4 E POSTERIORES!)');
DEFINE('_COM_A_MOSBOT_TITLE','DIscussbot');
DEFINE('_COM_A_COMBUILDER_PROFILE','Criar um perfil do fórum para Community Builder');
DEFINE('_COM_A_COMBUILDER_PROFILE_DESC','Clique no link para criar campos do fórum necessários no perfil do utilizador do Community Builder. Após eles estarem criados você estará liberado para movê-los quando você desejar usando o administrador do Community Builder, apenas não se esqueça de renomear os nomes e mensagens deles. Se você deletá-los da adminitração do Community Builder, você pode criá-los novamente usando este link, contudo não clique no link várias vezes!');
DEFINE('_COM_A_COMBUILDER_PROFILE_CLICK','> Clique aqui <');
DEFINE('_COM_A_COMBUILDER','Perfil dos utilizadores do Community Builder');
DEFINE('_COM_A_COMBUILDER_DESC','Seleccionar para &quot;Sim&quot; você irá activar a integração do componente Community Builder component (www.mambojoe.com). Todas as funções do perfil de usuário do Simpleboard serão administradas pelo Community Builder e link do perfil nos fóruns levará você para o perfil do usuário do Community Builder. Esta configuração sobreporá a configuração do perfil do myPMS Pro se ambos estiverm setados para &quot;Sim&quot;. Também, tenha certeza que você deseja aplicar as mudanças necesárias ao Banco de Dados do Community Builder usando a opção abaixo.');
DEFINE('_COM_A_AVATAR_SRC','Usar figura do avatar do');
DEFINE('_COM_A_AVATAR_SRC_DESC','Se você tem myPMS Professional ou componente Community Builder instalado, você pode configurar o simpleboard para usar a figura de avatar do utilizador do myPMS Pro ou do perfil do utilizador do Community Builder. NOTA: Para Community Builder você precisa ter a opção thumbnail seleccionada como on pois o fórum usa as figuras thumbnail, não as originais.');
DEFINE('_COM_A_KARMA','Mostrar Karma');
DEFINE('_COM_A_KARMA_DESC','Mudar para &quot;Sim&quot; para mostrar Karma do utilizador e botões relacionados (aumentar / diminuir) se as estatísticas do usuário estiverem ativadas.');
DEFINE('_COM_A_DISEMOTICONS','Desactivar emoticons');
DEFINE('_COM_A_DISEMOTICONS_DESC','Coloque &quot;Sim&quot; para desactivar completamente os emoticons.');
DEFINE('_COM_C_SBCONFIG','Configuração<br />do Simpleboard');
DEFINE('_COM_C_SBCONFIGDESC','Configurar todas as funções do Simpleboard');
DEFINE('_COM_C_FORUM','Administração<br />do Fórum');
DEFINE('_COM_C_FORUMDESC','Adicionar categorias/fóruns e configurar');
DEFINE('_COM_C_USER','Administração<br />dos utilizadores');
DEFINE('_COM_C_USERDESC','Utilizador básico e administração do perfil de utilizador');
DEFINE('_COM_C_FILES','Listar<br />Arquivos enviados');
DEFINE('_COM_C_FILESDESC','Aceda e administre arquivos enviados');
DEFINE('_COM_C_IMAGES','Listas<br />Imagens enviadas');
DEFINE('_COM_C_IMAGESDESC','Aceda e administre imagens enviadas');
DEFINE('_COM_C_CSS','Editar<br />Arquivo CSS');
DEFINE('_COM_C_CSSDESC','Alterar o visual do Simpleboard');
DEFINE('_COM_C_SUPPORT','Site de<br />Suporte');
DEFINE('_COM_C_SUPPORTDESC','Entre no site TSMF (nova janela)');
DEFINE('_COM_C_PRUNETAB','Limpar (prune)<br />Fórums');
DEFINE('_COM_C_PRUNETABDESC','Remover tópicos antigos (configurável)');
DEFINE('_COM_C_PRUNEUSERS','Limpar (prune)<br />Utilizadores');
DEFINE('_COM_C_PRUNEUSERSDESC','Sincronizar tabela de utilizadores do Simpleboard com a tabela de utilizadores do Joomla');
DEFINE('_COM_C_LOADSAMPLE','Carregar<br />Dados de exemplo');
DEFINE('_COM_C_LOADSAMPLEDESC','Para um fácil começo: carregue os dados de exemplo no banco de dados do Simpleboard vazio');
DEFINE('_COM_C_UPGRADE','Upgrade do <br />Banco de dados para a<br />última versão: ');
DEFINE('_COM_C_UPGRADEDESC','Actualizar banco de dados para a última versão após um upgrade');
DEFINE('_COM_C_BACK','Voltar ao painel do Simpleboard');
DEFINE('_COM_C_BOARDSTATS','Estatísticas do fórum');
DEFINE('_SHOW_LAST_SINCE','Tópicos activos desde a última visita em:');
DEFINE('_POST_SUCCESS_REQUEST2','O seu pedido foi processado');
DEFINE('_POST_NO_PUBACCESS3','Clique aqui para registar.');
//==================================================================================================
//Changed in 1.0.4
//please update your local language file with these changes as well
DEFINE('_POST_SUCCESS_DELETE','A mensagem foi apagada com sucesso.');
DEFINE('_POST_SUCCESS_EDIT','A mensagem foi editada com sucesso.');
DEFINE('_POST_SUCCESS_MOVE','O tópico foi movido com sucesso.');
DEFINE('_POST_SUCCESS_POST','mensagem foi gravada com sucesso.');
DEFINE('_POST_SUCCESS_SUBSCRIBE','Inscrição processada.');
//==================================================================================================
//new in 1.0.3 stable
//Karma
DEFINE('_KARMA','Karma');
DEFINE('_KARMA_SMITE','Castigar');
DEFINE('_KARMA_APPLAUD','Aplaudir');
DEFINE('_KARMA_BACK','Para voltar ao tópico,');
DEFINE('_KARMA_WAIT','Apenas pode modificar o Karma de uma pessoa qualquer a cada 6 horas.<br />Por favor, aguarde esse tempo antes de modificar o Karma de alguém novamente.');
DEFINE('_KARMA_SELF_DECREASE','Por favor, não tente diminuir o seu próprio Karma!');
DEFINE('_KARMA_SELF_INCREASE','O seu Karma foi diminuido por causa da tentativa de aumentá-lo você mesmo');
DEFINE('_KARMA_DECREASED','Karma do utilizador diminuido. Se não for redireccionado ao tópico em alguns momentos,');
DEFINE('_KARMA_INCREASED','Karma do utilizador aumentado. Se não for redirecionado ao tópico em alguns momentos,');
DEFINE('_COM_A_TEMPLATE','Tema');
DEFINE('_COM_A_TEMPLATE_DESC','Escolha um tema para utilizar.');
DEFINE('_PREVIEW_CLOSE','Fechar esta janela');
//==========================================
//new in 1.0 Stable
DEFINE('_GEN_PATHWAY','Índice :: ');
DEFINE('_COM_A_POSTSTATSBAR','Use a barra de estatística');
DEFINE('_COM_A_POSTSTATSBAR_DESC','Seleccione &quot;Sim&quot; se você deseja numerar as mensagens de um utilizador, gráficamente, através de uma barra de estatísticas.');
DEFINE('_COM_A_POSTSTATSCOLOR','Número da cor para a barra');
DEFINE('_COM_A_POSTSTATSCOLOR_DESC','Informe o número da cor que você deseja usar na barra de estatísticas');
DEFINE('_LATEST_REDIRECT','Simpleboard necessita (re)estabelecer seus privilégios de acesso. Pode criar uma lista própria dos últimos tópicos.\nNão se preocupe, isto é normal após mais que 30 minutos inactivo ou após efectuar o login novamente.\nPor favor efectue nova pesquisa.');
DEFINE('_PREVIEW','Pré-visualizar');
DEFINE('_SMILE_COLOUR','Cores');
DEFINE('_SMILE_SIZE','Tamanho');
DEFINE('_COLOUR_DEFAULT','Padrão');
DEFINE('_COLOUR_RED','Vermelho');
DEFINE('_COLOUR_PURPLE','Purple');
DEFINE('_COLOUR_BLUE','Azul');
DEFINE('_COLOUR_GREEN','Verde');
DEFINE('_COLOUR_YELLOW','Amarelo');
DEFINE('_COLOUR_ORANGE','Laranja');
DEFINE('_COLOUR_DARKBLUE','Azul Marinho');
DEFINE('_COLOUR_BROWN','Castanho');
DEFINE('_COLOUR_GOLD','Amarelo Ouro');
DEFINE('_COLOUR_SILVER','Prata');
DEFINE('_SIZE_NORMAL','Normal');
DEFINE('_SIZE_SMALL','Pequeno');
DEFINE('_SIZE_VSMALL','Muito Pequeno');
DEFINE('_SIZE_BIG','Grande');
DEFINE('_SIZE_VBIG','Muito Grande');
DEFINE('_IMAGE_SELECT_FILE','Seleccione uma imagem para anexar');
DEFINE('_FILE_SELECT_FILE','Seleccione um arquivo para anexar');
DEFINE('_FILE_NOT_UPLOADED','Não foi possivel fazer upload do arquivo. Tente enviar a mensagem novamente ou editá-la');
DEFINE('_IMAGE_NOT_UPLOADED','Não foi possivel fazer upload da imagem. Tente enviar a mensagem novamente ou editá-la');
DEFINE('_BBCODE_IMGPH','Insira [img] na mensagem para anexar a imagem');
DEFINE('_BBCODE_FILEPH','Insira [file] na mensagem para anexar o Arquivo');
DEFINE('_POST_ATTACH_IMAGE','[img]');
DEFINE('_POST_ATTACH_FILE','[file]');
DEFINE('_USER_UNSUBSCRIBE_ALL','Seleccione aqui para cancelar a inscrição de todos os tópicos (incluindo os invisíveis para propósitos de detecção de erros )');
DEFINE('_LINK_JS_REMOVED','<em>Links que possuem JavaScript serão removidos automaticamente</em>');
//==========================================
//new in 1.0 RC4
DEFINE('_COM_A_LOOKS','Exibição e procura');
DEFINE('_COM_A_USERS','Sobre o Utilizador');
DEFINE('_COM_A_LENGTHS','Configurações de comprimentos');
DEFINE('_COM_A_SUBJECTLENGTH','Comprimento Max. do assunto');
DEFINE('_COM_A_SUBJECTLENGTH_DESC','Comprimento máximo do assunto por linha. O número máximo aceite pelo banco de dados é 255 caracteres. Se o teu site está configurado para usar caracteres Multi-byte então coloque UBICODE, Unicode, UTF-8, non-ISO-8599-x use esta fórmula:<br /><tt>round_down(255/(maximum character set byte size per character))</tt><br /> Exemplo para UTF-8, para cada número max. de caracteres por caracter é 4 bytes: 255/4=63.');
DEFINE('_LATEST_THREADFORUM','Tópico / Fórum');
DEFINE('_LATEST_NUMBER','Nova mensagem');
DEFINE('_COM_A_SHOWNEW','Exibir nova mensagem');
DEFINE('_COM_A_SHOWNEW_DESC','Se seleccionar &quot;Sim&quot; então o Simpleboard mostra para o utulizador um indicador quais os fóruns que contêm mensagens novas e que mensagens foram criadas após a sua última visita.');
DEFINE('_COM_A_NEWCHAR','Indicador de &quot;novo&quot;');
DEFINE('_COM_A_NEWCHAR_DESC','Defina aqui o que deve ser usado para indicar mensagens novas (Use &quot;!&quot; ou &quot;Novo!&quot;)');
DEFINE('_LATEST_AUTHOR','Mais recente mensagem deste utilizador');
DEFINE('_GEN_FORUM_NEWPOST','Indica se há novas postagens neste fórum desde sua última visita');
DEFINE('_GEN_FORUM_NOTNEW','Nenhum mensagem nova desde a sua última visita');
DEFINE('_GEN_NEWPOST','Indica se existem novas mensagens neste fórum desde a sua última visita');
DEFINE('_GEN_NOTNEW','Nenhuma mensagem nova desde a sua última visita');
DEFINE('_GEN_UNREAD','Indica se existem novas respostas, não lidas neste tópico desde a sua última visita');
DEFINE('_GEN_NOUNREAD','Nenhuma resposta nova, não lida neste tópico desde a sua última visita');
DEFINE('_GEN_MARK_ALL_FORUMS_READ','Marcar todos os fóruns como lidos');
DEFINE('_GEN_MARK_THIS_FORUM_READ','Marcar este fórum como lido');
DEFINE('_GEN_FORUM_MARKED','Todas as mensagens neste fórum foram marcadas como lida');
DEFINE('_GEN_ALL_MARKED','Todas as mensagens marcadas como lidas');
DEFINE('_IMAGE_UPLOAD','Upload de Imagem');
DEFINE('_IMAGE_DIMENSIONS','O seu arquivo de imagem pode ser no máximo (Largura x Altura - Tamanho)');
DEFINE('_IMAGE_SUBMIT','Enviar nova imagem para Upload');
DEFINE('_IMAGE_ERROR_TYPE','Por favor use somente imagens jpeg, gif or png');
DEFINE('_IMAGE_ERROR_EMPTY','Por favor, seleccione um arquivo antes de enviar');
DEFINE('_IMAGE_ERROR_NAME','O arquivo de imagem tem que conter só caracteres alfanuméricos sem espaço');
DEFINE('_IMAGE_ERROR_SIZE','O tamanho de arquivo de imagem excede o máximo.');
DEFINE('_IMAGE_ERROR_WIDTH','A largura do arquivo de imagem excede o máximo permitido.');
DEFINE('_IMAGE_ERROR_HEIGHT','A altura do  arquivo de imagem excede o máximo permitido.');
DEFINE('_IMAGE_UPLOADED','Foi feito o upload da sua imagem.');
DEFINE('_IMAGE_UPDATED','Foi feito o upload da sua imagem.');
DEFINE('_IMAGE_COPY_PASTE','Copiar/Colar este link dentro da sua mensagem para incluir a imagemm');
DEFINE('_COM_A_IMAGES','Todas as imagens relacionadas');
DEFINE('_COM_A_IMAGE','Imagens');
DEFINE('_COM_A_IMGHEIGHT','Altura máxima da imagem');
DEFINE('_COM_A_IMGWIDTH','Largura máxima da imagem');
DEFINE('_COM_A_IMGSIZE','Tamanho máximo da imagem<br /><em>em kilobytes</em>');
DEFINE('_COM_A_IMAGEUPLOAD','Permitir que o público envie imagens');
DEFINE('_COM_A_IMAGEUPLOAD_DESC','Seleccione &quot;Sim&quot; se você deseja qualquer utilizador (o público) seja capaz de enviar imagens.');
DEFINE('_COM_A_IMAGEREGUPLOAD','Permitir que somente utilizadores registados enviem imagens');
DEFINE('_COM_A_IMAGEREGUPLOAD_DESC','Seleccione &quot;Sim&quot; Se deseja que utilizadores registados e ligados sejam capazes de enviarem imagens.<br /> Nota: (Super)administradores e moderadores podem sempre enviar imagens.');
  //New since preRC4-II:
DEFINE('_IMAGE_INSERT','Clique aqui para inserir uma tag na sua mensagem');
DEFINE('_IMAGE_BUTTON','Upload da imagem');
DEFINE('_IMAGE_ERROR_EXISTS','Uma imagem com o nome indicado já existe. Por favor escolha outro nome para que seja feito o uploading.');
DEFINE('_FILE_BUTTON','Arquivo anexado');
DEFINE('_COM_A_UPLOADS','Uploads');
DEFINE('_FILE_UPLOAD','Upload de arquivo');
DEFINE('_FILE_TYPES','O seu arquivo pode ter no máximo - máx. tamanho');
DEFINE('_FILE_SUBMIT','Enviar um novo arquivo para Upload');
DEFINE('_FILE_ERROR_TYPE','Permitir, somente, upload dos seguintes tipos de arquivos:\n');
DEFINE('_FILE_ERROR_EMPTY','Por favor seleccione um arquivo antes de uploading');
DEFINE('_FILE_ERROR_NAME','O arquivo tem que conter só caracteres alfanuméricos sem espaço.');
DEFINE('_FILE_ERROR_SIZE','O tamanho de arquivo excede o máximo permitido.');
DEFINE('_FILE_ERROR_EXISTS','Um arquivo com o nome especificado já existe. Por favor escolha outro nome antes de fazer a transferência.');
DEFINE('_FILE_UPLOADED','O upload do seu arquivo foi feito com sucesso.');
DEFINE('_FILE_UPDATED','O updated do seu arquivo foi feito com sucesso.');
DEFINE('_FILE_COPY_PASTE','Copie/Cole este link na sua mensagem para incluir o arquivo');
DEFINE('_COM_A_FILES','Todos os arquivos relacionados');
DEFINE('_COM_A_FILE','Arquivos');
DEFINE('_COM_A_FILEALLOWEDTYPES','Tipos de arquivos permitidos');
DEFINE('_COM_A_FILEALLOWEDTYPES_DESC','Especifique aqui quais tipos de arquivo que são permitidos enviar. Para a lista use a vírgula para separar, letras <strong>minúsculas</strong> e sem espaço.<br />Exemplo: zip,txt,exe,htm,html');
DEFINE('_COM_A_FILESIZE','Tamanho máximo. do arquivo<br /><em>em Kilobyte</em>');
DEFINE('_COM_A_FILEUPLOAD','Permitir que o público faça Upload');
DEFINE('_COM_A_FILEUPLOAD_DESC','Seleccione &quot;Sim&quot; se deseja que todos (o público) sejam capazes de fazer upload de arquivos.');
DEFINE('_COM_A_FILEREGUPLOAD','Permitir que somente utilizadores registaados façam Upload');
DEFINE('_COM_A_FILEREGUPLOAD_DESC','Seleccione &quot;Sim&quot; Se deseja que utilizadores registados e ligados sejam capazes de fazerem upload de imagens.<br /> Nota: (Super)administradores e moderadores podem sempre fazer upload de arquivos.');
DEFINE('_FILE_INSERT','Clique aqui para inserir uma tag na sua mensagem');
DEFINE('_SUBMIT_CANCEL','O envio da sua mensagem foi cancelada com sucesso');
DEFINE('_HELP_SUBMIT','Clique aqui para enviar a sua mensagem');
DEFINE('_HELP_PREVIEW','Clique aqui para pré-visualizar a sua mensagem');
DEFINE('_HELP_CANCEL','Clique aqui para cancelar a sua mensagem');
DEFINE('_POST_DELETE_ATT','Se esta opção for seleccionada, todas as imagens e anexos de arquivo de mensagens que deseja apagar serão excluidos como (indicado).');
   //new since preRC4-III
DEFINE('_COM_A_USER_MARKUP','Exibir marcação');
DEFINE('_COM_A_USER_MARKUP_DESC','Seleccione &quot;Sim&quot; se deseja que a mensagem editada seja marcada com um texto indicando que foi editada por um utilizador.');
DEFINE('_EDIT_BY','Mensagem editada por:');
DEFINE('_EDIT_AT','a:');
DEFINE('_UPLOAD_ERROR_GENERAL','Ocorreu um erro ao tentar fazer uploading do seu avatar. Por favor tente novamente ou notifique o administrador do sistema');
DEFINE('_COM_A_IMGB_IMG_BROWSE','Browser de imagens enviadas');
DEFINE('_COM_A_IMGB_FILE_BROWSE','Browser de arquivos enviados');
DEFINE('_COM_A_IMGB_TOTAL_IMG','Número de imagens enviadas');
DEFINE('_COM_A_IMGB_TOTAL_FILES','Número de arquivos enviados');
DEFINE('_COM_A_IMGB_ENLARGE','Clique na imagem para ver o seu tamanho real');
DEFINE('_COM_A_IMGB_DOWNLOAD','Clique no arquivo para fazer o download');
DEFINE('_COM_A_IMGB_DUMMY_DESC','A Opção &quot;Substitua com a imagem espelho&quot; irá trocar a imagem seleccionada por uma imagem espelho.<br /> Isto permite-lhe remover o arquivo actual sem interromper a mensagem.<br /><small><em>Por favor, às vezes é necessário actualizar o navegador para ver a substituição pela imagem espelho.</em></small>');
DEFINE('_COM_A_IMGB_DUMMY','Imagem espelho actual');
DEFINE('_COM_A_IMGB_REPLACE','Substitua com a imagem espelho');
DEFINE('_COM_A_IMGB_REMOVE','Remover completamente');
DEFINE('_COM_A_IMGB_NAME','Nome');
DEFINE('_COM_A_IMGB_SIZE','Tamanho');
DEFINE('_COM_A_IMGB_DIMS','Dimensões');
DEFINE('_COM_A_IMGB_CONFIRM','Você está absolutamente seguro que deseja apagar este arquivo? \n Apagando um arquivo, teremos uma mensagem sem referência...');
DEFINE('_COM_A_IMGB_VIEW','Abrir mensagem (para editar)');
DEFINE('_COM_A_IMGB_NO_POST','Mensagem sem referência!');
DEFINE('_USER_CHANGE_VIEW','As alterações ficarão gravadas na próxima vez que visitar os fóruns.<br /> Se deseja mudar para &quot;Mid-Flight&quot; pode usar as opções do menu.');
DEFINE('_COM_A_MOSBOT','Habilitar o Mosbot');
DEFINE('_COM_A_MOSBOT_DESC',
 'O Mosbot Discuss possibilita seus utilizadores discutir itens de conteúdo nos fóruns. O título do conteúdo é usado como título do tópico.'
.'<br />Se um tópico não existe é criado um novo. Se o tópico já existe, artigo do utilizador é exibido e ele(a) pode repetir.'
.'<br /><strong>Necessita de fazer o download e instalar o mosbot separadamente.</strong>'
.'<br />verifique o <a href="http://tsmf.jigsnet.com">Site TSMF</a> para melhores informações.'
.'<br />Quando instalado necessitará adicionar a seguinte linha do mosbot ao seu conteúdo:'
.'<br />{mos_sb_discuss:<em>catid</em>}'
.'<br />O <em>catid</em> é a categoria na qual o item de conteúdo pode ser comentado. Para encontrar o catid apropriado, veja nos foruns '
.'e verifique o id da categoria a partir das URLs de barra de status do seu browser.'
.'<br />Exemplo: se deseja um artigo discutido no fórum com catid 26, o mosbot deverá ser: {mos_sb_discuss:26}'
.'<br />Isto parece um pouco difícil, mas ele possibilita-o de ter cada um dos itens de conteúdo a serem comentados no fórum correspondente.'
);
DEFINE('_MOSBOT_DISCUSS_A','Comente este artigo no fórum. (');
DEFINE('_MOSBOT_DISCUSS_B',' mensagem)');
DEFINE('_COM_A_BOT_REFERENCE','Exibir quadro de referência do mosbot');
DEFINE('_POST_DISCUSS','Esta linha debate o conteúdo do artigo');
DEFINE('_FORUM_USERSEARCH','Procurar mensagens colocadas por');
DEFINE('_RESULTS_USERNAME','localizar utilizador por');
DEFINE('_SEARCH_ON_USER','Pelo nome do utilizador');
DEFINE('_SEARCH_OTHER_OPTIONS','Outra opções de pesquisa');
DEFINE('_COM_A_RSS','Habilitar RSS');
DEFINE('_COM_A_RSS_DESC','O RSS permite aos utilizadores receberem as mensagens mais recentes nos seus desktops através de leitor de RSS (veja <a href="http://www.rssreader.com" target="_blank">rssreader.com</a> por exemplo.');
DEFINE('_LISTCAT_RSS','Receba as mensagens do nosso fórum no seu desktop');
DEFINE('_SEARCH_REDIRECT','Simpleboard necessita (re)estabelecer o seu privilégio de acesso antes de executar a pesquisa.\nNão se preocupe, isso ocorre normalmente após 30 minutos de inactividade.\nPor favor pesquise novamente.');

//====================
//admin.forum.html.php
DEFINE('_COM_A_CONFIG','Configuração');
DEFINE('_COM_A_CONFIG_DESC','Alterar a configuração');
DEFINE('_COM_A_VERSION','A sua versão é ');
DEFINE('_COM_A_DISPLAY','Exibir #');
DEFINE('_COM_A_SB_BY','Um componente customizado para MOS4.5 por');
DEFINE('_COM_A_CURRENT_SETTINGS','Posição actual');
DEFINE('_COM_A_EXPLANATION','Explicação');
DEFINE('_COM_A_BOARD_TITLE','Título do Fórum');
DEFINE('_COM_A_BOARD_TITLE_DESC','Descrição do fórum');
DEFINE('_COM_A_VIEW_TYPE','Visão padrão');
DEFINE('_COM_A_VIEW_TYPE_DESC','Escolha entre uma visão ramificada ou alinhada como padrão');
DEFINE('_COM_A_THREADS','Linhas por página');
DEFINE('_COM_A_THREADS_DESC','Número de partes exibidas por página');
DEFINE('_COM_A_HEADER','Cabeçalho');
DEFINE('_COM_A_HEADER_DESC','Cabeçalho, se desejar...');
DEFINE('_COM_A_REGISTERED_ONLY','Somente utilizadores registados');
DEFINE('_COM_A_REG_ONLY_DESC','Seleccione &quot;Sim&quot; para permitir que somente utilizadores registados utilizem o fórum (ver & postar). Seleccione &quot;Não&quot; para permitir que utilizadores não registados utilizem o fórum');
DEFINE('_COM_A_PUBWRITE','Leitura / Escrita pública');
DEFINE('_COM_A_PUBWRITE_DESC','Seleccione &quot;Sim&quot; para permitir que utilizadores não registados escrevam no fórum. Seleccione &quot;Não&quot; para permitir que os visitantes vejam o fórum, mas somente os registados possam escrever');
DEFINE('_COM_A_USER_EDIT','Utilizadores podem editar?');
DEFINE('_COM_A_USER_EDIT_DESC','Seleccione &quot;Sim&quot; para permitir que os utilizadores registados editem as suas mensagens.');
DEFINE('_COM_A_MESSAGE','Para salvar troque os valores acima, pressione &quot;Salvar&quot; no botão acima.');
DEFINE('_COM_A_HISTORY','Exibir histórico');
DEFINE('_COM_A_HISTORY_DESC','Seleccione &quot;Sim&quot; se deseja exibir um histórico do tópico quando alguem clicar em responder/citar');
DEFINE('_COM_A_SUBSCRIPTIONS','Permitir observar');
DEFINE('_COM_A_SUBSCRIPTIONS_DESC','Seleccione &quot;Sim&quot; se deseja que os utilizadores registados possam observar um tópico e recebam notificações por email quando ocorrerem novas mensagens');
DEFINE('_COM_A_HISTLIM','Limite de histórico');
DEFINE('_COM_A_HISTLIM_DESC','Limite de mensagens exibidas por tópico');
DEFINE('_COM_A_FLOOD','Protecção contra flood');
DEFINE('_COM_A_FLOOD_DESC','Quantos segundos um usuário tem que esperar entre duas mensagens sucessivas. Seleccione 0 (zero) para não ter nenhuma protecção contra food. NOTA: Protecção contra flood <em>pode</em> prejudicar a performance.');
DEFINE('_COM_A_MODERATION','E-mail moderadores');
DEFINE('_COM_A_MODERATION_DESC','Seleccione &quot;Sim&quot; se deseja notificar os moderadores por e-mail sempre que existam novas mensagens. Nota: Embora todo (super)administrador possua privilégios como moderador ele pode nomear outros utilizadores como moderador do fórum que também receberão e-mails!');
DEFINE('_COM_A_SHOWMAIL','Mostrar e-mail');
DEFINE('_COM_A_SHOWMAIL_DESC','Seleccione &quot;Não&quot; se não deseja exibir o endereço de e-mail do utilizador; nem mesmo para utilizadores registados.');
DEFINE('_COM_A_AVATAR','Permitir avatares');
DEFINE('_COM_A_AVATAR_DESC','Seleccione &quot;Sim&quot; se deseja que os utilizadores registados usem avatar (alterando o perfil)');
DEFINE('_COM_A_AVHEIGHT','Altura máxima do avatar');
DEFINE('_COM_A_AVWIDTH','Largura máxima do avatar');
DEFINE('_COM_A_AVSIZE','Tamanho máximo do avatar<br /><em>em kilobytes</em>');
DEFINE('_COM_A_USERSTATS','Informação do utilizador');
DEFINE('_COM_A_USERSTATS_DESC','Seleccione &quot;Sim&quot; para mostrar estatísticas de utilizadores, tais como o número de mensagens colocadas, típo de utilizador (Administrador, Moderador, Utilizador, etc.).');
DEFINE('_COM_A_AVATARUPLOAD','Permitir upload de avatar');
DEFINE('_COM_A_AVATARUPLOAD_DESC','Seleccione &quot;Sim&quot; se deseja que os utilizadores registados possam fazer upload do avatar.');
DEFINE('_COM_A_AVATARGALLERY','Utilizar galeria de avatares');
DEFINE('_COM_A_AVATARGALLERY_DESC','Seleccione &quot;Sim&quot; se quer que os utilizadores registados possam escolher um avatar de uma outra galeria (components/com_simpleboard/avatars/gallery).');
DEFINE('_COM_A_RANKING','Exibir ranking');
DEFINE('_COM_A_RANKING_DESC','Seleccione &quot;Sim&quot; para permitir que os utilizadores registados saibam o total de mensagens colocadas por eles.<br /><strong>Deve habilitar também as estatísticas de utilizadores na aba avançada para permitir a exibição.');
DEFINE('_COM_A_RANKINGIMAGES','Imagens do ranking');
DEFINE('_COM_A_RANKINGIMAGES_DESC','Seleccione &quot;Sim&quot; se deseja exibir o ranking dos utilizadores registados com uma imagem (de components/com_simpleboard/ranks). Alterando isso exibirá o ranking com um texto. Veja a documentação em www.tsmf-mambo.com para obter mais informações sobre o ranking com imagens');
DEFINE('_COM_A_RANK1','Ranking 1');
DEFINE('_COM_A_RANK1TXT','Ranking 1 texto');
DEFINE('_COM_A_RANK2','Ranking 2');
DEFINE('_COM_A_RANK2TXT','Ranking 2 texto');
DEFINE('_COM_A_RANK3','Ranking 3');
DEFINE('_COM_A_RANK3TXT','Ranking 3 texto');
DEFINE('_COM_A_RANK4','Ranking 4');
DEFINE('_COM_A_RANK4TXT','Ranking 4 texto');
DEFINE('_COM_A_RANK5','Ranking 5');
DEFINE('_COM_A_RANK5TXT','Ranking 5 texto');
DEFINE('_COM_A_RANK6','Ranking 6');
DEFINE('_COM_A_RANK6TXT','Ranking 6 texto');
DEFINE('_COM_A_RANK','Ranking');
DEFINE('_COM_A_RANK_NAME','Nome do Ranking');
DEFINE('_COM_A_RANK_LIMIT','Limite do Ranking');
//email and stuff
$_COM_A_NOTIFICATION ="Notificação de nova mensagem de ";
$_COM_A_NOTIFICATION1="Uma nova mensagem foi colocada no fórum. Solicitou que fosse notificado no ";
$_COM_A_NOTIFICATION2="Pode administrar as suas subscrições em 'Meu Perfil' no link na página inicial do fórum. É necessário que esteja ligado. No seu perfil é possível concelar a sua subscrição aos tópicos.";
$_COM_A_NOTIFICATION3="Por favor, não responda a esta notificação, pois foi gerada automaticamente pelo sistema.";
$_COM_A_NOT_MOD1="Uma nova mensagem foi colocada no fórum onde foi nomeado(a) como moderador no";
$_COM_A_NOT_MOD2="Por favor, participe após fazer o login no site.";

DEFINE('_COM_A_NO','Não');
DEFINE('_COM_A_YES','Sim');
DEFINE('_COM_A_FLAT','Alinhada');
DEFINE('_COM_A_THREADED','Ramificada');
DEFINE('_COM_A_MESSAGES','Mensagens por página');
DEFINE('_COM_A_MESSAGES_DESC','Número de mensagens exibidas por página');
   //admin; changes from 0.9 to 0.9.1
DEFINE('_COM_A_USERNAME','Nome do utilizador');
DEFINE('_COM_A_USERNAME_DESC','Seleccione &quot;Sim&quot; se deseja que o nome do utilizador (usado no login) possa ser usado no lugar do nome real');
DEFINE('_COM_A_CHANGENAME','Permitir troca de nome');
DEFINE('_COM_A_CHANGENAME_DESC','Seleccione &quot;Sim&quot; se deseja que os utilizadores registados possam mudar os seus nomes. Se seleccionar &quot;Não&quot; então os utilizadores registados não poderão editar os seus nomes');
   //admin; changes 0.9.1 to 0.9.2
DEFINE('_COM_A_BOARD_OFFLINE','Fórum offline');
DEFINE('_COM_A_BOARD_OFFLINE_DESC','Seleccione &quot;Sim&quot; se deseja deixar o fórum offline. O fórum só voltará a ser utilizado  com a intervenção do (super)administrador.');
DEFINE('_COM_A_BOARD_OFFLINE_MES','Mensagem para o fórum offline');
DEFINE('_COM_A_PRUNE','Limpar fóruns');
DEFINE('_COM_A_PRUNE_NAME','Fórum para exclusão:');
DEFINE('_COM_A_PRUNE_DESC','A função prune fóruns permite limpar os tópicos que não têm recebido novas mensagens por um período específico de tempo. Isto não remove tópicos fixos ou bloqueados; estes precisam ser removidos manualmente.');
DEFINE('_COM_A_PRUNE_NOPOSTS','Excluir tópicos que não foram respondidos num período de: ');
DEFINE('_COM_A_PRUNE_DAYS','dias');
DEFINE('_COM_A_PRUNE_USERS','Limpar utilizadores');
DEFINE('_COM_A_PRUNE_USERS_DESC','Esta função permite limpar da sua lista de utilizadores do Simpleboard contra a lista de usuários do Joomla. Apagará todos os perfis de utilizadores do Simpleboard que foram apagados do Joomla.<br />Quando tiver certeza que
deseja continuar, clique em &quot;Start Prunig&quot; na barra no menu acima.');

//general
DEFINE('_GEN_ACTION','Acção');
DEFINE('_GEN_AUTHOR','Autor');
DEFINE('_GEN_BY','por');
DEFINE('_GEN_CANCEL','cancelar');
DEFINE('_GEN_CATEGORY','Categoria');
DEFINE('_GEN_CONTINUE','Enviar');
DEFINE('_GEN_DATE','Data');
DEFINE('_GEN_DELETE','apagar');
DEFINE('_GEN_EDIT','editar');
DEFINE('_GEN_EDIT_MESSAGE','Editar mensagem');
DEFINE('_GEN_EMAIL','e-mail');
DEFINE('_GEN_EMOTICONS','emoticons');
DEFINE('_GEN_FLAT','Alinhado');
DEFINE('_GEN_FLAT_VIEW','Visão alinhada');
DEFINE('_GEN_FORUMLIST','Índice do fórum');
DEFINE('_GEN_FORUM','Fórum');
DEFINE('_GEN_HELP','Ajuda');
DEFINE('_GEN_HITS','Exibições ');
DEFINE('_GEN_LAST_POST','Última mensagem');
DEFINE('_GEN_LATEST_POSTS','Mensagens recentes');
DEFINE('_GEN_LOCK','bloquear');
DEFINE('_GEN_UNLOCK','desbloquear');
DEFINE('_GEN_LOCKED_FORUM','Este fórum está bloqueado. Não é possível colocar novas mensagens.');
DEFINE('_GEN_LOCKED_TOPIC','Este tópico está bloqueado. Não é possível colocar novas mensagens.');
DEFINE('_GEN_MESSAGE','Mensagem');
DEFINE('_GEN_MODERATED','Este fórum está sendo moderado. Mensagens novas serão revistas antes de serem publicadas.');
DEFINE('_GEN_MODERATORS','Moderadores');
DEFINE('_GEN_MOVE','mover');
DEFINE('_GEN_MY_PROFILE','Meu Perfil');
DEFINE('_GEN_NAME','Nome');
DEFINE('_GEN_NEW','Novo!');
DEFINE('_GEN_NO_NEW','Não há mensagens novas');
DEFINE('_GEN_NO_ACCESS','Lamento, não tem acesso a esta página.');
DEFINE('_GEN_POSTS','Seleccione um perfil');
DEFINE('_GEN_POSTS_DISPLAY','Mensagens');
DEFINE('_GEN_POST_A_PROFILE','Coloque um perfil');
DEFINE('_GEN_POST_NEW_TOPIC','[Escrever novo tópico]');
DEFINE('_GEN_POST_REPLY','Responder mensagem');
DEFINE('_GEN_MYPROFILE','Meu Perfil');
DEFINE('_GEN_QUOTE','citar');
DEFINE('_GEN_REPLY','responder');
DEFINE('_GEN_REPLIES','Respostas ');
DEFINE('_GEN_THREADED','Ramificada');
DEFINE('_GEN_THREADED_VIEW','Visão ramificada');
DEFINE('_GEN_SIGNATURE','Assinatura');
DEFINE('_GEN_STARTED','Início em');
DEFINE('_GEN_ISSTICKY','Significa que este tópico é fixo.');
DEFINE('_GEN_STICKY','fixar');
DEFINE('_GEN_UNSTICKY','soltar');
DEFINE('_GEN_SUBJECT','Assunto');
DEFINE('_GEN_SUBMIT','Enviar');
DEFINE('_GEN_TODAYS_POSTS','hoje');
DEFINE('_GEN_TOPIC','Tópico');
DEFINE('_GEN_TOPICS','Tópicos');
DEFINE('_GEN_TOPIC_ICON','Ícone do tópico');
DEFINE('_GEN_UNANSWERED','Sem resposta');
DEFINE('_GEN_USAGE','uso');
DEFINE('_GEN_VIEWS','Visualizações');
DEFINE('_GEN_SEARCH_BOX','Pesquisar no fórum');
$_GEN_THREADED_VIEW="Visão ramificada";
$_GEN_FLAT_VIEW    ="Visão Alinhada";

//avatar_upload.php
DEFINE('_UPLOAD_UPLOAD','Upload');
DEFINE('_UPLOAD_DIMENSIONS','A imagem pode ter no máximo (largura x altura - peso)');
DEFINE('_UPLOAD_SUBMIT','Enviar novo avatar para upload');
DEFINE('_UPLOAD_SELECT_FILE','Seleccione arquivo');
DEFINE('_UPLOAD_ERROR_TYPE','Por favor use somente imagens jpeg, gif ou png');
DEFINE('_UPLOAD_ERROR_EMPTY','Por favor seleccione um arquivo antes de fazer o upload');
DEFINE('_UPLOAD_ERROR_NAME','O arquivo de imagem tem que conter apenas carateres alfanuméricos sem espaço.');
DEFINE('_UPLOAD_ERROR_SIZE','O tamanho de arquivo de imagem excede o máximo permitido.');
DEFINE('_UPLOAD_ERROR_WIDTH','A largura da imagem excede o máximo permitido.');
DEFINE('_UPLOAD_ERROR_HEIGHT','A altura de arquivo de imagem excede o máximo permitido.');
DEFINE('_UPLOAD_ERROR_CHOOSE',"Não escolheu nenhum avatar da galeria.");
DEFINE('_UPLOAD_UPLOADED','O seu avatar foi enviado.');
DEFINE('_UPLOAD_GALLERY','Escolha um avatar a partir da galeria:');
DEFINE('_UPLOAD_CHOOSE','Confirmar.');
DEFINE('_UPLOAD_UPDATED','O seu avatar foi aceite');

// listcat.php
DEFINE('_LISTCAT_ADMIN','Um Administrador deve cria-lo primeiro a partir de ');
DEFINE('_LISTCAT_DO','Eles saberão o que fazer ');
DEFINE('_LISTCAT_INFORM','Informe-os e diga-lhes para serem breves!');
DEFINE('_LISTCAT_NO_CATS','Não existe ainda nenhuma categoria definida no fórum.');
DEFINE('_LISTCAT_PANEL','Painel de Administração do joomla OS CMS.');
DEFINE('_LISTCAT_PENDING','mensagem(s) pendente(s)');

// moderation.php
DEFINE('_MODERATION_DELETE_MESSAGE','Corre o risco de apagar a mensagem ');
DEFINE('_MODERATION_DELETE_REPLIES',' Não apagar respostas desta mensagem');
DEFINE('_MODERATION_DELETE_POST',' Apagar todas as respostas desta mensagem');
DEFINE('_MODERATION_DELETE_SUCCESS','Mensagem apagada com sucesso.');
DEFINE('_MODERATION_ERROR_MESSAGE','Ocorreu um erro e a mensagem não foi actualizada. Por favor tente novamente, se este erro persistir entre em contacto com o administrador');
DEFINE('_MODERATION_MESSAGES','Não existem mensagens pendentes neste fórum.');
DEFINE('_MODERATION_UPDATED_A','Mensagem actualizada correctamente.');
DEFINE('_MODERATION_UPDATED_B','Clique aqui');
DEFINE('_MODERATION_UPDATED_C',' Para ver.');

// post.php
DEFINE('_POST_ABOUT_TO_DELETE','Corre o risco de apagar a mensagem');
DEFINE('_POST_ABOUT_DELETE','<strong>NOTAS:</strong><br />
-SE você deletar este tópico (A Mensagem principal na ramificação) todos as mensagens filhas vão ser apagadas também!
Considere apagando o texto e o seu título se os conteúdos deveriam ser removidos..
<br />
 Serão movidas todas mensagens filhas, apagado um grau acima na hierarquia em linha');
DEFINE('_POST_BOARDCODE','boardcode');
DEFINE('_POST_BBCODE_HELP','Passe o rato sobre o botão para obter ajuda.');
DEFINE('_POST_CLICK','clique aqui');
DEFINE('_POST_ERROR','Não foi possivel localizar o username / e-mail. Um erro grave e desconhecido no banco de dados');
DEFINE('_POST_ERROR_EXIT','Sair');
DEFINE('_POST_ERROR_MESSAGE','Erro desconhecido no banco de dados ocorreu e a sua mensagem não foi gravada. Se o problema persistir , por favor, entre em contacto com o administrador.');
DEFINE('_POST_ERROR_MESSAGE_OCCURED','Ocorreu um erro e a mensagem não foi actualizada.  Por favor tente novamente. Se este erro persistir, por favor, entre em contacto com o administrador.');
DEFINE('_POST_ERROR_TOPIC','Ocorreu um erro ao tentar apagar a(s) mensagem(s). Por favor confira o erro abaixo:');
DEFINE('_POST_FORGOT_NAME','Esqueceu-se de incluir o seu nome. Clique no botão voltar do seu browser e tente novamente.');
DEFINE('_POST_FORGOT_SUBJECT','Esqueceu-se de incluir um assunto. Clique no botão voltar de seu browser e tente novamente.');
DEFINE('_POST_FORGOT_MESSAGE','Esqueceu-se de incluir uma mensagem. Clique no botão voltar de seu browser e tente novamente.');
DEFINE('_POST_INVALID','ID inválido de uma mensagem foi solicitado.');
DEFINE('_POST_LOCK_SET','O tópico foi bloqueado.');
DEFINE('_POST_LOCK_NOT_SET','O tópico não pôde ser bloqueado.');
DEFINE('_POST_LOCK_UNSET','O tópico foi desbloqueado.');
DEFINE('_POST_LOCK_NOT_UNSET','O tópico não pode ser desbloqueado.');
DEFINE('_POST_MESSAGE','Colocar nova mensagem em ');
DEFINE('_POST_EDIT_MESSAGE','Editar mensagem em ');
DEFINE('_POST_MOVE_TOPIC','Mover este tópico para o fórum');
DEFINE('_POST_NEW','Colocar uma nova mensagem em: ');
DEFINE('_POST_NO_SUBSCRIBED_TOPIC','A sua assinatura para este tópico não pôde ser processada.');
DEFINE('_POST_NOTIFIED','Seleccione esta opção para ser notificado quando ocorrerem respostas a este tópico');
DEFINE('_POST_POSTED','Será colocado brevemente.');
DEFINE('_POST_STICKY_SET','O tópico está a ser bloqueado.');
DEFINE('_POST_STICKY_NOT_SET','Este tópico não pode ser bloqueado.');
DEFINE('_POST_STICKY_UNSET','O tópico está sendo desbloqueado.');
DEFINE('_POST_STICKY_NOT_UNSET','Este tópico não pode ser desbloqueado.');
DEFINE('_POST_SUBSCRIBE','Assinatura');
DEFINE('_POST_SUBSCRIBED_TOPIC','Assinou correctamente este tópico.');
DEFINE('_POST_SUCCESS','Mensagem foi bem sucedida');
DEFINE('_POST_SUCCESS_DELETE','A mensagem foi apagada com sucesso. Se não for redireccionado automaticamente ao fórum em alguns momentos,, ');
DEFINE('_POST_SUCCESS_EDIT','A mensagem foi editada correctamente. Se não for redirecionado automaticamente em alguns momentos, ');
DEFINE('_POST_SUCCESS_MOVE','O Tópico foi movido com sucesso. Se não for redireccionado automaticamente em alguns momentos,');
DEFINE('_POST_SUCCESS_POST','Mensagem foi gravada com sucesso. Se não for redireccionado automaticamente em alguns momentos, ');
DEFINE('_POST_SUCCESS_SUBSCRIBE','Assinatura processada correctamente. Se não for redireccionado automaticamente em alguns momentos,');
DEFINE('_POST_SUCCES_REVIEW','Mensagem gravada correctamente. Será revista por um moderador antes de ser publicada no fórum.');
DEFINE('_POST_SUCCESS_REQUEST','Pedido processado correctamente. Se não for redireccionado automaticamente de volta ao tópico em alguns momentos,');
DEFINE('_POST_TO_VIEW',' para vê-lo.');
DEFINE('_POST_TOPIC_HISTORY','Histórico do tópico de');
DEFINE('_POST_TOPIC_HISTORY_MAX','Exibindo no máximo as últimas');
DEFINE('_POST_TOPIC_HISTORY_LAST','mensagens  -  <i>(últimos primeiro)</i>');
DEFINE('_POST_TOPIC_NOT_MOVED','O tópico não pôde ser movido. Voltar ao tópico:');
DEFINE('_POST_TOPIC_FLOOD1','O administrador deste fórum habilitou protecção contra flood e decidiu que tem que aguardar ');
DEFINE('_POST_TOPIC_FLOOD2',' segundos entre a mensagem colocada e uma nova.');
DEFINE('_POST_TOPIC_FLOOD3','Por favor clique no botão voltar do seu browser para voltar ao fórum.');
DEFINE('_POST_EMAIL_NEVER','o seu endereço de e-mail não será exibido no site.');
DEFINE('_POST_EMAIL_REGISTERED','o seu endereço de e-mail só estará disponível a utilizadores registados.');
DEFINE('_POST_LOCKED','fechado pelo administrador.');
DEFINE('_POST_NO_NEW','Não são permitidas respostas novas.');
DEFINE('_POST_NO_PUBACCES1','O administrador não permite que utilizadores anónimos escrevam no fórum.');
DEFINE('_POST_NO_PUBACCES2','Apenas utilizadores ligados<br /> estão habilitados a contribuir para este fórum.');

// search.php
DEFINE('_SEARCH_HEADER','Pesquisar fórum ...');

// showcat.php
DEFINE('_SHOWCAT_NO_TOPICS','>> Não existe nenhum tópico neste fórum  <<');
DEFINE('_SHOWCAT_PENDING','mensagem(s) pendente(s)');

// usage.php
DEFINE('_USAGE_BOARDCODE','Boardcode?');
DEFINE('_USAGE_INSTRUCTIONS','instruções do uso do fórum');
DEFINE('_USAGE_MYPROFILE','Meu Perfil');
DEFINE('_USAGE_PREVIOUS','Pré-visualizar a página');
DEFINE('_USAGE_TEXT','Boardcodes são tags especiais que lhe permitirão formatar as suas mensagens. Actualmente, o fórum aceita links, negrito, itálico, sublinhado e "citar" texto.  As tags são usadas como se segue:');
DEFINE('_USAGE_TEXT_BOLD','Negrito');
DEFINE('_USAGE_TEXT_ITALIC','Itálico');
DEFINE('_USAGE_TEXT_QUOTE','Citar');
DEFINE('_USAGE_TEXT_UNDERLINE','Sublinhado');
DEFINE('_USAGE_TEXT_WILL','Reproduzir');

// userprofile.php
DEFINE('_USER_DELETE',' seleccione essa opção para apagar a sua assinatura');
DEFINE('_USER_ERROR_A','Ocorreu um erro ao tentar entra na página que desejava. Por favor informe o administrador qual foi o Link ');
DEFINE('_USER_ERROR_B','clicou e veio para este local. Ela ou ele podem arquivar um relatório de bugs.');
DEFINE('_USER_ERROR_C','Obrigado!');
DEFINE('_USER_ERROR_D','Número de erro para incluir no seu relatório: ');
DEFINE('_USER_GENERAL','Opções gerais de perfil');
DEFINE('_USER_MODERATOR','Você foi nomeado como moderador do fórum');
DEFINE('_USER_MODERATOR_NONE','Nenhum fórum foi encontrado assinado por você');
DEFINE('_USER_MODERATOR_ADMIN','Administradores são moderadores em todos os fóruns.');
DEFINE('_USER_NOSUBSCRIPTIONS','Você não está observando nenhum fórum');
DEFINE('_USER_PREFERED','Tipo de visão preferida');
DEFINE('_USER_PROFILE','Perfil para ');
DEFINE('_USER_PROFILE_NOT_A','Seu perfil pode ');
DEFINE('_USER_PROFILE_NOT_B','não');
DEFINE('_USER_PROFILE_NOT_C',' ser actualizado.');
DEFINE('_USER_PROFILE_UPDATED','Seu perfil está actualizado.');
DEFINE('_USER_RETURN_A','Se não voltar ao seu perfil em alguns momentos ');
DEFINE('_USER_RETURN_B','clique aqui');
DEFINE('_USER_SUBSCRIPTIONS','Suas observações');
DEFINE('_USER_UNSUBSCRIBE','[Cancelar Inscrição] ');
DEFINE('_USER_UNSUBSCRIBE_A','Pode ');
DEFINE('_USER_UNSUBSCRIBE_B','não');
DEFINE('_USER_UNSUBSCRIBE_C',' seja cancelada a inscrição no tópico.');
DEFINE('_USER_UNSUBSCRIBE_YES','Cancelou a inscrição no tópico.');
DEFINE('_USER_DELETEAV','Seleccione esta opção para apagar o seu avatar');
//New 0.9 to 1.0
DEFINE('_USER_ORDER','Ordem das mensagens');
DEFINE('_USER_ORDER_DESC','Antigas primeiro');
DEFINE('_USER_ORDER_ASC','Mais recentes primeiro');

// view.php
DEFINE('_VIEW_DISABLED','utilizadores não registados não podem escrever no fórum.');
DEFINE('_VIEW_LOCKED','Bloqueado por um moderador.');
DEFINE('_VIEW_POSTED','Colocado por');
DEFINE('_VIEW_SUBSCRIBE','[Assinatura para este tópico]');
DEFINE('_MODERATION_INVALID_ID','Um ID inválido referente a esta mensagem foi solicitado.');
DEFINE('_VIEW_NO_POSTS','Não há nenhuma mensagem neste fórum.');
DEFINE('_VIEW_VISITOR','Visitante');
DEFINE('_VIEW_ADMIN','Administrador');
DEFINE('_VIEW_EDITOR','Editor');
DEFINE('_VIEW_USER','Utilizador');
DEFINE('_VIEW_MODERATOR','Moderador');
DEFINE('_VIEW_REPLY','Responder a esta mensagem');
DEFINE('_VIEW_EDIT','Editar esta mensagem');
DEFINE('_VIEW_QUOTE','Citar esta mensagem em nova mensagem');
DEFINE('_VIEW_DELETE','Apagar esta mensagem');
DEFINE('_VIEW_STICKY','Bloquear tópico');
DEFINE('_VIEW_UNSTICKY','Desbloquear este');
DEFINE('_VIEW_LOCK','Bloquear este tópico');
DEFINE('_VIEW_UNLOCK','Desbloquear este tópico');
DEFINE('_VIEW_MOVE','Mover este tópico para um fórum novo');
DEFINE('_VIEW_SUBSCRIBETXT','Participar deste fórum e avisar por e-mail quando tiver mensagens novas.');


//NEW-STRINGS-FOR-TRANSLATING-READY-FOR-SIMPLEBOARD 9.2


DEFINE('_HOME','Início');
DEFINE('_POSTS','Mensagens:');
DEFINE('_TOPIC_NOT_ALLOWED','Mensagem');
DEFINE('_FORUM_NOT_ALLOWED','Fórum');
DEFINE('_FORUM_IS_OFFLINE','O fórum está OFFLINE!');
DEFINE('_PAGE','Página: ');
DEFINE('_NO_POSTS','Nenhuma mensagem');
DEFINE('_CHARS','caracteres no máximo');
DEFINE('_HTML_YES','HTML está inactivo');
DEFINE('_YOUR_AVATAR','<b>Seu avatar</b>');
DEFINE('_NON_SELECTED','Não seleccionado <br />');
DEFINE('_SET_NEW_AVATAR','Seleccionar novo avatar');
DEFINE('_THREAD_UNSUBSCRIBE','[Cancelar Inscrição]');
DEFINE('_SHOW_LAST_POSTS','Tópicos activos nas últimas');
DEFINE('_SHOW_HOURS','horas');
DEFINE('_SHOW_POSTS','Total: ');
DEFINE('_DESCRIPTION_POSTS','As mensagens mais novas nos tópicos activos são exibidas');
DEFINE('_SHOW_4_HOURS','4 Horas');
DEFINE('_SHOW_8_HOURS','8 Horas');
DEFINE('_SHOW_12_HOURS','12 Horas');
DEFINE('_SHOW_24_HOURS','24 Horas');
DEFINE('_SHOW_48_HOURS','48 Horas');
DEFINE('_SHOW_WEEK','Semana');
DEFINE('_POST_NO_PUBACCES1_','Não tem permissão');
DEFINE('_POSTED_AT','Colocado em');
DEFINE('_POST_FORUM','Mensagem / Fórum');
DEFINE('_DATETIME','d/m/Y H:i');
DEFINE('_NO_TIMEFRAME_POSTS','Não há nenhuma mensagem nova no prazo que seleccionou.');
DEFINE('_MESSAGE','Mensagem');
DEFINE('_HTML_NO','HTML esta inactivo');
DEFINE('_NO_SMILIE','não');
DEFINE('_DESCRIPTION_BOLD','[b]Negrito[/b]: Seleccione o texto que deseja colocar em Negrito e em seguida clique aqui');
DEFINE('_DESCRIPTION_ITALIC','[i]Itálico[/i]: Seleccione o texto que deseja colocar em Itálico e em seguida clique aqui');
DEFINE('_DESCRIPTION_U','[u]Sublinhado[/u]: Seleccione o texto que deseja colocar em Sublinhado e em seguida clique aqui');
DEFINE('_DESCRIPTION_QUOTE','[quote] texto [/quote]: Seleccione o texto que deseja colocar fazer uma Citação e em seguida clique aqui');
DEFINE('_DESCRIPTION_URL','[url=] Nome do Link [/url]: Seleccione o Link Sem http:// e clique aqui para criar um Link. Depois substitua o nome do Link ');
DEFINE('_DESCRIPTION_CODE','[code] texto [/code]: Seleccione o texto que deseja colocar como Código e em seguida clique aqui' );
DEFINE('_DESCRIPTION_IMAGE','[img=size]link para imagem[/img]: Insira o link de onde está a imagem sem o http:// e clique aqui para que ela apareça. Tamanho máximo = 499 pixels');
DEFINE('_DESCRIPTION_LIST','[list]List[/lista]: Para marcar os itens de uma lista com o sinal [*] selecione a lista  e depois clique aqui.');
DEFINE('_DESCRIPTION_SIZE','[size=escolha um tamanho]texto[/size]: Seleccione o texto e clique aqui. Depois, coloque o tamanho desejado até ao tamanho  (max. 7).');
DEFINE('_DESCRIPTION_RED','Vermelho, seleccione o texto e clique aqui para substituir a cor padrão (black).');
DEFINE('_DESCRIPTION_BLUE','Azul, seleccione o texto e clique aqui para substituir a cor padrão (black).');
DEFINE('_DESCRIPTION_GREEN','Verde, seleccione o texto e clique aqui para substituir a cor padrão (black).');
DEFINE('_DESCRIPTION_YELLOW','Amarelo, seleccione o texto e clique aqui para substituir a cor padrão (black).');
DEFINE('_DESCRIPTION_ORANGE','Laranja, seleccione o texto e clique aqui para substituir a cor padrão (black)');
DEFINE('_DESCRIPTION_PURPLE','Violeta, seleccione o texto e clique aqui para substituir a cor padrão (black)');
DEFINE('_DESCRIPTION_NAVY','Azul Marinho, seleccione o texto e clique aqui para substituir a cor padrão (black).');
DEFINE('_DESCRIPTION_DARKGREEN','Racing Verde, seleccione o texto e clique aqui para substituir a cor padrão (black).');
DEFINE('_DESCRIPTION_AQUA','Aqua, seleccione o texto e clique aqui para substituir a cor padrão (black).');
DEFINE('_DESCRIPTION_MAGENTA','Magenta, seleccione o texto e clique aqui para substituir a cor padrão (black).');
DEFINE('_FORUM_SEARCH','Pesquisar por:');
DEFINE('_SEARCH_RESULTS','Pesquisar resultados:');
DEFINE('_SEARCH_HITS','Hits');
DEFINE('_RESULTS_TITLE','No título do tópico');
DEFINE('_RESULTS_CONTENT','Nos conteúdos do tópico');
DEFINE('_RESULTS_FORUM_NAME','No nome do fórum');
DEFINE('_RESULTS_CATEGORY','No nome da categoria');
DEFINE('_FORUM_UNAUTHORIZIED','Este fórum só está disponível para utilizadores registados e ligados.');
DEFINE('_FORUM_UNAUTHORIZIED2','Se já é um utilizador registado, por favor efectue o seu login.');
//DEFINE('_COM_A_CHANGENAME' , 'Alterar Nome de Utilizador');
//DEFINE('_COM_A_CHANGENAME_DESC','Selecione &quot;Sim&quot;, se deseja permitir que os utilizadores registados mudem os seus nomes ao colocarem mensagens.');
DEFINE('_BACK_TO_FORUM','Voltar para o fórum');
DEFINE('_MESSAGE_ADMINISTRATION','Moderação');
DEFINE('_MOD_APPROVE','Aprovar');
DEFINE('_MOD_DELETE','Apagar');

//NEW in RC1
DEFINE('_POST_HTML_ENABLED','HTML inactivo');
DEFINE('_SHOW_LAST','Exibir mensagem mais recente');
DEFINE('_POST_WROTE','escrito');
DEFINE('_COM_A_EMAIL','Endereço de e-mail do fórum');
DEFINE('_COM_A_EMAIL_DESC','Endereço de e-mail do fórum. Coloque um endereço de e-mail válido');
DEFINE('_COM_A_WRAP','Quebrar palavras maiores que');
DEFINE('_COM_A_WRAP_DESC','Entre com o número máximo de caracteres que uma única palavra pode ter. Esta característica permite-lhe desenvolver um texto no Simpleboard de acordo com o seu tema.<br /> 70 caracteres são possivelmente o máximo mas pode
 alterar se desejar.<br />As URLs, não são afectadas pelo tamanho nem pela marginação automática');
DEFINE('_COM_A_SIGNATURE','Comprimento máximo da assinatura');
DEFINE('_COM_A_SIGNATURE_DESC','Número máximo de caracteres permitidos para a assinatura de um utilizador.');
DEFINE('_SHOWCAT_NOPENDING','Nenhuma mensagem pendente');
DEFINE('_COM_A_BOARD_OFSET','Correcção da hora do fórum');
DEFINE('_COM_A_BOARD_OFSET_DESC','Alguns fóruns estão situados em servidores onde a timezone é diferente de onde os utilizadores se encontram. Coloque o tempo que Simpleboard  tem que compensar ao fazer a postagem em horas. Podem ser usados números positivos e negativos');

//New in RC2
DEFINE('_COM_A_BASICS','Básico');
DEFINE('_COM_A_FRONTEND','Exibição');
DEFINE('_COM_A_SECURITY','Segurança');
DEFINE('_COM_A_AVATARS','Avatares');
DEFINE('_COM_A_RANKING','Ranking');
DEFINE('_COM_A_INTEGRATION','Integração');
DEFINE('_COM_A_PMS','Autoriza mensagem privada');
DEFINE('_COM_A_PMS_DESC','Seleccione o componente de mensagem privada se tiver algum instalado. Seleccionando myPMS Professional será também habilitado com opções relacionadas ao perfil do utilizador do myPMS Pro (como ICQ, AIM, Yahoo, MSN e links de perfil se suportado pelo tema usado no Simpleboard');
DEFINE('_VIEW_PMS','Clique aqui para enviar para este utilizador uma mensagem privada');

//new in RC3
DEFINE('_POST_RE','Re:');
DEFINE('_BBCODE_BOLD','Texto em Negrito: [b]text0[/b] ');
DEFINE('_BBCODE_ITALIC','Texto em Itálico: [i]texto[/i]');
DEFINE('_BBCODE_UNDERL','Texto Sublinhado: [u]texto[/u]');
DEFINE('_BBCODE_QUOTE','Citar: [quote]texto[/quote]');
DEFINE('_BBCODE_CODE','Móstrar código: [code]código[/code]');
DEFINE('_BBCODE_ULIST','Lista não-ordenada: [ul] [li]texto[/li] [/ul] - Sugestão: uma lista tem que conter itens ordenados');
DEFINE('_BBCODE_OLIST','Lista ordenada: [ol] [li]texto[/li] [/ol] - Sugestão: uma lista tem que conter itens ordenados');
DEFINE('_BBCODE_IMAGE','Imagem: [img size=(01-499)]http://www.google.com/images/web_logo_left.gif[/img]');
DEFINE('_BBCODE_LINK','Link: [url]http://www.zzz.com/[/url] or [url=http://www.zzz.com/]This is a link[/url]');
DEFINE('_BBCODE_CLOSA','Fechar todas as tags');
DEFINE('_BBCODE_CLOSE','Feche todas as tags BBCode abertas');
DEFINE('_BBCODE_COLOR','Cores: [color=#FF6600]texto[/color]');
DEFINE('_BBCODE_SIZE','Tamanho: [size=1]texto tamanho[/size] - Sugestão: tamanhos variam de 1 a 5');
DEFINE('_BBCODE_LITEM','Itens da lista: [li] item da lista [/li]');
DEFINE('_BBCODE_HINT','Ajuda do bbCode - Sugestão: bbCode pode ser usado na selecção do texto!');
DEFINE('_COM_A_TEXTAREA','Área de texto');
DEFINE('_COM_A_TAWIDTH','Largura da área de texto');
DEFINE('_COM_A_TAWIDTH_DESC','Ajuste a largura da área de texto para se adaptar ao seu tema. <br />A barra de tópicos de Emoticon será sobreposta através duas linhas com largura <= 450');
DEFINE('_COM_A_TAHEIGHT','Altura da área de texto');
DEFINE('_COM_A_TAHEIGHT_DESC','Ajuste a altura da área de texto para se adaptar ao seu tema');
DEFINE('_COM_A_ASK_EMAIL','Exigir e-mail');
DEFINE('_COM_A_ASK_EMAIL_DESC','É necessário um endereço de e-mail quando um utilizador ou visitante colocar uma mensagem? Seleccione &quot;Não&quot; se não deseja colocar esta característica na primeira página. Não será solicitado o endereço de e-mail.');

?>
