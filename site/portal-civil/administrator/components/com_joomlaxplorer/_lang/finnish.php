<?php
// Finnish Language Module for joomlaXplorer (translated by Jarmo Ker�nen)
// modifications and translation of new strings by Markku Suominen 10.11.2005
// Finnish Joomla translation team, http://www.joomlaportal.fi, admin@joomlaportal.fi
global $_VERSION;

$GLOBALS["charset"] = "iso-8859-1";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "m.d.y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "VIRHE(ET)",
	"back"			=> "Palaa",
	
	// root
	"home"			=> "Kotihakemistoa ei ole, tarkista asetuksesi.",
	"abovehome"		=> "Nykyinen hakemisto ei saa olla kotihakemiston yl�puolella.",
	"targetabovehome"	=> "Kohdehakemisto ei saa olla kotihakemiston yl�puolella.",
	
	// exist
	"direxist"		=> "Hakemistoa ei ole.",
	//"filedoesexist"	=> "This file already exists.",
	"fileexist"		=> "Tiedostoa ei ole.",
	"itemdoesexist"		=> "Nimike on jo olemassa.",
	"itemexist"		=> "Nimike ei ole olemassa.",
	"targetexist"		=> "Kohdehakemistoa ei ole.",
	"targetdoesexist"	=> "Kohdenimike on jo olemassa.",
	
	// open
	"opendir"		=> "Hakemistoa ei voi avata.",
	"readdir"		=> "Hakemistoa ei voi lukea.",
	
	// access
	"accessdir"		=> "Sinulla ei ole valtuuksia t�h�n hakemistoon.",
	"accessfile"		=> "Sinulla ei ole valtuuksia t�h�n tiedostoon.",
	"accessitem"		=> "Sinulla ei ole valtuuksia t�h�n nimikkeeseen.",
	"accessfunc"		=> "Sinulla ei ole valtuuksia t�h�n toimintoon.",
	"accesstarget"		=> "Sinulla ei ole valtuuksia kohdehakemistoon.",
	
	// actions
	"permread"		=> "K�ytt�oikeuksien luku ep�onnistui.",
	"permchange"		=> "K�ytt�oikeuksien muutos ep�onnistui.",
	"openfile"		=> "Tiedoston avaaminen ep�onnistui.",
	"savefile"		=> "Tiedoston tallennus ep�onnistui.",
	"createfile"		=> "Tiedoston luonti ep�onnistui.",
	"createdir"		=> "Hakemiston luonti ep�onnistui.",
	"uploadfile"		=> "Tiedoston vienti palvelimelle ep�onnistui.",
	"copyitem"		=> "Kopiointi ep�onnistui.",
	"moveitem"		=> "Siirto ep�onnistui.",
	"delitem"		=> "Poisto ep�onnistui.",
	"chpass"		=> "Salasanan vaihto ep�onnistui.",
	"deluser"		=> "K�ytt�j�n poisto ep�onnistui.",
	"adduser"		=> "K�ytt�j�n lis�ys ep�onnistui.",
	"saveuser"		=> "K�ytt�j�n tallennus ep�onnistui.",
	"searchnothing"		=> "Sinun pit�� antaa jotain etsitt�v��.",
	
	// misc
	"miscnofunc"		=> "Toiminto ei ole k�ytett�viss�.",
	"miscfilesize"		=> "Tiedosto koko ylitt�� suurimman sallitun arvon.",
	"miscfilepart"		=> "Tiedoston vienti palvelimelle onnistui vain osittain.",
	"miscnoname"		=> "Anna nimi.",
	"miscselitems"		=> "Et ole valinnut yht��n nimikett�.",
	"miscdelitems"		=> "Haluatko varmasti poistaa n�m� \"+num+\" nimikett�?",
	"miscdeluser"		=> "Haluatko varmasti poistaa k�ytt�j�n '\"+user+\"'?",
	"miscnopassdiff"	=> "Uusi salasana ei eroa nykyisest�.",
	"miscnopassmatch"	=> "Salasanat eiv�t t�sm��.",
	"miscfieldmissed"	=> "Ohitit t�rke�n kent�n.",
	"miscnouserpass"	=> "K�ytt�j�nimi tai salasana on v��r�.",
	"miscselfremove"	=> "Et voi poistaa omaa tunnustasi.",
	"miscuserexist"		=> "K�ytt�j� on jo olemassa.",
	"miscnofinduser"	=> "K�ytt�j�� ei l�ydy.",
	"extract_noarchive" => "Tiedoston tyyppi ei ole sellainen joka voidaan purkaa.",
	"extract_unknowntype" => "Tuntematon arkistointimuoto"
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "MUUTA OIKEUKSIA",
	"editlink"		=> "MUOKKAA",
	"downlink"		=> "LATAA",
	"uplink"		=> "YL�S",
	"homelink"		=> "KOTI",
	"reloadlink"		=> "P�IVIT�",
	"copylink"		=> "KOPIOI",
	"movelink"		=> "SIIRR�",
	"dellink"		=> "POISTA",
	"comprlink"		=> "ARKISTOI",
	"adminlink"		=> "HALLINTA",
	"logoutlink"		=> "POISTU",
	"uploadlink"		=> "VIE PALVELIMELLE",
	"searchlink"		=> "ETSI",
	"extractlink"	=> "Pura arkistotiedosto",
	'chmodlink'		=> 'Muuta (chmod) oikeudet (kansio/tiedosto(t))', // new mic
	'mossysinfolink'	=> $_VERSION->PRODUCT.' J�rjestelm�tiedot ('.$_VERSION->PRODUCT.', Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Siiry joomlaXplorer sivustolle(uusi ikkuna)', // new mic
	
	// list
	"nameheader"		=> "Nimi",
	"sizeheader"		=> "Koko",
	"typeheader"		=> "Tyyppi",
	"modifheader"		=> "Muutettu",
	"permheader"		=> "Oikeudet",
	"actionheader"		=> "Toiminnot",
	"pathheader"		=> "Polku",
	
	// buttons
	"btncancel"		=> "Peruuta",
	"btnsave"		=> "Tallenna",
	"btnchange"		=> "Muuta",
	"btnreset"		=> "Peru",
	"btnclose"		=> "Sulje",
	"btncreate"		=> "Luo",
	"btnsearch"		=> "Etsi",
	"btnupload"		=> "Vie palvelimelle",
	"btncopy"		=> "Kopioi",
	"btnmove"		=> "Siirr�",
	"btnlogin"		=> "Kirjaudu",
	"btnlogout"		=> "Poistu",
	"btnadd"		=> "Lis��",
	"btnedit"		=> "Muokka",
	"btnremove"		=> "Poista",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'NIME�',
	'confirm_delete_file' => 'Haluatko varmasti poistaa tiedoston? \\n%s',
	'success_delete_file' => 'Nimike(tt�) poistettu .',
	'success_rename_file' => 'Hakemisto/tiedosto  %s nimettiin uudelleen, uusi nimi on %s.',
	
	
	// actions
	"actdir"		=> "Hakemisto",
	"actperms"		=> "Muuta oikeuksia",
	"actedit"		=> "Muokkaa tiedostoa",
	"actsearchresults"	=> "Etsinn�n tulokset",
	"actcopyitems"		=> "Kopioi nimikkeet",
	"actcopyfrom"		=> "Kopioi kohteesta /%s kohteeseen /%s ",
	"actmoveitems"		=> "Siirr� nimikkeet",
	"actmovefrom"		=> "Siirr� kohteesta /%s kohteeseen /%s ",
	"actlogin"		=> "Kirjaudu",
	"actloginheader"	=> "Kirjaudu k�ytt��ksesi joomlaXploreria",
	"actadmin"		=> "Hallinta",
	"actchpwd"		=> "Muuta salasana",
	"actusers"		=> "K�ytt�j�t",
	"actarchive"		=> "Arkistoi nimikkeet",
	"actupload"		=> "Vie tiedostot palvelimelle",
	
	// misc
	"miscitems"		=> "Nimikkeet",
	"miscfree"		=> "Vapaa",
	"miscusername"		=> "K�ytt�j�nimi",
	"miscpassword"		=> "Salasana",
	"miscoldpass"		=> "Vanha salasana",
	"miscnewpass"		=> "Uusi salasana",
	"miscconfpass"		=> "Vahvista salasana",
	"miscconfnewpass"	=> "Vahvista uusi salasana",
	"miscchpass"		=> "Muuta salasana",
	"mischomedir"		=> "Kotihakemisto",
	"mischomeurl"		=> "Koti URL",
	"miscshowhidden"	=> "N�yt� piilotetut nimikkeet",
	"mischidepattern"	=> "Piilota kuvio",
	"miscperms"		=> "Oikeudet",
	"miscuseritems"		=> "(nimi, kotihakemisto, n�yt� piilotetut nimikkeet, oikeudet, aktiivi)",
	"miscadduser"		=> "lis�� k�ytt�j�",
	"miscedituser"		=> "muokkaa k�ytt�j�� '%s'",
	"miscactive"		=> "Aktiivi",
	"misclang"		=> "Kieli",
	"miscnoresult"		=> "Ei tuloksia.",
	"miscsubdirs"		=> "Etsi alahakemistoista",
	"miscpermnames"		=> array("Vain katselu","Muokkaa","Muuta salasana","Muokkaa & Muuta salasana",
					"Hallinta"),
	"miscyesno"		=> array("Kyll�","Ei","K","E"),
	"miscchmod"		=> array("Omistaja", "Ryhm�", "Julkinen"),
	// from here all new by mic
	'miscowner'			=> 'Omistaja',
	'miscownerdesc'		=> '<strong>Kuvaus:</strong><br />K�ytt�j� (UID) /<br />Ryhm� (GID)<br />Nykyiset oikeudet:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> $_VERSION->PRODUCT.' J�rjestelm�n tiedot',
	'sisysteminfo'		=> 'J�rjestelm�n tiedot',
	'sibuilton'			=> 'Operating System',
	'sidbversion'		=> 'Database Version (MySQL)',
	'siphpversion'		=> 'PHP Version',
	'siphpupdate'		=> 'INFORMATION: <span style="color: red;">The PHP version you use is <strong>not</strong> actual!</span><br />To guarantee all functions and features of '.$_VERSION->PRODUCT.' and addons,<br />you should use as minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - PHP Interface',
	'simamboversion'	=> $_VERSION->PRODUCT.' Version',
	'siuseragent'		=> 'Browser Version',
	'sirelevantsettings' => 'Important PHP Settings',
	'sisafemode'		=> 'Safe Mode',
	'sibasedir'			=> 'Open basedir',
	'sidisplayerrors'	=> 'PHP Errors',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'Datei Uploads',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Session Savepath',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML enabled',
	'sizlibenabled'		=> 'ZLIB enabled',
	'sidisabledfuncs'	=> 'Non enabled functions',
	'sieditor'			=> 'WYSIWYG-editori',
	'siconfigfile'		=> 'Config file',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'K�ytt�oikeudet',
	'sidirperms'		=> 'Hakemiston k�ytt�oikeudet',
	'sidirpermsmess'	=> 'To be shure that all functions and features of '.$_VERSION->PRODUCT.' are working correct, following folders should have permission to write [chmod 0777]',
	'sionoff'			=> array( 'Pois', 'P��ll�' ),
	
	'extract_warning' => "Haluatko purkaa tiedoston t�nne? \\nK�yt� toimintoa varoen sill� olemassa olevat tiedostot ylikirjoitetaan.",
	'extract_success' => "Tiedoston purkaminen onnistui",
	'extract_failure' => "Purkaminen ep�onnistui",
	
	'overwrite_files' => 'Overwrite existing file(s)?',
	"viewlink"		=> "VIEW",
	"actview"		=> "Showing source of file",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Recurse into subdirectories?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Check for latest version',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Rename a directory or file...',
	'newname'		=>	'New Name',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Return to directory after saving?',
	'line'		=> 	'Line',
	'column'	=>	'Column',
	'wordwrap'	=>	'Wordwrap: (IE only)',
	'copyfile'	=>	'Copy file into this filename',
	
	// Bookmarks
	'quick_jump' => 'Quick Jump To',
	'already_bookmarked' => 'This directory is already bookmarked',
	'bookmark_was_added' => 'This directory was added to the bookmark list.',
	'not_a_bookmark' => 'This directory is not a bookmark.',
	'bookmark_was_removed' => 'This directory was removed from the bookmark list.',
	'bookmarkfile_not_writable' => "Failed to %s the bookmark.\n The Bookmark File '%s' \nis not writable.",
	
	'lbl_add_bookmark' => 'Add this Directory as Bookmark',
	'lbl_remove_bookmark' => 'Remove this Directory from the Bookmark List',
	
	'enter_alias_name' => 'Please enter the alias name for this bookmark',
	
	'normal_compression' => 'normal compression',
	'good_compression' => 'good compression',
	'best_compression' => 'best compression',
	'no_compression' => 'no compression',
	
	'creating_archive' => 'Creating Archive File...',
	'processed_x_files' => 'Processed %s of %s Files',
	
	'ftp_header' => 'Local FTP Authentication',
	'ftp_login_lbl' => 'Please enter the login credentials for the FTP server',
	'ftp_login_name' => 'FTP User Name',
	'ftp_login_pass' => 'FTP Password',
	'ftp_hostname_port' => 'FTP Server Hostname and Port <br />(Port is optional)',
	'ftp_login_check' => 'Checking FTP connection...',
	'ftp_connection_failed' => "The FTP server could not be contacted. \nPlease check that the FTP server is running on your server.",
	'ftp_login_failed' => "The FTP login failed. Please check the username and password and try again.",
		
	'switch_file_mode' => 'Current mode: <strong>%s</strong>. You could switch to %s mode.',
	'symlink_target' => 'Target of the Symbolic Link',
	
	"permchange"		=> "CHMOD Success:",
	"savefile"		=> "The File was saved.",
	"moveitem"		=> "Moving succeeded.",
	"copyitem"		=> "Copying succeeded.",
	'archive_name' 	=> 'Name of the Archive File',
	'archive_saveToDir' 	=> 'Save the Archive in this directory',
	
	'editor_simple'	=> 'Simple Editor Mode',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted Mode'
);
?>