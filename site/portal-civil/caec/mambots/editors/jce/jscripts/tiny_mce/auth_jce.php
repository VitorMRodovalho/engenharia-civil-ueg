<?php
/**
 * General Authorisation script for mosCE plugins.
 *@author $Author: Ryan Demmer $
 * @version $Id: auth_mce.php 26 2004-12-24 18:24:00 $
 * @package mosCE
 * @Portions from remository.php
 */

// Don't allow direct linking
defined( '_VALID_MOS' ) or die( 'Restricted Access.' );
class authJCE
{
    function authJCE()
    {
            global $my, $database;
            
			$query = "SELECT gid"
			. "\n FROM #__users"
			. "\n WHERE id = '".$my->id."' LIMIT 1";
			;
			$database->setQuery( $query );
			$gid = $database->loadResult();
						          
			$this->id = $gid;            
            $this->usertype = $my->usertype;
            $this->username = $my->username;
    }
    function getUserName()
    {
        return $this->username;
    }
    function getUserType()
    {
        return $this->usertype;
    }
    function isAdmin()
    {
        return ( strtolower( $this->usertype ) == 'superadministrator' || strtolower( $this->usertype ) == 'super administrator' || $this->id == 25 ) ? true : false;
    }
}

?>
