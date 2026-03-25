<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mosAkostaff extends mosDBTable {
  var $id=null;
  var $catid=null;
  var $name=null;
  var $position=null;
  var $nick=null;
  var $tel=null;
  var $fax=null;
  var $email=null;
  var $website=null;
  var $place=null;
  var $age=null;
  var $biography=null;
  var $hits=null;
  var $picture=null;
  var $staff_in=null;
  var $staff_out=null;
  var $status=null;
  var $published=null;
  var $ordering=null;
  var $checked_out=null;

  function mosAkostaff( &$db ) {
    $this->mosDBTable( '#__sygmund', 'id', $db );
  }
}
?>