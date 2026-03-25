<?/**
* @version $Id: admin.cursos.html.php,v 1.22 2004/09/21 16:36:46 stingrey Exp $
* @package Mambo_4.5.1
* @copyright (C) 2000 - 2004 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
	function myDateConvert($timestamp)
	 { 
		 $year = substr($timestamp, 0, 4); 
		 $month = substr($timestamp, 5, 2); 
		 $day = substr($timestamp, 8, 2); 
		
		 return $day.'/'.$month.'/'.$year; 
	 }

/**
* @package Mambo_4.5.1
*/
class HTML_cursos {

	function showCursoss( $option, &$rows, &$lists, &$search, &$pageNav ) {
		global $my;
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Cursos Manager
			</th>
			<td>
			Filter:
			</td>
			<td>
			<input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
			<td width="right">
			<? echo $lists['catid'];?>
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<? echo count( $rows ); ?>);" />
			</th>
			
			<!-- ------------------- -->
			<!-- 1 of 6 Change/Edit your column titles to display -->
			<!-- ------------------- -->
			
			<th class="title" nowrap="nowrap">
			Curso			</th>
			<th class="title" nowrap="nowrap">
			Ministrado			</th>
			<th class="title" nowrap="nowrap">
			Inicia			</th>
			<th class="title" nowrap="nowrap">
			Termina			</th>
			<!-- ------------------- -->
			<!-- The following can stay as they are -->
			<!-- ------------------- -->
			
			<th width="10%" nowrap="nowrap">
			Published
			</th>
			<th colspan="2">
			Reorder
			</th>
			<th width="25%" align="center" nowrap="nowrap">
			Category
			</th>
			<th width="10%" nowrap="nowrap">
			Checked Out
			</th>
		</tr>
		<?
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			$task = $row->published ? 'unpublish' : 'publish';
			$img = $row->published ? 'publish_g.png' : 'publish_x.png';
			$alt = $row->published ? 'Published' : 'Unpublished';
			?>
			<tr class="<? echo "row$k"; ?>">
				<td width="20">
				<? echo mosHTML::idBox( $i, $row->id, ($row->checked_out && $row->checked_out != $my->id ) ); ?>
				</td>
				
				<!-- ------------------- -->
				<!-- 2 of 6 Change the following display values to match your header -->
				<!-- ------------------- -->
				
				<td>
				<?
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					?>
					<? echo $row->Curso; ?>
					&nbsp;[ <i>Checked Out</i> ]
					<?
				} else {
					?>
					<a href="#edit" onclick="return listItemTask('cb<? echo $i;?>','edit')">
					<? echo $row->Curso; ?>
					</a>
					<?
				}
				?>
				</td>
				<td>
				<?
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					?>
					<? echo $row->Ministrado; ?>
					&nbsp;[ <i>Checked Out</i> ]
					<?
				} else {
					?>
					<a href="#edit" onclick="return listItemTask('cb<? echo $i;?>','edit')">
					<? echo $row->Ministrado; ?>
					</a>
					<?
				}
				?>
				</td>
				<td>
				<?
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					?>
					<? echo myDateConvert($row->Inicia); ?>
					&nbsp;[ <i>Checked Out</i> ]
					<?
				} else {
					?>
					<a href="#edit" onclick="return listItemTask('cb<? echo $i;?>','edit')">
					<? echo myDateConvert($row->Inicia); ?>
					</a>
					<?
				}
				?>
				</td>
				<td>
				<?
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					?>
					<? echo myDateConvert($row->Termina); ?>
					&nbsp;[ <i>Checked Out</i> ]
					<?
				} else {
					?>
					<a href="#edit" onclick="return listItemTask('cb<? echo $i;?>','edit')">
					<? echo myDateConvert($row->Termina); ?>
					</a>
					<?
				}
				?>
				</td>
							
				<!-- ------------------- -->
				<!-- The following can stay as they are -->
				<!-- ------------------- -->
				
				<td width="10%" align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<? echo $i;?>','<? echo $task;?>')">
				<img src="images/<? echo $img;?>" width="12" height="12" border="0" alt="<? echo $alt; ?>" />
				</a>
				</td>
				<td>
				<? echo $pageNav->orderUpIcon( $i, ($row->catid == @$rows[$i-1]->catid) ); ?>
				</td>
      			<td>
				<? echo $pageNav->orderDownIcon( $i, $n, ($row->catid == @$rows[$i+1]->catid) ); ?>
				</td>
				<td width="25%" align="center">
				<? echo $row->category; ?>
				</td>
				<?
				if ( $row->checked_out ) { 
					?>
					<td width="10%" align="center"><? echo $row->editor; ?></td>
					<?		
				} else { 
					?>
					<td width="10%" align="center">&nbsp;</td>
					<?		
				} 
				?>
			</tr>
			<?			
			$k = 1 - $k; 
		} 
		?>
		</table>
		<? echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="<? echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?
	}



function showArchivedCursoss( $option, &$rows, &$lists, &$search, &$pageNav ) {
		global $my;
		?>
		<form action="index2." method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Archived Cursos Manager
			</th>
			<td>
			Filter:
			</td>
			<td>
			<input type="text" name="search" value="<? echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
			<td width="right">
			<? echo $lists['catid'];?>
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<? echo count( $rows ); ?>);" />
			</th>
			
			<!-- -------------------
			<!-- 3 of 6 Change/Edit your column titles to display
			<!-- ------------------- -->
			
			<th class="title" nowrap="nowrap">
			Curso			</th>
			<th class="title" nowrap="nowrap">
			Ministrado			</th>
			<th class="title" nowrap="nowrap">
			Inicia			</th>
			<th class="title" nowrap="nowrap">
			Termina			</th>
			
			<!-- -------------------
			<!-- The following can stay as they are
			<!-- ------------------- -->
			
			<th width="10%" nowrap="nowrap">
			Published
			</th>
			<th colspan="2">
			Reorder
			</th>
			<th width="25%" align="center" nowrap="nowrap">
			Category
			</th>
			<th width="10%" nowrap="nowrap">
			Checked Out
			</th>
		</tr>
		<?
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			$task = $row->published ? 'unpublish' : 'publish';
			$img = $row->published ? 'publish_g.png' : 'publish_x.png';
			$alt = $row->published ? 'Published' : 'Unpublished';
			?>
			<tr class="<? echo "row$k"; ?>">
				<td width="20">
				<? echo mosHTML::idBox( $i, $row->id, ($row->checked_out && $row->checked_out != $my->id ) ); ?>
				</td>
				
				<!-- -------------------
				<!-- 4 of 6 Change the following display values to match your header
				<!-- ------------------- -->
				
				<td width="50%">
				<?
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					?>
					<? echo $row->Curso; ?>
					&nbsp;[ <i>Checked Out</i> ]
					<?
				} else {
					?>
					<a href="#edit" onclick="return listItemTask('cb<? echo $i;?>','edit')">
					<? echo $row->Curso; ?>
					</a>
					<?
				}
				?>
				<?
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					?>
					<? echo $row->Ministrado; ?>
					&nbsp;[ <i>Checked Out</i> ]
					<?
				} else {
					?>
					<a href="#edit" onclick="return listItemTask('cb<? echo $i;?>','edit')">
					<? echo $row->Ministrado; ?>
					</a>
					<?
				}
				?>
				<?
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					?>
					<? echo myDateConvert($row->Inicia); ?>
					&nbsp;[ <i>Checked Out</i> ]
					<?
				} else {
					?>
					<a href="#edit" onclick="return listItemTask('cb<? echo $i;?>','edit')">
					<? echo myDateConvert($row->Inicia); ?>
					</a>
					<?
				}
				?>
				<?
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					?>
					<? echo myDateConvert($row->Termina); ?>
					&nbsp;[ <i>Checked Out</i> ]
					<?
				} else {
					?>
					<a href="#edit" onclick="return listItemTask('cb<? echo $i;?>','edit')">
					<? echo myDateConvert($row->Termina); ?>
					</a>
					<?
				}
				?>
				<!-- -------------------
				<!-- The following can stay as they are
				<!-- ------------------- -->
				
				<td width="10%" align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<? echo $i;?>','<? echo $task;?>')">
				<img src="images/<? echo $img;?>" width="12" height="12" border="0" alt="<? echo $alt; ?>" />
				</a>
				</td>
				<td>
				<? echo $pageNav->orderUpIcon( $i, ($row->catid == @$rows[$i-1]->catid) ); ?>
				</td>
      			<td>
				<? echo $pageNav->orderDownIcon( $i, $n, ($row->catid == @$rows[$i+1]->catid) ); ?>
				</td>
				<td width="25%" align="center">
				<? echo $row->category; ?>
				</td>
				<?
				if ( $row->checked_out ) { 
					?>
					<td width="10%" align="center"><? echo $row->editor; ?></td>
					<?		
				} else { 
					?>
					<td width="10%" align="center">&nbsp;</td>
					<?		
				} 
				?>
			</tr>
			<?			
			$k = 1 - $k; 
		} 
		?>
		</table>
		<? echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="<? echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?
	}



/**
* Writes the edit form for new and existing record
*
* A new record is defined when <var>$row</var> is passed with the <var>id</var>
* property set to 0.
* @param mosCursos The cursos object
* @param array An array of select lists
* @param string The option
*/
	function editCursos( &$row, &$lists, $option ) {
		mosMakeHtmlSafe( $row, ENT_QUOTES, 'description' );
		?>
		<link rel="stylesheet" type="text/css" media="all" href="../includes/js/calendar/calendar-mos.css" title="green" />
		<!-- import the calendar script -->
		<script type="text/javascript" src="../includes/js/calendar/calendar.js"></script>
		<!-- import the language module -->
		<script type="text/javascript" src="../includes/js/calendar/lang/calendar-en.js"></script>
		
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}


			
			/*** ***********************
			/**  5 of 6 Edit for your field validation needs
			/*** *********************** */
			
			if (form.Curso.value == ""){
				alert( "Cursos item must have a Curso" );
			} else if (form.Inicia.value == ""){
				alert( "Cursos item must have a Inicia" );
			} else if (form.Termina.value == ""){
				alert( "Cursos item must have a Termina" );
			} else if (form.Ministrado.value == ""){
				alert( "Cursos item must have a Ministrado" );
			} else if (form.Local.value == ""){
				alert( "Cursos item must have a Local" );
			} else if (form.Valor.value == ""){
				alert( "Cursos item must have a Valor" );
			} else if (form.Pagamento.value == ""){
				alert( "Cursos item must have a Pagamento" );
			} else if (form.Vagas.value == ""){
				alert( "Cursos item must have a Vagas" );
			} else if (form.InscreveEm.value == ""){
				alert( "Cursos item must have a InscreveEm" );
			} else if (form.Descreve.value == ""){
				alert( "Cursos item must have a Descreve" );
			} else {
				/*
				/** Edit this area to write your edit area back to the database
				/*   */
				submitform( pressbutton );
			}
		}
		</script>
		<table class="adminheading">
		<tr>
			<th>
			<? echo $row->id ? 'Edit' : 'Add';?> Cursos
			</th>
		</tr>
		</table>

		<form action="index2.php" method="post" name="adminForm" id="adminForm">

		<!-- ------------------- -->
		<!-- 6 of 6 Change the following input form to capture all your values -->
		<!-- ------------------- -->
		
		<table class="adminform">
		<tr>
			<td width="20%" align="right">
			Curso:
			</td>
			<td width="80%">
			<input class="text_area" type="text" name="Curso" size="50" maxlength="50" value="<? echo $row->Curso;?>" />
			</td>
		</tr>
		<tr>
			<td valign="top" align="right">
			Inicia (YYYY-MM-DD):
			</td>
			<td>
			<input class="text_area" type="text" name="Inicia" id="Inicia" size="15" maxlength="15" value="<? echo myDateConvert($row->Inicia); ?>" />
			<input type="reset" class="button" value="..." onClick="return showCalendar('Inicia', 'y-mm-dd');">
			</td>
		</tr>
		<tr>
			<td valign="top" align="right">
			Termina (YYYY-MM-DD):
			</td>
			<td>
			<input class="text_area" type="text" name="Termina" id="Termina" size="15" maxlength="15" value="<? echo myDateConvert($row->Termina); ?>" />
			<input type="reset" class="button" value="..." onClick="return showCalendar('Termina', 'y-mm-dd');">
			</td>
		</tr>
		<tr>
			<td width="20%" align="right">
			Ministrante:
			</td>
			<td width="80%">
			<input class="text_area" type="text" name="Ministrado" size="50" maxlength="50" value="<? echo $row->Ministrado;?>" />
			</td>
		</tr>
		<tr>
			<td width="20%" align="right">
			Local:
			</td>
			<td width="80%">
			<input class="text_area" type="text" name="Local" size="50" maxlength="50" value="<? echo $row->Local;?>" />
			</td>
		</tr>
		<tr>
			<td width="20%" align="right">
			Valor:
			</td>
			<td width="80%">
			<input class="text_area" type="text" name="Valor" size="20" maxlength="20" value="<? echo $row->Valor;?>" />
			</td>
		</tr>
		<tr>
			<td width="20%" align="right">
			Pagamento:
			</td>
			<td width="80%">
			<input class="text_area" type="text" name="Pagamento" size="50" maxlength="50" value="<? echo $row->Pagamento;?>" />
			</td>
		</tr>
		<tr>
			<td width="20%" align="right">
			Vagas:
			</td>
			<td width="80%">
			<input class="text_area" type="text" name="Vagas" size="50" maxlength="50" value="<? echo $row->Vagas;?>" />
			</td>
		</tr>
		<tr>
			<td width="20%" align="right">
			Inscrever Em:
			</td>
			<td width="80%">
			<input class="text_area" type="text" name="InscreveEm" size="50" maxlength="50" value="<? echo $row->InscreveEm;?>" />
			</td>
		</tr>
		<tr>
			<td width="20%" align="right">
			Descri&ccedil;&atilde;o::
			</td>
			<td width="80%">
			  <textarea name="Descreve" cols="50" rows="10" class="text_area"><? echo $row->Descreve;?></textarea>
			</td>
		</tr>
		<!-- ------------------- -->
		<!-- The following can stay as they are -->
		<!-- ------------------- -->

           <tr>
                   <td valign="top" align="right">
                   Categoria:
                   </td>
                   <td>
                  <? echo $lists['catid']; ?>
                       </td>
           </tr>
		<tr>
			<td valign="top" align="right">
			Ordem:
			</td>
			<td>
			<? echo $lists['ordering']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right">
			Publicado:
			</td>
			<td>
			<? echo $lists['published']; ?>
			</td>
		</tr>
		</table>

		<input type="hidden" name="id" value="<? echo $row->id; ?>" />
		<input type="hidden" name="option" value="<? echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?
	}
}
?>

