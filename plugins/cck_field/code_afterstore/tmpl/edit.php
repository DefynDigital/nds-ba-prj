<?php
/**
* @version 			SEBLOD 3.x More
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2009 - 2016 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

JCckDev::forceStorage();
$options	=	JCckDev::fromSTRING( $this->item->options );
$options2	=	JCckDev::fromJSON( $this->item->options2 );
?>

<div class="seblod">
    <?php echo JCckDev::renderLegend( JText::_( 'COM_CCK_CONSTRUCTION' ), JText::_( 'PLG_CCK_FIELD_'.$this->item->type.'_DESC' ) ); ?>
    <ul class="adminformlist adminformlist-2cols">
		<?php
		echo JCckDev::renderForm( 'core_bool', $this->item->bool, $config, array( 'label'=>'Mode', 'options'=>'Files=1||Free=0' ) );
		echo JCckDev::renderBlank( '<input type="hidden" id="blank_li" value="" />' );
        echo JCckDev::renderForm( 'core_defaultvalue_textarea', @$options2['code'], $config, array( 'label'=>'AfterStore', 'cols'=>92, 'rows'=>8, 'maxlength'=>0, 'storage_field'=>'json[options2][code]' ), array(), 'w100' );
		echo JCckDev::renderForm( 'core_options', $options, $config, array( 'label'=>'Files' ) );
		
        echo JCckDev::renderSpacer( JText::_( 'COM_CCK_STORAGE' ), JText::_( 'COM_CCK_STORAGE_DESC' ) );
        echo JCckDev::getForm( 'core_storage', $this->item->storage, $config );
        ?>
    </ul>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#json_options2_code').isVisibleWhen('bool','0');
	$('#sortable_core_options,#blank_li').isVisibleWhen('bool','1');
});
</script>