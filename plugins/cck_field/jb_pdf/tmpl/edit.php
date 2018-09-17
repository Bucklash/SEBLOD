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
        * create
        
        * options: Never, Always, Add, Edit
        * override "Never" with some select field i.e. yes=1 no=0]
        * @tip: Similar to Seblod's Email Field
        * @tip: You can designate the value to trigger this plugin
        * @tip: You may require one plugin set with portrait and another plugin set with landscape settings, you could create a select field called 'orientation' with options "Landscape=landscape||Portrait=portrait" and have one pdf plugin with "create_field_value" as landscape and the other plugin with "create_field_value" as portrait
        */
        echo '<label>Create PDF</label>';
        echo '<select id="json_options2_create" name="json[options2][create]" class="inputbox select has-value">
                  <option value="0" selected="selected">Never</option>
                  <option value="3">Always</option>
                  <optgroup label="Workflow">
                      <option value="1">Add</option>
                      <option value="2">Edit</option>
                  </optgroup>
              </select>';
        echo '<input type="text" id="json_options2_create_field" name="json[options2][create_field]" value="" class="inputbox text" placeholder="some_field_to_override" size="14" maxlength="255">';
        echo '<input type="text" id="json_options2_create_field_value" name="json[options2][create_field_value]" value="" class="inputbox text" placeholder="value to look for" size="14" maxlength="255">';


        /*
        * Settings
        *
        * @options: Add your method name and value using a html style tag
        * @example: <tcpdf method="addPageBreak" value="true,10" class="">
        * @tip: add any method, and reference the class if not default value
        * @tip: add as many as you like, these will be initiated before the rendering of the pdf
        * @tip: any method can be added in to the document, these will be applied as they appear, good for when requiring a specific page break
        */
        echo '<label>TCPDF Settings</label>';
        echo '<select id="json_options2_settings" name="json[options2][settings]" class="inputbox select has-value">
                  <option value="0" selected="selected">Never</option>
                  <option value="3">Always</option>
                  <optgroup label="Workflow">
                      <option value="1">Add</option>
                      <option value="2">Edit</option>
                  </optgroup>
              </select>';
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
});
</script>
