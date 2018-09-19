
<?php
/**
* @version 			SEBLOD 3.x Core
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				https://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2009 - 2018 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

/**
* @version          JB Pdf
*
*
*
**/

defined( '_JEXEC' ) or die;
// Plugin
class plgCCK_FieldJBPDF extends JCckPluginField
{
	protected static $type		=	'jb_pdf';
	protected static $path;
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Construct
	
	// onCCK_FieldConstruct
	public function onCCK_FieldConstruct( $type, &$data = array() )
	{
		if ( self::$type != $type ) {
			return;
		}
		parent::g_onCCK_FieldConstruct( $data );
	}
  
  // -------- -------- -------- -------- -------- -------- -------- -------- // Prepare
	
	// onCCK_FieldPrepareContent
	public function onCCK_FieldPrepareContent( &$field, $value = '', &$config = array() )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		parent::g_onCCK_FieldPrepareContent( $field, $config );
		
		$field->value	=	$value;
	}
  
  
	// onCCK_FieldPrepareForm
	public function onCCK_FieldPrepareForm( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		self::$path	=	parent::g_getPath( self::$type.'/' );
		parent::g_onCCK_FieldPrepareForm( $field, $config );
		
		// Init
		if ( count( $inherit ) ) {
			$id		=	( isset( $inherit['id'] ) && $inherit['id'] != '' ) ? $inherit['id'] : $field->name;
			$name	=	( isset( $inherit['name'] ) && $inherit['name'] != '' ) ? $inherit['name'] : $field->name;
		} else {
			$id		=	$field->name;
			$name	=	$field->name;
		}
		$value		=	( $value != '' ) ? $value : $field->defaultvalue;
		$value		=	( $value != ' ' ) ? $value : '';
		$value		=	str_replace(array( '"','\\' ), '', $value );
		$value		=	htmlspecialchars( $value, ENT_COMPAT, 'UTF-8' );
		
		// Validate
		$validate	=	'';
		if ( $config['doValidation'] > 1 ) {
			plgCCK_Field_ValidationRequired::onCCK_Field_ValidationPrepareForm( $field, $id, $config );
			parent::g_onCCK_FieldPrepareForm_Validation( $field, $id, $config );
			$validate	=	( count( $field->validate ) ) ? ' validate['.implode( ',', $field->validate ).']' : '';
		}
		
		// Prepare
		$class	=	'inputbox text'.$validate . ( $field->css ? ' '.$field->css : '' );
		$maxlen	=	( $field->maxlength > 0 ) ? ' maxlength="'.$field->maxlength.'"' : '';
		$attr	=	'class="'.$class.'" size="'.$field->size.'"'.$maxlen . ( $field->attributes ? ' '.$field->attributes : '' );
		$form	=	'<input type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$attr.' />';
		
		// Set
		if ( ! $field->variation ) {
			$field->form	=	$form;
			if ( $field->script ) {
				parent::g_addScriptDeclaration( $field->script );
			}
		} else {
			parent::g_getDisplayVariation( $field, $field->variation, $value, $value, $form, $id, $name, '<input', '', '', $config );
		}
		$field->value	=	$value;
		
		// Return
		if ( $return === true ) {
			return $field;
		}
	}
  
  
	// onCCK_FieldPrepareSearch
	public function onCCK_FieldPrepareSearch( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		
		// Prepare
		self::onCCK_FieldPrepareForm( $field, $value, $config, $inherit, $return );
		
		// Return
		if ( $return === true ) {
			return $field;
		}
	}
  
  
	// onCCK_FieldPrepareStore
	public function onCCK_FieldPrepareStore( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		
		// Init
		if ( count( $inherit ) ) {
			$name	=	( isset( $inherit['name'] ) && $inherit['name'] != '' )	? $inherit['name'] : $field->name;
		} else {
			$name	=	$field->name;
		}
		$options2	=	JCckDev::fromJSON( $field->options2 );
		$siteName	=	JFactory::getConfig()->get( 'sitename' );
		$valid		=	0;
		$send		=	( isset( $options2['send'] ) && $field->state != 'disabled' ) ? $options2['send'] : 0;
		$send_field	=	( isset( $options2['send_field'] ) && strlen( $options2['send_field'] ) > 0 ) ? $options2['send_field'] : 0;
		$isNew		=	( $config['pk'] ) ? 0 : 1;
		$sender		=	0;
		switch ( $send ) {
			case 0:
				$sender	=	0;
				break;
			case 1:
				if ( !$config['pk'] ) {
					$sender	=	1;
				}
				break;
			case 2:
				if ( $config['pk'] ) {
					$sender	=	1;
				}
				break;
			case 3:
				$sender	=	1;
				break;
		}
		$subject	=	( isset( $options2['subject'] ) && $options2['subject'] ) ? $options2['subject'] : $siteName . '::' . JText::_( 'COM_CCK_EMAIL_GENERIC_SUBJECT' );
		$message	=	( isset( $options2['message'] ) && $options2['message'] ) ? htmlspecialchars_decode($options2['message']) : JText::sprintf( 'COM_CCK_EMAIL_GENERIC_MESSAGE', $siteName );
		$message	=	( strlen( $options2['message'] ) > 0 ) ? htmlspecialchars_decode($options2['message']) : JText::sprintf( 'COM_CCK_EMAIL_GENERIC_MESSAGE', $siteName );
		$new_message	=	( strlen( $options2['message_field'] ) > 0 ) ? $options2['message_field'] : '';
		$dest					=	array();
		$from					=	( isset( $options2['from'] ) ) ? $options2['from'] : 0;
		$from_param				=	( isset( $options2['from_param'] ) ) ? $options2['from_param'] : '';
		$from_name				=	( isset( $options2['from_name'] ) ) ? $options2['from_name'] : 0;
		$from_name_param		=	( isset( $options2['from_name_param'] ) ) ? $options2['from_name_param'] : '';
		$reply_to				=	( isset( $options2['reply_to'] ) ) ? $options2['reply_to'] : 0;
		$reply_to_param			=	( isset( $options2['reply_to_param'] ) ) ? $options2['reply_to_param'] : '';
		$reply_to_name			=	( isset( $options2['reply_to_name'] ) ) ? $options2['reply_to_name'] : 0;
		$reply_to_name_param	=	( isset( $options2['reply_to_name_param'] ) ) ? $options2['reply_to_name_param'] : '';
		$cc						=	( isset( $options2['cc'] ) ) ? $options2['cc'] : 0;
		$cc_param				=	( isset( $options2['cc_param'] ) ) ? $options2['cc_param'] : '';
		$bcc					=	( isset( $options2['bcc'] ) ) ? $options2['bcc'] : 0;
		$bcc_param				=	( isset( $options2['bcc_param'] ) ) ? $options2['bcc_param'] : '';
		$moredest				=	( isset( $options2['to_field'] ) ) ? $options2['to_field'] : '';
		$send_attach			=	( isset( $options2['send_attachment_field'] ) && strlen( $options2['send_attachment_field'] ) > 0 ) ? $options2['send_attachment_field'] : 1;
		$moreattach				=	( isset( $options2['attachment_field'] ) && strlen( $options2['attachment_field'] ) > 0 ) ? $options2['attachment_field'] : '';
		
		// Prepare
		if ( isset( $options2['to'] ) && $options2['to'] != '' ) {
			$to		=	self::_split( $options2['to'] );
			$dest	=	array_merge( $dest, $to );
			$valid	=	1;
		}
		if ( $moredest ) {
			$valid	=	1;
		}
		if ( isset( $options2['to_admin'] ) && $options2['to_admin'] != '' ) {
			$to_admin	=	( count( $options2['to_admin'] ) ) ? implode( ',', $options2['to_admin'] ) : $options2['to_admin'];
			if ( strpos( $to_admin, ',' ) !== false ) {
				$recips = explode( ',', $to_admin );
				foreach ( $recips as $recip ) {
					$recip_mail = JCckDatabase::loadResult( 'SELECT email FROM #__users WHERE block=0 AND id='.$recip );
					if ( $recip_mail ) {
						$dest[]	=	$recip_mail;
						$valid	=	1;
					}
				}
			} else {
				$recip_mail = JCckDatabase::loadResult( 'SELECT email FROM #__users WHERE block=0 AND id='.$to_admin );
				if ( $recip_mail ) {
					$dest[]	=	$recip_mail;
					$valid	=	1;
				}
			}
		}
		if ( $value ) {
			/* TODO#SEBLOD: check multiple if () */
			$m_value		=	self::_split( $value );
			$m_value_size	=	count( $m_value );
			if ( $m_value_size > 1 ) {
				for ( $i = 0; $i < $m_value_size; $i++ )
					$dest[]	= 	$m_value[$i];
			} else {
				$dest[]	= 	$value;
			}
			$valid	=	1;
		}
		
		// Validate
		parent::g_onCCK_FieldPrepareStore_Validation( $field, $name, $value, $config );
		
		// Add Process
		if ( ( $sender || $send_field ) && $valid ) {
			parent::g_addProcess( 'afterStore', self::$type, $config, array( 'isNew'=>$isNew, 'sender'=>$sender, 'send_field'=>$send_field, 'name'=>$name, 'valid'=>$valid, 'subject'=>$subject, 'message'=>$message, 'new_message'=>$new_message, 'dest'=>$dest, 'from'=>(int)$from, 'from_param'=>$from_param, 'from_name'=>(int)$from_name, 'from_name_param'=>$from_name_param, 'reply_to'=>(int)$reply_to, 'reply_to_param'=>$reply_to_param, 'reply_to_name'=>(int)$reply_to_name, 'reply_to_name_param'=>$reply_to_name_param, 'cc'=>(int)$cc, 'cc_param'=>$cc_param, 'bcc'=>(int)$bcc, 'bcc_param'=>$bcc_param, 'moredest'=>$moredest, 'send_attach'=>$send_attach, 'moreattach'=>$moreattach, 'format'=>@(string)$options2['format'] ) );
		}
		
		// Set or Return
		if ( $return === true ) {
			return $value;
		}
		$field->value	=	$value;
		parent::g_onCCK_FieldPrepareStore( $field, $name, $value, $config );
	}
