<?php
/**
* Letterman Newsletter Component
* 
* @package Mambo_4.5.1
* @subpackage Letterman
Authors:
* @copyright Soeren Eberhardt <soeren@mambo-phpshop.net>
    (who just needed an easy and *working* Newsletter component for Mambo 4.5.1 and mixed up Newsletter and YaNC)
* @copyright Mark Lindeman <mark@pictura-dp.nl> 
    (parts of the Newsletter component by Mark Lindeman; Pictura Database Publishing bv, Heiloo the Netherland)
* @copyright Adam van Dongen <adam@tim-online.nl>
    (parts of the YaNC component by Adam van Dongen, www.tim-online.nl)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
	  
require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );


$cid = mosGetParam( $_POST, 'cid', array(0) );
$task = mosGetParam( $_REQUEST, 'task' );
$no_html = mosGetParam( $_REQUEST, 'no_html' );

if (!is_array( $cid )) {
	$cid = array(0);
}
if(!$no_html) {
  HTML_Letterman::header();
}

switch( $task ) {
	case "new":
		editNewsletter( 0, $option);
		break;

	case "edit":
		editNewsletter( $cid[0], $option );
		break;

	case "save":
        saveNewsletter( $option );
		break;

	case "remove":
		removeNewsletter( $cid, $option );
		break;

	case "publish":
		publishNewsletter( $cid, 1, $option );
		break;

	case "unpublish":
		publishNewsletter( $cid, 0, $option );
		break;

	case "cancel":
		cancelNewsletter( $option );
		break;

	case "sendNow":
		sendNewsletter( $cid[0], $option );
		break;

	case "sendMail":
		sendMail();
		break;

  //subscriber management
	case "subscribers":
	  HTML_Letterman::showSubscriberOverview();
	  break;
	case "editSubscriber":
	  HTML_Letterman::editSubscriber( $cid[0] );
	  break;
	  
	case "saveSubscriber":
	  HTML_Letterman::saveSubscriber();
	  break;
		  
	case "assignUsers":
	  assignUsers();
	  break; 
	  
	case "deleteSubscriber":
	  if( sizeof( $_REQUEST['cid'] > 1 )) {
		HTML_Letterman::deleteSubscribers();
	  }
	  else {
		HTML_Letterman::deleteSubscriber( $cid[0] );
	  }
	  break;
	  
	case "importSubscribers":
	  importSubscribers();
	  break;
	  
	case "exportSubscribers":
	  doExport();
	  break;
	  
	default:
		viewNewsletter( $option );
		break;
}
HTML_letterman::footer();

function viewNewsletter( $option) {
	global $database, $mainframe;

	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
	$search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search = $database->getEscaped( trim( strtolower( $search ) ) );

	$where = "";
	if ($search) {
		$where = " WHERE a.subject LIKE '%$search%' OR a.message LIKE '%$search%'";
	}

	$database->setQuery( "SELECT COUNT(*) FROM #__letterman AS a $where" );
	$total = $database->loadResult();

	require_once( "includes/pageNavigation.php" );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$sql = "SELECT
        a.*, u.name AS editor, g.name AS groupname"
	. "\nFROM #__letterman AS a"
	. "\nLEFT JOIN #__users AS u ON u.id=a.checked_out"
	. "\nLEFT JOIN #__groups AS g ON g.id = a.access"
	. "\n$where ORDER BY created DESC LIMIT $pageNav->limitstart,$pageNav->limit";
	$database->setQuery( $sql );
	$rows = $database->loadObjectList();

	HTML_Letterman::showNewsletter( $rows, $search, $pageNav, $option );
}

function editNewsletter( $uid, $option ) {
	global $database, $my;

	$row = new mosLetterman( $database );
	// load the row from the db table
	$row->load( $uid );

	if ($uid) {
		$row->checkout( $my->id );
	} else {
		// initialise new record
		$row->published = 0;
	}

	// make the select list for the image positions
	$yesno[] = mosHTML::makeOption( '0', 'No' );
	$yesno[] = mosHTML::makeOption( '1', 'Yes' );

	// build the html select list
	$publist = mosHTML::selectList( $yesno, 'published', 'class="inputbox" size="2"',
	'value', 'text', $row->published );
	
	// get list of groups
	$database->setQuery( "SELECT id AS value, name AS text FROM #__groups ORDER BY id" );
	$groups = $database->loadObjectList();	if (!($orders = $database->loadObjectList())) {
		echo $database->stderr();
		return false;
	}

	// build the html select list
	$glist = mosHTML::selectList( $groups, 'access', 'class="inputbox" size="1"',
	'value', 'text', intval( $row->access ) );


	HTML_Letterman::editNewsletter( $row, $publist, $option , $glist );
}

function saveNewsletter( $option ) {
	global $database, $my;

	$row = new mosLetterman( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();

	mosRedirect( "index2.php?option=$option" );
}

/**
* Publishes or Unpublishes one or more records
* @param database A database connector object
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The current url option
*/
function publishNewsletter( $cid=null, $publish=1, $option ) {
	global $database, $my;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
		exit;
	}

	$cids = implode( ',', $cid );

	$database->setQuery( "UPDATE #__letterman SET published='$publish'"
	. "\nWHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$my->id'))"
	);
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (count( $cid ) == 1) {
		$row = new mosLetterman( $database );
		$row->checkin( $cid[0] );
	}
	mosRedirect( "index2.php?option=$option" );
}

/**
* Deletes one or more records
* @param array An array of unique category id numbers
* @param string The current url option
*/
function removeNewsletter( $cid, $option ) {
	global $database;

	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$database->setQuery( "DELETE FROM #__letterman WHERE id IN ($cids)" );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	mosRedirect( "index2.php?option=$option" );
}

/**
* Cancels an edit operation
* @param string The current url option
*/
function cancelNewsletter( $option ) {
	global $database;
	$row = new mosLetterman( $database );
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect( "index2.php?option=$option" );
}

function sendNewsletter ( $uid, $option )
{
	global $database, $my;

    // Get default emailaddress
    $database->setQuery( "SELECT email FROM #__users WHERE usertype='superadministrator' LIMIT 0,1");
    $row = $database->loadObjectList();
    $admin_email = $row[0]->email;

	$row = new mosLetterman( $database );
	// load the row from the db table
	$row->load( $uid );

	// get list of groups
	$groups = array(
		mosHTML::makeOption( "subscribers", '- All Subscribers -' ),
		mosHTML::makeOption( 0, '- All User Groups -' )
	);
	$database->setQuery( "SELECT group_id AS value, name AS text FROM #__core_acl_aro_groups WHERE group_id<>17 AND group_id NOT in(28,29,30) ORDER BY group_id" );
	$groups = array_merge( $groups, $database->loadObjectList() );

	// build the html select list
	$grouplist = mosHTML::selectList( $groups, 'sendto', 'class="inputbox" size="1"',
	'value', 'text', '-1' );

	HTML_Letterman::sendNewsletter( $row, $option , $grouplist, $admin_email );
}

function sendMail() {

    global $database, $my, $mosConfig_sitename, $mosConfig_live_site, $mosConfig_lang, 
		  $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_sef;
	include_once( $mosConfig_absolute_path."/includes/sef.php" );
    /*
     * because sending mail may take a long time, we want to disable timeout
     * unfortunately when you are running php in safe mode you cannot use set_time_limnit(0)
     * therefor I've made the timout optional.
     */
	  
    $disable_timeout  = mosGetParam( $_POST, "disable_timeout", '' );
    if ( $disable_timeout ) {
        @set_time_limit(0);
    }
    // Get default emailaddress
    $database->setQuery( "SELECT email FROM #__users WHERE usertype='superadministrator' LIMIT 0,1");
    $admin_email = $database->loadResult();
    echo $database->getErrorMsg();

    $id  = mosGetParam( $_POST, "id", '' );
    $sendto = mosGetParam( $_POST, "sendto", null );
    $mailfrom = mosGetParam( $_POST, "mailfrom", $mosConfig_mailfrom );
    $confirmed_accounts = mosGetParam( $_POST, "confirmed_accounts", "0" );
	
    $replyto = mosGetParam( $_POST, "replyto", false );

    $mailfrom = $mailfrom ? $mailfrom : $admin_email;
    $replyto = $replyto ? $replyto : $admin_email;

    if ( $sendto===null) {
        mosRedirect( "index2.php?mosmsg=".LM_ERROR_NEWSLETTER_COULDNTBESENT );
    }
	// Get Itemid for Letterman
    $database->setQuery ( "SELECT id FROM #__menu WHERE link LIKE '%com_letterman%'" );
	$database->loadObject($myid);
    if( !empty($myid->id))
        $Itemid = $myid->id;
    else
        $Itemid = 1;
		
    // Get newsletter
    $database->setQuery( "SELECT subject, message, html_message FROM #__letterman WHERE id='$id'");
    $database->loadObject( $newsletter );
	
    // Build e-mail message format
    $subject = $newsletter->subject;
    $message = $newsletter->message;
	$unsub_link = sefRelToAbs("index.php?option=com_letterman&task=unsubscribe&Itemid=$Itemid");
	$unsub_link_html = "<a href=\"$unsub_link\">$unsub_link</a>";
	
	$footer_html = str_replace( "[UNLINK]", $unsub_link_html, LM_NEWSLETTER_FOOTER );
	$footer_html = str_replace( "[mosConfig_live_site]", "<a href=\"$mosConfig_live_site\">$mosConfig_live_site</a>", $footer_html );
	
	$footer_text = str_replace( "[UNLINK]", $unsub_link, LM_NEWSLETTER_FOOTER );
	$footer_text = str_replace( "[mosConfig_live_site]", $mosConfig_live_site, $footer_text );
	$footer_text = str_replace( "<br/>", "", $footer_text );
	$footer_text = str_replace( "<br />", "", $footer_text );
	
    $html_message = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
					  <html>
						  <head>
							  <title>$mosConfig_sitename :: $subject</title>
						  </head>
						  <body>"
						  . $newsletter->html_message 
						  . $footer_html .
						  "</body>
					  </html>";
					  
	// Create the PHPMailer Object ( we need that NOW! )
	$mymail = mosCreateMail( $mailfrom, $mosConfig_fromname, $subject, $html_message);
	$mymail->AddReplyTo( $replyto, $mosConfig_fromname );
	
	// Handle <img />Images and embed ALL images
	$images = array();
	if (preg_match_all("/<img[^>]*>/", $html_message, $images) > 0) {
	  $i = 0;
	  foreach ($images as $image) {
	  	if ( is_array( $image ) ) {
		  foreach( $image as $src) {
			  preg_match("'src=\"[^\"]*\"'si", $src, $matches);
			  $source = str_replace ("src=\"", "", $matches[0]);
			  
			  $source = str_replace ("\"", "", $source);
			  $filename = basename( $source );
			  
			  // must be a remote Image or somethin with ../../../image.gif then
			  if (!stristr($source, $mosConfig_live_site)) {
			  
				// must be a local image. 
				// Attention! Now we guess it's located somewhere in the folder /images/ !!!
				if (!stristr($source, "http")) {
				  $source = substr( $source, strpos( $source, "images" ), strlen( $source) );
				  $source = "$mosConfig_absolute_path/$source";
				}
				else {
				  // so get it!!
				  // Read the Image
				  $fp = @fopen( $source, "rb" );
				  if( $fp ) {
					$img = "";
					while(!feof($fp)) {
						$img = $img . fread($fp, 1024);
					} 
					fclose ($fp);
					$source = "$mosConfig_absolute_path/$mosConfig_cachepath/$filename";
					// Write the image to /cache
					$write_image = fopen( $source, "wb" );
					fwrite( $write_image, $img );
					fclose( $write_image );
				  }
				}
			  }
			  else {
				$source = str_replace( $mosConfig_live_site, $mosConfig_absolute_path, $source );
			  }
			  $pathinfo  = pathinfo( $filename );
			  $cid = basename( $filename, ".".$pathinfo['extension'] );
			  $size = @getimagesize( $source );
			  
			  switch($pathinfo['extension']) {
				case "jpg":
				case "jpeg":
				  $mimetype = "image/jpeg"; break;
				case "png":
				  $mimetype = "image/png"; break;
				case "gif":
				  $mimetype = "image/gif"; break;
				case "swf":
				  $mimetype = "image/swf"; break;
			  }
			  $mymail->AddEmbeddedImage( $source, $cid, $filename, "base64", $mimetype );
			  $newtag = $size[3] ." src=\"cid:$cid\"";
			  $html_message = str_replace( $matches[0], $newtag, $html_message );
		  }
		}
	  }
	}
	
    // Get all users email and group
	if( $sendto == "subscribers" ) {
	  $q = "SELECT subscriber_name AS name, subscriber_email AS email FROM #__letterman_subscribers";
	  if( $confirmed_accounts == "1" )
		$q .= " WHERE confirmed='1'";
	  $database->setQuery( $q );
	}
	else {
	  $query_appendix = ", #__core_acl_aro, #__core_acl_groups_aro_map WHERE #__core_acl_aro.value=#__users.id AND #__core_acl_groups_aro_map.aro_id = #__core_acl_aro.aro_id AND #__core_acl_groups_aro_map.group_id='$sendto'"; 
	  $query = "SELECT #__users.name, email FROM #__users ";
	  $query .= ($sendto !== '0') ? $query_appendix : "";
	  $database->setQuery( $query );
	}
    $rows = $database->loadObjectList();
    echo $database->getErrorMsg();
	
	// Now process all Recepients
	$i = 0;
	$errors = 0;
	foreach ($rows as $row) {
		// Now let's update the HTML Mail Body
		$mymail->Body = str_replace( "[NAME]", $row->name, $html_message);
		// Set alternative Body with Text Message
		$mymail->AltBody = str_replace( "[NAME]", $row->name, $message . $footer_text );
		
		$mymail->ClearAddresses();
		$mymail->AddAddress( $row->email, $row->name );
		
		//Send email
		if( $mymail->Send()) {
		  $i++;
		}
		else {
		  $errors++;
		}
	}
	if( $errors == 0) {
	  $database->setQuery( "UPDATE #__letterman SET send=NOW() WHERE id=$id" );
	  $database->query();
	  $msg = str_replace( "{X}", $i, LM_NEWSLETTER_SENDTO_X_USERS);
	  mosRedirect( "index2.php?option=com_letterman", $msg );
	}
	else {
	  //echo $mymail->ErrorInfo;
	  mosRedirect( "index2.php?option=com_letterman", $mymail->ErrorInfo." =&gt; $errors Errors" );
	}
}


	function assignUsers() {
	  global $database;
	  
	  $q = "SELECT id, name, username, email FROM #__users LEFT JOIN #__letterman_subscribers ON id = user_id OR email=subscriber_email WHERE user_id IS NULL";
	  $database->setQuery( $q );
	  
	  $rows = $database->loadObjectList();
	  
	  HTML_Letterman::assignUsers( $rows );
	}
	

	function doExport() {
		global $database, $mosConfig_absolute_path;
		
		// Workaround for GZIP = On
		if( stristr( $_SERVER['PHP_SELF'], "index2" ) )
		  mosRedirect( "index3.php?option=com_letterman&task=exportSubscribers&no_html=1" );
		  
		if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
            $UserBrowser = "Opera";
		}
		elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
		  $UserBrowser = "IE";
		} else {
		  $UserBrowser = '';
		}
		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
          
		$filename = "backup_list_" . $list . "_from_" . date('d-m-Y');

		$output = '<?xml version="1.0" encoding="ISO-8859-1" ?>
				  <!-- Letterman export file -->
				  <!DOCTYPE subscribers [
				  <!ELEMENT subscribers (subscriber+)>
				  <!ELEMENT subscriber (subscriber_id, name, email, confirmed, subscribe_date)>
				  <!ELEMENT subscriber_id (#PCDATA)>
				  <!ELEMENT name (  CDATA)>
				  <!ELEMENT email (#PCDATA)>
				  <!ELEMENT confirmed (#PCDATA)>
				  <!ELEMENT subscribe_date (#PCDATA)>
				  ]>
				  <subscribers>
				  ';
		$q = "SELECT * FROM #__letterman_subscribers";
		$database->setQuery( $q );
		$subscribers = $database->loadObjectList();
		
		foreach ($subscribers AS $subscriber){
			$output .= "  <subscriber>\n";
			$output .= "    <subscriber_id>" . $subscriber->subscriber_id . "</subscriber_id>\n";
			$output .= "    <name>" . htmlentities($subscriber->subscriber_name) . "</name>\n";
			$output .= "    <email>" . $subscriber->subscriber_email . "</email>\n";
			$output .= "    <confirmed>" . $subscriber->confirmed . "</confirmed>\n";
			$output .= "    <subscribe_date>" . $subscriber->subscribe_date . "</subscribe_date>\n";
			$output .= "  </subscriber>\n";
		}
		$output .= "</subscribers>";
		

		//send file to browser
		
  		ob_end_clean();
  		ob_start();
		
  		header('Content-Type: ' . $mime_type);
  		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  
  		if ($UserBrowser == 'IE') {
  			header('Content-Disposition: inline; filename="' . $filename . '.xml"');
  			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  			header('Pragma: public');
  		} 
		else {
  			header('Content-Disposition: attachment; filename="' . $filename . '.xml"');
  			header('Pragma: no-cache');
  		}
  		print $output;
  		exit();
	}
	
	function importSubscribers() { 
		global $database, $mosConfig_absolute_path, $mosConfig_live_site, $option, $mosConfig_cachepath;
		//send mailing to an entered emailadres
	  if(!empty($_FILES['xmlfile']) && !empty($_FILES['cvsfile'])){
?>
     <table class="adminheading">
		<tr>
			<th><?php echo LM_IMPORT_USERS ?></th>
		</tr>
		</table>
    
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<tr>
      <th colspan="2" class="title">&nbsp;</th>
    </tr>
    <tr>
      <td>
<?php
      if(!empty($_FILES['xmlfile']['name'])){
  	    if($_FILES['xmlfile']['type'] == "text/xml"){
          $filename = $_FILES['xmlfile']['name'];
                
		  $path = $mosConfig_cachepath . '/';
		  if( is_writable($path) ) {
      	      
      	    if(!move_uploaded_file($_FILES['xmlfile']['tmp_name'], $path . $filename)) {
              print "<font class=\"error\">".LM_UPLAOD_FAILED.": " . $_FILES['xmlfile']['error'] . "</font><br>\n";
			}
			else {
          	    
			  $data = html_entity_decode(file_get_contents($path . $filename));
			
			  /* XML Parsing */
			  require_once( $mosConfig_absolute_path. '/includes/domit/xml_domit_lite_include.php' );
			  $xmlDoc =& new DOMIT_Lite_Document();
			  if( !$xmlDoc->parseXML( $data, false, true )) {
				echo "<span class=\"message\">".LM_ERROR_PARSING_XML."</span>";
				return;
			  }
			  $nodelist = $xmlDoc->getElementsByTagName( "subscriber" );

          	  if($nodelist->getLength() > 0){
				$count = 0;
          	    for ($i = 0; $i < $nodelist->getLength(); $i++) {
				  $currNode =& $nodelist->item($i);
				  $subscriber['subscriber_id'] = $currNode->childNodes[0]->getText();
				  $subscriber['name'] = $currNode->childNodes[1]->getText();
				  $subscriber['email'] = $currNode->childNodes[2]->getText();
				  $subscriber['confirmed'] = $currNode->childNodes[3]->getText();
				  $subscriber['subscribe_date'] = $currNode->childNodes[4]->getText();
				  
				  if( !empty( $subscriber['name'] ) && !empty( $subscriber['email'] )) {
					$query = "REPLACE INTO #__letterman_subscribers VALUES(" 
						  . "'" 
						  . $subscriber['subscriber_id'] . "', '', '" 
						  . $subscriber['name'] . "', '" 
						  . $subscriber['email'] . "', '" 
						  . $subscriber['confirmed'] . "', '" 
						  . $subscriber['subscribe_date'] . "');";
					$database->setQuery($query);
					$database->query();
					$error = $database->getErrorNum();
					if( $error ) {
					  if($error == 1062){
						echo '<span class="error">' . LM_ERROR_EMAIL_ALREADY_ONLIST . ": ".$subscriber['email']."'. </span><br />";
					  }
					  else{
					  echo $database->getErrorMsg() . "<br />\n";
					  }
					}
					else {
					  $count++;
					}
				  }
          	    }
				$msg = str_replace( "{X}", $count, LM_SUCCESS_ON_IMPORT );
          	    echo "<span class=\"message\">$msg</span><br /><br />".LM_IMPORT_FINISHED."<br />
          	    <a href=\"index2.php?option=com_letterman&task=subscribers\">"._CMN_CONTINUE."</a>"; 
          	  }
          	  
          	  if( !unlink($path . $filename) ) {
          	    print"<font class=\"error\">" . LM_ERROR_DELETING_FILE . ": $path | $filename.</font><br>\n";
          	  }
			}
		  }
		  else {
			echo '<span class="error">' . LM_DIR_NOT_WRITABLE . '</span>';
		  }
  	    }
  	    else{
  	      echo '<span class="error">'.LM_ERROR_NO_XML.'</span>';
  	    }
      }
      else{
        if(
          ($_FILES['cvsfile']['type'] == "application/octet-stream") ||
          ($_FILES['cvsfile']['type'] == "application/vnd.ms-excel")
          ){
          $filename = $_FILES['cvsfile']['name'];
                
    	    $path = $mosConfig_cachepath . '/';
    	    if(is_writable($path)){
      	      
      	    if(!move_uploaded_file($_FILES['cvsfile']['tmp_name'], $path . $filename)){
              echo "<span class=\"error\">".LM_UPLAOD_FAILED.": " . $_FILES['cvsfile']['error'] . "</span><br>\n";
			}
			else{
          	  //print_r($_FILES);
          	  $name_column = mosGetParam($_POST, 'name_column', '1');
          	  $address_column = mosGetParam($_POST, 'address_column', '2');
          	  $delim = mosGetParam($_POST, 'delimiter', ';');
          	  $offset = mosGetParam($_POST, 'record_number', 1);
          	  $offset = $offset - 1; //default an array starts at 0 in stead of 1
          	  $name_column = $name_column - 1; //default an array starts at 0 in stead of 1
          	  $address_column = $address_column - 1; //default an array starts at 0 in stead of 1
          	  $content = file($path . $filename);
          	  
          	  
          	  if(sizeof($content) > 0){
          	    for($i = $offset; $i < sizeof($content); $i++){
          	      
          	      $subscriber = explode($delim, $content[$i]);
          	      //var_dump($subscriber);
          	      $subscriber[$address_column] = ltrim(rtrim($subscriber[$address_column]));
          	      $subscriber[$address_column] = str_replace('"', '', $subscriber[$address_column]);
          	      $subscriber[$name_column] = str_replace('"', '', $subscriber[$name_column]);
          	      if(!empty($subscriber[$address_column])){
          	        //echo $subscriber[$address_column] . ": ". intval(check_email_address($subscriber[$address_column])) .'<br />';
                    if(check_email_address($subscriber[$address_column])){
              	      $query = "REPLACE INTO #__letterman_subscribers (subscriber_name, subscriber_email, confirmed, subscribe_date) 
              	      VALUES('". addslashes($subscriber[$name_column]) . "', '". addslashes($subscriber[$address_column]) . "', '1', NOW());";
              	      $database->setQuery($query);
              	      $database->query();
              	      $error = $database->getErrorNum();
                  		if($error){ 
                  		  if($error == 1062){
                  		    echo '<span class="error">' . LM_ERROR_EMAIL_ALREADY_ONLIST . ": ".$subscriber['email']."'. </span><br />";
                  		  }
                  		  else{
                          echo $database->getErrorMsg() . "<br />\n";
                  		  }
                  		}
                    }
                    else{
                      echo '<span class="error"><b>'.LM_ERROR_INVALID_EMAIL.':</b> ' . $subscriber[$address_column] . '</span><br />';
                    }
          	      }
          	      else{
          	      	echo '<span class="error">'.LM_ERROR_EMPTY_EMAIL.':' . $subscriber[$name_column] . '</span><br />';
          	      }
          	    }
          	    echo '<br /><br />'.LM_IMPORT_FINISHED.'<br />
          	    <a href="index2.php?option=com_letterman&task=subscribers">'._CMN_CONTINUE.'</a>'; 
          	  }
          	  else{
          	  	echo '<font class="error">'.LM_ERROR_EMPTY_FILE.'</font><br />';
          	  }
          	  
          	  if(!unlink($path . $filename)){
          	    print"<font class=\"error\">" . LM_ERROR_DELETING_FILE.": ".$path ." | ". $filename . ".</font><br>\n";
          	  }
        	  }
    	    }
    	    else{
    	      echo '<span class="error">' . LM_DIR_NOT_WRITABLE . '</span><br />';
    	    }
  	    }
  	    else{
  	      echo '<span class="error">'.LM_ERROR_ONLY_TEXT.'</span><br />';
  	    }
      }
?>
    </td>
  </tr>
  </table>
<?php
	  }
	  else{
?>
    <table class="adminheading">
		<tr>
			<th><?php echo LM_SELECT_FILE ?>:</th>
		</tr>
		</table>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script language="Javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
    <form action="index2.php" method="POST" name="adminForm" enctype="multipart/form-data">
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="importSubscribers" />
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<tr>
      <th colspan="3" class="title">&nbsp;</th>
    </tr>
    <tr>
      <td width="200" colspan="2"><strong><?php echo LM_YOUR_XML_FILE ?>: </strong></td>
      <td><input type="file" name="xmlfile"></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </td>
    <tr>
      <td width="200" colspan="2"><strong><?php echo LM_YOUR_CSV_FILE ?>: </strong></td>
      <td><input type="file" name="cvsfile"></td>
    </tr>
    <tr>  
		  <td width="20" valign="top"><?php echo mosToolTip(LM_POSITION_NAME); ?></td>    
      <td><?php echo LM_NAME_COL ?>:</td>
      <td><input type="text" name="name_column" size="2" value="1" /></td>
    </tr>
    <tr>
		  <td width="20" valign="top"><?php echo mosToolTip(LM_POSITION_EMAIL); ?></td>
      <td><?php echo LM_EMAIL_COL ?>:</td>
      <td><input type="text" name="address_column" size="2" value="2" /></td>
    </tr>
    <tr>
		  <td width="20" valign="top"><?php echo mosToolTip(LM_STARTFROM); ?></td>
      <td><?php echo LM_STARTFROMLINE ?>:</td>
      <td><input type="text" name="record_number" size="2" value="1" /></td>
    </tr>
    <tr>
		  <td width="20" valign="top"><?php echo mosToolTip(LM_CSV_DELIMITER_TIP); ?></td>
      <td><?php echo LM_CSV_DELIMITER ?>:</td>
      <td><input type="text" name="delimiter" size="2" value=";"/></td>
    </tr>
    </table>
    </form>
<?php
	  }
  }

function check_email_address($email) {
  // First, we check that there's one @ symbol, and that the lengths are right
  if (!ereg("[^@]{1,64}@[^@]{1,255}", $email)) {
    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
     if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
      return false;
    }
  }  
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}
?>
