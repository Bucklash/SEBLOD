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
		
		$isNew		=	( $config['pk'] ) ? 0 : 1;
		
		// Determine whether we create PDF or not?
		$create_select	=	( isset( $options2['create_select'] ) && $field->state != 'disabled' ) ? $options2['create_select'] : 0;
		$create_field = ( isset( $options2['create_field'] ) && $field->state != 'disabled' ) ? $options2['create_field'] : '';
		$create_field_trigger	=	( isset( $options2['create_field_trigger'] ) && $field->state != 'disabled' ) ? $options2['create_field_trigger'] : '';
		$location	=	( isset( $options2['location'] ) ) ? $options2['location'] : JPATH_SITE.'/'.'images';
		$settings	=	( isset( $options2['settings'] ) ) ? $options2['settings'] : '';
		$header	=	( isset( $options2['header'] ) ) ? $options2['header'] : '';
		$body	=	( isset( $options2['body'] ) ) ? $options2['body'] : '';
		$footer	=	( isset( $options2['footer'] ) ) ? $options2['footer'] : '';
		
		$valid		=	0;
		
		// Prepare
		switch ( $create ) {
			case 0:
  		    $create_field_trigger = ($create_field_trigger == '') ? 1 : $create_field_trigger;
		      $valid = ($fields[$create_field]->value == $create_field_trigger) ? 1 : 0;
				break;

			case 1:
				$valid	=	($isNew === 1) ? 1 : 0;
				break;
				
			case 2:
				$valid	=	($isNew === 0) ? 1 : 0;
				break;
				
			case 3:
				$valid	=	1;
				break;
				
			default:
		      $valid = 0;
				break;
		}
    

		// Validate
		parent::g_onCCK_FieldPrepareStore_Validation( $field, $name, $value, $config );
		
		// Add Process
		if ( $valid ) {
			parent::g_addProcess( 'afterStore', self::$type, $config, array(
			  'isNew'=>$isNew,
        'create_select'=>$create_select,
        'create_field'=>$create_field,
        'create_field_trigger'=>$create_field_trigger,
        'location'=>$location,
        'settings'=>$settings,
        'header'=>$header,
        'body'=>$body,
        'footer'=>$footer,
        'valid'=>$valid
        ));
		}
		
		// Set or Return
		if ( $return === true ) {
			return $value;
		}
		$field->value	=	$value;
		parent::g_onCCK_FieldPrepareStore( $field, $name, $value, $config );
	}

	// -------- -------- -------- -------- -------- -------- -------- -------- // Render
	
	// onCCK_FieldRenderContent
	public static function onCCK_FieldRenderContent( $field, &$config = array() )
	{
		return parent::g_onCCK_FieldRenderContent( $field );
	}
	
	// onCCK_FieldRenderForm
	public static function onCCK_FieldRenderForm( $field, &$config = array() )
	{
		return parent::g_onCCK_FieldRenderForm( $field );
	}
	
	
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Special Events
	
	// onCCK_FieldAfterStore
	public static function onCCK_FieldAfterStore( $process, &$fields, &$storages, &$config = array() )
	{
		$isNew		=	$process['isNew'];
		$valid		=	$process['valid'];

		if ( $valid )
		{

		}
		if ( !$valid )
		{
			return;
		}

    // Get Settings Defined in PDF Field
		if ( $process['settings'] )
		{
			

			// <tcpdf class="" method="" params="">);
			if ( $process['settings'] != '' && strpos( $process['settings'], '<tcpdf' ) !== false )
			{
			  
				$matches	=	'';
				
        $settingsSplit = _split($process['settings']);
				
      	foreach($settingsSplit as $k => $v)
      	{
      	    
          	if (preg_match('/class="(.*?)"/', $v, $match) === 1)
          	{
          	       $matches[$k]['class'] = $match[1];
          	}
      	    
          	if (preg_match('/method="(.*?)"/', $v, $match) === 1)
          	{
          	       $matches[$k]['method'] = $match[1];
          	}
      	    
          	if (preg_match('/params="(.*?)"/', $v, $match) === 1)
          	{
          	       $matches[$k]['params'] = $match[1];
          	}
      
      	}

			}
		}
		
			
			
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Stuff & Script
	
	// _split
	protected static function _split( $string )
	{
		$string		=	str_replace( array( ' ', "\r" ), '', $string );
		if ( strpos( $string, ',' ) !== false ) {
			$tab	=	explode( ',', $string );
		} else if ( strpos( $string, ';' ) !== false ) {
			$tab	=	explode( ';', $string );
		} else {
			$tab	=	explode( "\n", $string );
		}
		
		return $tab;
	}
	

	
	
} // END OF PLUGIN
