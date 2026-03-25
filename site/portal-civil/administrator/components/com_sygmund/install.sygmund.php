<?
function com_install() {
  global $database;

  $database->setQuery( "UPDATE #__components"
  ." SET admin_menu_link='option=categories&section=com_sygmund'"
  ." WHERE admin_menu_link = 'option=com_sygmund&act=categories'");
  if (!$database->query()) {
    echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    exit();
  }
  echo "Corrected category menu link... <b>OK</b><br />";

  echo "<p><b>SygMund Installed Successfully!</b></p>";

}
?>