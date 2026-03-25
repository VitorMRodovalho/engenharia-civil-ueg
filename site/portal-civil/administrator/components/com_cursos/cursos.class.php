
<?
/**
* @version : cursos.class.php,v 1.8 2004/09/27 15:18:21 stingrey Exp $
* @package Mambo_4.5.1
* @copyright (C) 2000 - 2004 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/
/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* Category database table class
* @package Mambo_4.5.1
*/
class Cursos extends mosDBTable {
	/** @var int Primary key */
	var $id=null;
	/** @var int */
	var $catid=null;
	/** @var int */
	var $sid=null;
	
	/** @var string */
	var $Curso=null;
	/** @var date */
	var $Inicia=null;
	/** @var date */
	var $Termina=null;
	/** @var string */
	var $Ministrado=null;
	/** @var string */
	var $Local=null;
	/** @var string */
	var $Valor=null;
	/** @var string */
	var $Pagamento=null;
	/** @var string */
	var $Vagas=null;
	/** @var string */
	var $InscreveEm=null;
	/** @var string */
	var $Descreve=null;
	
	/** @var datetime */
	var $date=null;
	/** @var int */
	var $published=null;
	/** @var boolean */
	var $checked_out=null;
	/** @var time */
	var $checked_out_time=null;
	/** @var int */
	var $ordering=null;
	/** @var int */
	var $archived=null;
	/** @var int */
	var $approved=null;
	/** @var string */
	/** var $params=null; */
	/**
	* @param database A database connector object
	*/
	function Cursos( &$db ) {
		$this->mosDBTable( '#__cursos', 'id', $db );
	}
}
?>
