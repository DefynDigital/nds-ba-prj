<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class nrFrameworkAssignmentsContent extends NRAssignment
{
	/**
	 *  Article Object
	 *
	 *  @var  object
	 */
	private $article;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 */
	public function __construct($assignment)
	{
		parent::__construct($assignment);
		$this->getItem();
	}

	/**
	 *  Pass check for Joomla! Articles
	 *
	 *  @return  bool
	 */
	public function passArticles()
	{
		if (!$this->request->id || !(($this->request->option == 'com_content' && $this->request->view == 'article')))
		{
			return false;
		}

		return $this->passSimple($this->request->id, $this->selection);
	}

	/**
	 *  Pass check for Joomla! Categories
	 *
	 *  @return  bool
	 */
	public function passCategories()
	{
		$is_content  = $this->request->option == 'com_content' ? true : false;
		$is_category = $this->request->view == 'category' ? true : false;
		$is_item     = in_array($this->request->view, array('', 'article', 'item', 'form'));

		// Check if we have a valid context
		if ($is_content != 'com_content')
		{
			return false;
		}

		// Check if we have a valid selection
		if (empty($this->selection))
		{
			return false;
		}

		$this->params->inc_categories = false;
		$this->params->inc_articles   = false;
		$this->params->inc_children   = $this->params->assign_contentcats_param_inc_children;

		if (isset($this->params->assign_contentcats_param_inc) && is_array($this->params->assign_contentcats_param_inc))
		{
			$this->params->inc_categories = in_array('inc_categories', $this->params->assign_contentcats_param_inc);
			$this->params->inc_articles   = in_array('inc_articles', $this->params->assign_contentcats_param_inc);
		}

		// Check we have a valid context
		if (!($this->params->inc_categories && $is_category) && !($this->params->inc_articles && $is_item))
		{
			return false;
		}

		$pass = false;

		$catids = $this->getCategoryIds($is_category);

		foreach ($catids as $catid)
		{
			if (!$catid)
			{
				continue;
			}

			$pass = in_array($catid, $this->selection);

			// Pass check on child items only
			if ($pass && $this->params->inc_children == 2)
			{
				$pass = false;
				continue;
			}

			// Pass check for child items
			if (!$pass && $this->params->inc_children)
			{
				$parent_ids = $this->getParentIDs($catid);
				$parent_ids = array_diff($parent_ids, array('1'));

				foreach ($parent_ids as $id)
				{
					if (in_array($id, $this->selection))
					{
						$pass = true;
						break;
					}
				}

				unset($parent_ids);
			}
		}

		return $pass;
	}

	/**
	 *  Returns category IDs based on active view
	 *
	 *  @param   boolean  $is_category  The current view is a category view
	 *
	 *  @return  array                  The IDs
	 */
	private function getCategoryIds($is_category = false)
	{
		// If we are in category view return category's id
		if ($is_category)
		{
			return (array) $this->request->id;
		}

		// If we are in article view return article's category id
		if ($this->article && $this->article->catid)
		{
			return (array) $this->article->catid;
		}

		return false;
	}

	/**
	 *  Get current Joomla article object
	 *
	 *  @return  object
	 */
	public function getItem()
	{
		if ($this->article)
		{
			return $this->article;
		}

		// Check we have right context
		if ($this->request->option != 'com_content' || $this->request->view != 'article' || !$this->request->id)
		{
			return false;
		}

		if (!class_exists('ContentModelArticle'))
		{
			require_once JPATH_SITE . '/components/com_content/models/article.php';
		}

		$model = JModelLegacy::getInstance('article', 'contentModel');

		if (!method_exists($model, 'getItem'))
		{
			return null;
		}

		try
		{
			$this->article = $model->getItem($this->request->id);
		}
		catch (JException $e)
		{
			return null;
		}

		return $this->article;
	}
}
