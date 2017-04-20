<?php

/**
 * @package         Engage Box
 * @version         3.2.0 Pro
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'item.cancel' || document.formvalidator.isValid(document.id('adminForm')))
        {
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    }
</script>

<div class="rstbox rstbox-item form-horizontal">
    <form action="<?php echo JRoute::_('index.php?option=com_rstbox&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm">
        <div class="row-fluid">
            <div class="span9">

                <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_RSTBOX_CONTENT')); ?>

                <div class="row-fluid">
                    <div class="span12">
                        <div class="well boxtype">
                            <?php echo RstboxHelper::renderFormFields($this->form->getFieldset("type")) ?>
                        </div>
                        <div class="boxtypes">
                            <?php foreach ($this->boxtypes as $key => $value) { ?>
                                <div data-showon='[{"field":"jform[boxtype]","values":["<?php echo $value ?>"],"op":""}]'>
                                    <?php echo $this->form->renderFieldset($value); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <div class="clr"></div>
                
                <!-- Trigger Tab -->
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'trigger', JText::_('COM_RSTBOX_TRIGGER')); ?>

                <div class="row-fluid">
                    <div class="span6"><?php echo $this->form->renderFieldset("item1") ?></div>
                    <div class="span6"><?php echo $this->form->renderFieldset("item2") ?></div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <div class="clr"></div>

                <!-- Appearance Tab -->
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'appearance', JText::_('COM_RSTBOX_APPEARANCE')); ?>

                <div class="row-fluid">
                    <div class="span6"><?php echo $this->form->renderFieldset("appearance1") ?></div>
                    <div class="span6"><?php echo $this->form->renderFieldset("appearance2") ?></div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <div class="clr"></div>

                <!-- Publishing Assignments Tab -->
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishingAssignments', JText::_('NR_PUBLISHING_ASSIGNMENTS')); ?>

                <div class="row-fluid">
                    <div class="span12">
                        
                        <div class="well ss">
                            <?php echo RstboxHelper::renderField($this->form->getField('assignmentMatchingMethod')) ?>
                        </div>

                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_menu'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_menu_list')) ?>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_menu_param_noitem')) ?>
                            </div>
                        </div>

                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_usergroups'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_usergroups_list')) ?>
                            </div>
                        </div>

                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_datetime'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_datetime_param_publish_up')) ?>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_datetime_param_publish_down')) ?>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_datetime_note')) ?>
                            </div>
                        </div>
    
                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_devices'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_devices_list')) ?>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_devices_note')) ?>
                            </div>
                        </div>

                        <div class="well well-assign well-assign-group">
                            <label><strong>Joomla! Content</strong></label>
                            <div class="well well-assign">
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_contentarticles'), 1) ?>
                                <div>
                                    <?php echo RstboxHelper::renderField($this->form->getField('assign_contentarticles_list')) ?>
                                </div>
                            </div>
                            <div class="well well-assign">
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_contentcats'), 1) ?>
                                <div>
                                    <?php echo RstboxHelper::renderField($this->form->getField('assign_contentcats_list')) ?>
                                    <?php echo RstboxHelper::renderField($this->form->getField('assign_contentcats_param_inc_children')) ?>
                                    <?php echo RstboxHelper::renderField($this->form->getField('assign_contentcats_param_inc')) ?>
                                </div>
                            </div>
                        </div>

                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_urls'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_urls_list')) ?>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_urls_param_regex')) ?>
                            </div>
                        </div>

                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_referrer'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_referrer_list')) ?>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_referrer_note')) ?>
                            </div>
                        </div>

                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_lang'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_lang_list')) ?>
                            </div>
                        </div>

                        <?php if (NRFrameworkFunctions::extensionInstalled('akeebasubs')) { ?>
                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_akeebasubs'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_akeebasubs_list')) ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (NRFrameworkFunctions::extensionInstalled('convertforms')) { ?>
                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_convertforms'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_convertforms_list')) ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (NRFrameworkFunctions::extensionInstalled('acymailing')) { ?>
                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_acymailing'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_acymailing_list')) ?>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_timeonsite'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_timeonsite_list')) ?>
                            </div>
                        </div>

                        <div class="well well-assign">
                            <?php echo RstboxHelper::renderField($this->form->getField('assign_php'), 1) ?>
                            <div>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_php_list')) ?>
                                <?php echo RstboxHelper::renderField($this->form->getField('assign_php_note')) ?>
                            </div>
                        </div>
                    </div>

                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <div class="clr"></div>

                <!-- Advanced Tab -->
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'advanced', JText::_('NR_ADVANCED')); ?>
   

                <div class="row-fluid">
                    <div class="span12"><?php echo RstboxHelper::renderFormFields($this->form->getFieldset("advanced")) ?></div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <div class="clr"></div>


                <input type="hidden" name="task" value="item.edit" />
                <?php echo JHtml::_('form.token'); ?>

                <?php echo JHtml::_('bootstrap.endTabSet'); ?>
            </div>

            <div class="span3 form-vertical paddingLeft">
                <h4>Details</h4>
                <hr>
                <?php echo RstboxHelper::renderFormFields($this->form->getFieldset("general")) ?>
            </div>
        </div>
    </form>
</div>


