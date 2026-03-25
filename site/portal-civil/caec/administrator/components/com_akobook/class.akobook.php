<?php
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.3
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze
* @license Released under the terms of the GNU General Public License
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mosAkobook extends mosDBTable {
  var $gbid=null;
  var $gbip=null;
  var $gbname=null;
  var $gbmail=null;
  var $gbloca=null;
  var $gbpage=null;
  var $gbvote=null;
  var $gbtext=null;
  var $gbdate=null;
  var $gbcomment=null;
  var $gbedit=null;
  var $gbeditdate=null;
  var $published=null;
  var $gbicq=null;
  var $gbaim=null;
  var $gbmsn=null;

  function mosAkobook( &$db ) {
    $this->mosDBTable( '#__akobook', 'id', $db );
  }

}
?>