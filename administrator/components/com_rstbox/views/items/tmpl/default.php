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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();

?>

<div class="rstbox rstbox-items">
    <form action="<?php echo JRoute::_('index.php?option=com_rstbox&view=items'); ?>" method="post" name="adminForm" id="adminForm">
        <?php
            echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>

        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th class="center" width="2%"><?php echo JHtml::_('grid.checkall'); ?></th>
                    <th class="center" width="7%"><?php echo JText::_('JSTATUS'); ?></th>
                    <th><?php echo JText::_('NN_TITLE'); ?></th>
                    <th width="15%"><?php echo JText::_('COM_RSTBOX_ITEM_FIELD_TYPE'); ?></th>
                    <th width="15%"><?php echo JText::_('COM_RSTBOX_ITEM_TRIGGER'); ?></th>
                    <th width="15%"><?php echo JText::_('COM_RSTBOX_ASSIGN_IMPRESSIONS'); ?></th>
                    <th width="5%"><?php echo JText::_('COM_RSTBOX_ID'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($this->items)) { ?>
                    <?php foreach($this->items as $i => $item): ?>
                        <?php 
                            $canChange  = $user->authorise('core.edit.state', 'com_rstbox.item.' . $item->id);
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
                            <td class="center">
                                <div class="btn-group">
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'items.', $canChange); ?>
                                    <?php
                                    if ($canChange)
                                    {
                                        JHtml::_('actionsdropdown.' . ((int) $item->published === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'items');
                                        JHtml::_('actionsdropdown.' . 'duplicate', 'cb' . $i, 'items');
                                               
                                        echo JHtml::_('actionsdropdown.render', $this->escape($item->name));
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_rstbox&task=item.edit&id='.$item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>"><?php echo RstboxHelper::pretty($this->escape($item->name)); ?>
                                </a>
                                <?php if (RstboxHelper::boxHasCookie($item->id)) { ?>
                                    <span class="label label-important hasTooltip" title="<?php echo JText::_("COM_RSTBOX_HIDDEN_BY_COOKIE_DESC") ?>">
                                        <?php echo JText::_("COM_RSTBOX_HIDDEN_BY_COOKIE") ?>
                                    </span>     
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-mini" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="javascript://" onclick="listItemTask('cb<?php echo $i; ?>', 'items.removeCookie')">
                                                    <span class="icon-trash"></span> <?php echo JText::_("COM_RSTBOX_REMOVE_COOKIE") ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>

                                <?php if ($item->testmode) { ?>
                                    <span class="label hasTooltip" title="<?php echo JTEXT::_("COM_RSTBOX_ITEM_TESTMODE_DESC") ?>">
                                        <?php echo JText::_("COM_RSTBOX_ITEM_TESTMODE") ?>
                                    </span>
                                <?php } ?>

                                <div class="small"><?php echo (isset($item->params->note)) ? $item->params->note : "" ?></div>
                            </td>
                            <td><?php echo ucfirst($item->boxtype) ?></td>
                            <td>
                                <?php echo RstboxHelper::pretty($item->triggermethod) ?> /
                                <?php echo RstboxHelper::pretty($item->position) ?>
                            </td>
                            <td>
                                <?php if ($item->impressions > 0) { ?>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default dropdown-toggle btn-mini" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="javascript://" onclick="listItemTask('cb<?php echo $i; ?>', 'items.reset')">
                                                <span class="icon-refresh"></span> <?php echo JText::_("COM_RSTBOX_RESET_STATISTICS") ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <span class="badge badge-info hasTooltip" title="<?php echo JText::sprintf("COM_RSTBOX_TOTAL_IMPRESSIONS", $item->impressions); ?>">
                                    <?php echo $item->impressions; ?>
                                </span>

                                <?php } else { ?>

                                <span class="badge hasTooltip" title="<?php echo JText::sprintf("COM_RSTBOX_TOTAL_IMPRESSIONS", $item->impressions); ?>">
                                    <?php echo $item->impressions; ?>
                                </span>

                                <?php } ?>
                            </td>
                            <td><?php echo $item->id ?></td>
                        </tr>
                    <?php endforeach; ?>  
                <?php } else { ?>
                    <tr>
                        <td align="center" colspan="7">
                            <div align="center"><?php echo JText::_('COM_RSTBOX_ERROR_NO_BOXES') ?></div>
                        </td>
                    </tr>
                <?php } ?>        
            </tbody>
            <tfoot>
    			<tr><td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td></tr>
            </tfoot>
        </table>
        <div>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
    <?php include_once(JPATH_COMPONENT_ADMINISTRATOR."/layouts/footer.php"); ?>
</div>