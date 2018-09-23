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
$options2   =   JCckDev::fromJSON( $this->item->options2 );
$to_admin   =   ( is_array( @$options2['to_admin'] ) ) ? implode( ',', $options2['to_admin'] ) : ( ( @$options2['to_admin'] ) ? $options2['to_admin'] : '' );
?>

<div class="seblod">
    <?php echo JCckDev::renderLegend( JText::_( 'COM_CCK_CONSTRUCTION' ), JText::_( 'PLG_CCK_FIELD_'.$this->item->type.'_DESC' ) ); ?>
    <ul class="adminformlist adminformlist-2cols">
        <?php
        
        /*
        *
        * create_select
        * create_field
        * create_field_trigger
        *
        * @options: Never, Always, Add, Edit
        * @tip: Similar to Seblod's Email Field
        * @tip: create_field overrides "Never", i.e. use a select field with yes=1 no=0
        * @tip: create_field_trigger references the trigger for create_field
        * @example: create_field references select field called "orientation" which has the options "Landscape=landscape||Portrait=portrait".
        * @example: create_field_trigger set as "landscape" as it's value, so when "orientation" equals "landscape", the pdf plugin is triggered.
        * @example: you could then have another pdf plugin with create_field_trigger set as "portrait".
        *
        */
        echo '<label>Create PDF</label>';
        echo '<select id="json_options2_create_select" name="json[options2][create_select]" class="inputbox select has-value">
                  <option value="0" selected="selected">Never</option>
                  <option value="3">Always</option>
                  <optgroup label="Workflow">
                      <option value="1">Add</option>
                      <option value="2">Edit</option>
                  </optgroup>
              </select>';
        echo '<input type="text" id="json_options2_create_field" name="json[options2][create_field]" value="" class="inputbox text" placeholder="some_field_to_override" size="14" maxlength="255">';
        echo '<input type="text" id="json_options2_create_field_trigger" name="json[options2][create_field_trigger]" value="" class="inputbox text" placeholder="value to look for" size="14" maxlength="255">';

        /*
        *
        * location
        *
        * @options: Text [$user,$uri,$fields,#field_name#]
        * @tip: Enter location to store pdf manually or designate a field etc
        * @tip: Idea is to be able to have storeage location as dynamic as possible
        * @tip: Aim to have $user,$uri,$fields available to use within any string
        *
        */
        echo '<label>Location PDF</label>';
        echo '<input type="text" id="json_options2_location" name="json[options2][location]" value="" class="inputbox text" placeholder="some location to store pdf" size="14" maxlength="255">';

        
   
        /*
        *
        * tcpdf
        *
        * @options: Text 
        * @tip: Enter location and name of tcpdf.php file
        * @example: /libraries/tcpdf/tcpdf.php
        *
        */
        echo '<label>Location TCPDF</label>';
        echo '<input type="text" id="json_options2_location_tcpdf" name="json[options2][location_tcpdf]" value="" class="inputbox text" placeholder="location of tcpdf.php" size="14" maxlength="255">';

        
        /*
        * TODO
        * location_override_select
        * location_override_format
        *
        * Looking to use Seblod's SEF alias creation stuff i.e. append with -1, -2, -n, whatever is next in sequence
        * @options: Yes,No
        * @tip: If PDF with that name exists in that location, what do you want to do?
        * @tip: If yes, replace existing
        * @tip: If No, save with alteration as selected in location_override_format
        *
        */

        echo '<select id="json_options2_location_override_select" name="json[options2][location_override_select]" class="inputbox select has-value">
                  <option value="0" selected="selected">JNo</option>
                  <option value="1">JYes</option>
              </select>';
        

        echo '<select id="json_options2_location_override_format" name="json[options2][location_override_format]" class="inputbox select has-value">
                  <option value="0" selected="selected"></option>
                  <option value="1"></option>
              </select>';        
        
        /*
        * settings
        *
        * @options: Add your method name and value using a html style tag
        * @example: <tcpdf method="addPageBreak" value="true,10" class="">
        * @tip: add any method, and reference the class if not default value
        * @tip: add as many as you like, these will be initiated before the rendering of the pdf
        * @tip: any method can be added in to the document, these will be applied as they appear, good for when requiring a specific page break
        */
        echo '<label>TCPDF Settings</label>';
        echo '<textarea id="json_options2_settings" name="json[options2][settings]" value="" class="inputbox text" placeholder="&lt;tcpdf method="addPageBreak" value="true,10" class=""&gt;" col="50" rows="10">';
        
        
        /*
        * Header
        *
        * @tip: same functionality as Seblod's Email Message field
        */
        echo '<label>Header</label>';
        echo '<textarea id="json_options2_header" name="json[options2][header]" value="" class="inputbox text" col="50" rows="10">';
        
        
        /*
        * Body
        *
        * @tip: same functionality as Seblod's Email Message field
        */
        echo '<label>Body</label>';
        echo '<textarea id="json_options2_body" name="json[options2][body]" value="" class="inputbox text" col="50" rows="10">';

        
                
        /*
        * Footer
        *
        * @tip: same functionality as Seblod's Email Message field
        */
        echo '<label>Footer</label>';
        echo '<textarea id="json_options2_footer" name="json[options2][footer]" value="" class="inputbox text" col="50" rows="10">';

        
        
        // Add link to tutorial on forum i.e. https://www.seblod.com/community/forums/fields-plug-ins/pdf-plugin
        // RenderHelp forces a dodgy link so need to hardcode or do something else
        echo JCckDev::renderHelp( 'field', 'pdf-plugin' );
        echo JCckDev::renderSpacer( JText::_( 'COM_CCK_STORAGE' ), JText::_( 'COM_CCK_STORAGE_DESC' ) );
        echo JCckDev::getForm( 'core_storage', $this->item->storage, $config );
        ?>
    </ul>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    // If Never is selected, then show override field
    $('#json_options2_create_field').isVisibleWhen('json_options2_create','0',true,'visibility');
    $('#json_options2_create_field_value').isVisibleWhen('json_options2_create','0',true,'visibility');
    $('#json_options2_location_override_format').isVisibleWhen('json_options2_location_override_select','1',true,'visibility');
    });
</script>
