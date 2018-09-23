<?php
/**
* @version          SEBLOD 3.x Core
* @package          SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url              https://www.seblod.com
* @editor           Octopoos - www.octopoos.com
* @copyright        Copyright (C) 2009 - 2018 SEBLOD. All Rights Reserved.
* @license          GNU General Public License version 2 or later; see _LICENSE.php
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
    protected static $type      =   'jb_pdf';
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

        $field->value   =   $value;
    }


    // onCCK_FieldPrepareForm
    public function onCCK_FieldPrepareForm( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
    {
        if ( self::$type != $field->type ) {
            return;
        }
        self::$path =   parent::g_getPath( self::$type.'/' );
        parent::g_onCCK_FieldPrepareForm( $field, $config );

        // Init
        if ( count( $inherit ) ) {
            $id     =   ( isset( $inherit['id'] ) && $inherit['id'] != '' ) ? $inherit['id'] : $field->name;
            $name   =   ( isset( $inherit['name'] ) && $inherit['name'] != '' ) ? $inherit['name'] : $field->name;
        } else {
            $id     =   $field->name;
            $name   =   $field->name;
        }
        $value      =   ( $value != '' ) ? $value : $field->defaultvalue;
        $value      =   ( $value != ' ' ) ? $value : '';
        $value      =   str_replace(array( '"','\\' ), '', $value );
        $value      =   htmlspecialchars( $value, ENT_COMPAT, 'UTF-8' );

        // Validate
        $validate   =   '';
        if ( $config['doValidation'] > 1 ) {
            plgCCK_Field_ValidationRequired::onCCK_Field_ValidationPrepareForm( $field, $id, $config );
            parent::g_onCCK_FieldPrepareForm_Validation( $field, $id, $config );
            $validate   =   ( count( $field->validate ) ) ? ' validate['.implode( ',', $field->validate ).']' : '';
        }

        // Prepare
        $class  =   'inputbox text'.$validate . ( $field->css ? ' '.$field->css : '' );
        $maxlen =   ( $field->maxlength > 0 ) ? ' maxlength="'.$field->maxlength.'"' : '';
        $attr   =   'class="'.$class.'" size="'.$field->size.'"'.$maxlen . ( $field->attributes ? ' '.$field->attributes : '' );
        $form   =   '<input type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$attr.' />';

        // Set
        if ( ! $field->variation ) {
            $field->form    =   $form;
            if ( $field->script ) {
                parent::g_addScriptDeclaration( $field->script );
            }
        } else {
            parent::g_getDisplayVariation( $field, $field->variation, $value, $value, $form, $id, $name, '<input', '', '', $config );
        }
        $field->value   =   $value;

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
            $name   =   ( isset( $inherit['name'] ) && $inherit['name'] != '' ) ? $inherit['name'] : $field->name;
        } else {
            $name   =   $field->name;
        }

        $options2   =   JCckDev::fromJSON( $field->options2 );

        $isNew      =   ( $config['pk'] ) ? 0 : 1;

        // Determine whether we create PDF or not?
        $create_select  =   ( isset( $options2['create_select'] ) && $field->state != 'disabled' ) ? $options2['create_select'] : 0;
        $create_field = ( isset( $options2['create_field'] ) && $field->state != 'disabled' ) ? $options2['create_field'] : '';
        $create_field_trigger   =   ( isset( $options2['create_field_trigger'] ) && $field->state != 'disabled' ) ? $options2['create_field_trigger'] : '';
        $location   =   ( isset( $options2['location'] ) ) ? $options2['location'] : JPATH_SITE.'/'.'images';
        $location_tcpdf   =   ( isset( $options2['location_tcpdf'] ) ) ? $options2['location_tcpdf'] : JPATH_SITE.'/'.'libraries'.'/'.'tcpdf'.'/'.'tcpdf.php';
        $settings   =   ( isset( $options2['settings'] ) ) ? $options2['settings'] : '';
        $header =   ( isset( $options2['header'] ) ) ? $options2['header'] : '';
        $body   =   ( isset( $options2['body'] ) ) ? $options2['body'] : '';
        $footer =   ( isset( $options2['footer'] ) ) ? $options2['footer'] : '';

        $valid      =   0;

        // Prepare
        switch ( $create ) {
            case 0:
            $create_field_trigger = ($create_field_trigger == '') ? 1 : $create_field_trigger;
              $valid = ($fields[$create_field]->value == $create_field_trigger) ? 1 : 0;
                break;

            case 1:
                $valid  =   ($isNew === 1) ? 1 : 0;
                break;

            case 2:
                $valid  =   ($isNew === 0) ? 1 : 0;
                break;

            case 3:
                $valid  =   1;
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
                'location_tcpdf'=>$location_tcpdf,
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
        $field->value   =   $value;
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
        $isNew      =   $process['isNew'];
        $valid      =   $process['valid'];

        if ( $valid )
        {

        }
        if ( !$valid )
        {
            return;
        }

        // create pdf
        self::_tcpdf($process);

    }





    // -------- -------- -------- -------- -------- -------- -------- -------- // Stuff & Script

    // _split
    protected static function _split( $string )
    {
        $string     =   str_replace( array( ' ', "\r" ), '', $string );
        if ( strpos( $string, ',' ) !== false ) {
            $tab    =   explode( ',', $string );
        } else if ( strpos( $string, ';' ) !== false ) {
            $tab    =   explode( ';', $string );
        } else {
            $tab    =   explode( "\n", $string );
        }

        return $tab;
    }



    // _split($strng, 'delimiter')
    protected static function _splitDelimiter( $string, $delimiter = ',' )
    {

        $string     =   str_replace( array( ' ', "\r" ), '', $string );

        $tab    =   explode( $delimiter, $string );

        return $tab;
    }


    // _tcpdf
    protected static function _tcpdf( $data )
    {

        //  require_once('tcpdf_include.php');
        require_once($data['location_tcpdf']);

        // initiate
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // convert tags to useable data
        // from Header, Footer, Settings
        // assign that data to instance i.e. instance->method(param)
        if ( $data['header'] )
        {
            if ( $data['header'] != '' && strpos( $data['header'], '<tcpdf' ) !== false )
            {
                $data['header'] = self::_tcpdfTagToData( $data['header'], $data['delimiter'] );

                // pass params to instance
                // $vaue is array array(class => '', method => 'someMethod', params => array(0,1,2...))
                foreach ($data['header'] as $key => $value)
                {

                    self::_tcpdfParamsBuilder(&$pdf,&$value['method'], &$value['params']);
                }
            }

        }
        if ( $data['footer'] )
        {
            if ( $data['footer'] != '' && strpos( $data['footer'], '<tcpdf' ) !== false )
            {
                $data['footer'] = self::_tcpdfTagToData( $data['footer'], $data['delimiter'] );

                // pass params to instance
                // $vaue is array array(class => '', method => 'someMethod', params => array(0,1,2...))
                foreach ($data['footer'] as $key => $value)
                {

                    self::_tcpdfParamsBuilder(&$pdf,&$value['method'], &$value['params']);
                }
            }

        }
        if ( $data['settings'] )
        {
            if ( $data['settings'] != '' && strpos( $data['settings'], '<tcpdf' ) !== false )
            {
                $data['settings'] = self::_tcpdfTagToData( $data['settings'], $data['delimiter'] );

                // pass params to instance
                // $vaue is array array(class => '', method => 'someMethod', params => array(0,1,2...))
                foreach ($data['settings'] as $key => $value)
                {

                    self::_tcpdfParamsBuilder(&$pdf,&$value['method'], &$value['params']);
                }
            }

        }

        // create the bloody thing
        // have to decide how to create title, and what i I???
        $pdf->Output('some title...hmmmm', 'I');

    }



    // _tcpdfParamsBuilder
    protected static function _tcpdfParamsBuilder( &$pdf,&$method, &$param )
    {

                // Parameters 10 max (should be enough, are there any methods that can take more?)
        switch (count($param))
        {
            case 1:
                $pdf->$method($param[0]);
                break;
            case 2:
                $pdf->$method($param[0],$param[1]);
                break;
            case 3:
                $pdf->$method($param[0],$param[1],$param[2]);
                break;
            case 4:
                $pdf->$method($param[0],$param[1],$param[2],$param[3]);
                break;
            case 5:
                $pdf->$method($param[0],$param[1],$param[2],$param[3],$param[4]);
                break;
            case 6:
                $pdf->$method($param[0],$param[1],$param[2],$param[3],$param[4],$param[5]);
                break;
            case 7:
                $pdf->$method($param[0],$param[1],$param[2],$param[3],$param[4],$param[5],$param[6]);
                break;
            case 8:
                $pdf->$method($param[0],$param[1],$param[2],$param[3],$param[4],$param[5],$param[6],$param[7]);
                break;
            case 9:
                $pdf->$method($param[0],$param[1],$param[2],$param[3],$param[4],$param[5],$param[6],$param[7],$param[8]);
                break;
            case 10:
                $pdf->$method($param[0],$param[1],$param[2],$param[3],$param[4],$param[5],$param[6],$param[7],$param[8],$param[9]);
                break;

            default:
                $pdf->$method($param[0]);
                break;
        }

    }

    // _tcpdfParamsBuilder
    // <tcpdf class="" method="" params="">);
    // becomes array[0]['method'] = someMethod
    // becomes array[0]['params'] = array(param1,param2,...)
    protected static function _tcpdfTagToData( $string, $delimiter )
    {

        $matches    =   '';

        $array = self::_splitDelimiter($string, $delimiter);

        foreach($array as $k => $v)
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
                // split params in to array
                $matches[$k]['params'] = self::_split($match[1]);
            }
        }

        return $matches;
    }

} // END OF PLUGIN
