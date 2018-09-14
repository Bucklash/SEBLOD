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
        * jb_pdf_create, jb_pdf_create_field
        * copied from 
        * core_options_send, send_field
        * options: Never, Always, Add, Edit
        * jb_pdf_create_field will reference a user created field to override "Never" i.e. with select field as yes=1 no=0]
        */
        echo '<label>Create PDF</label>
                <select id="json_options2_create" name="json[options2][create]" class="inputbox select has-value">
                    <option value="0" selected="selected">Never</option>
                    <option value="3">Always</option>
                    <optgroup label="Workflow">
                        <option value="1">Add</option>
                        <option value="2">Edit</option>
                    </optgroup>
                </select>';
        echo '<input type="text" id="json_options2_create_field" name="json[options2][create_field]" value="" class="inputbox text" placeholder="some_field_to_override" size="14" maxlength="255">';

        // ** TODO **
        // Decide on which paramaters use
        // How to manage multiple pages

        /*
        * Some of the many Paramaters for TCPDF
        *
        *
        * // set document information
        * $pdf->SetCreator(PDF_CREATOR);
        * $pdf->SetAuthor('Nicola Asuni');
        * $pdf->SetTitle('TCPDF Example 001');
        * $pdf->SetSubject('TCPDF Tutorial');
        * $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        * 
        * // set default header data
        * $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        * $pdf->setFooterData(array(0,64,0), array(0,64,128));
        * 
        * // set header and footer fonts
        * $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        * $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        * 
        * // set default monospaced font
        * $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        * 
        * // set margins
        * $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        * $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        * $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        * 
        * // set auto page breaks
        * $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        * 
        * // set image scale factor
        * $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        * 
        * // set some language-dependent strings (optional)
        * if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        *     require_once(dirname(__FILE__).'/lang/eng.php');
        *     $pdf->setLanguageArray($l);
        * }
        * 
        * // ---------------------------------------------------------
        * 
        * // set default font subsetting mode
        * $pdf->setFontSubsetting(true);
        * 
        * // Set font
        * // dejavusans is a UTF-8 Unicode font, if you only need to
        * // print standard ASCII chars, you can use core fonts like
        * // helvetica or times to reduce file size.
        * $pdf->SetFont('dejavusans', '', 14, '', true);
        * 
        * // Add a page
        * // This method has several options, check the source code documentation for more information.
        * $pdf->AddPage();
        * 
        * // set text shadow effect
        * $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        * 
        * // Set some content to print
        * $html = <<<EOD
        * <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
        * <i>This is the first example of TCPDF library.</i>
        * <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
        * <p>Please check the source code documentation and other examples for further information.</p>
        * <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
        * EOD;
        * 
        * // Print text using writeHTMLCell()
        * $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        * 
        * // ---------------------------------------------------------
        * 
        * // Close and output PDF document
        * // This method has several options, check the source code documentation for more information.
        * $pdf->Output('example_001.pdf', 'I');
        * 
        * 
        */

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
});
</script>
