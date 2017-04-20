<?php

defined( '_JEXEC' ) or die;

// Plugin
class plgContentJo_Unique_Alias extends JPlugin
{

  /**
  * @param object  $context  The context of the content passed to the plugin
  * @param object  $article  A reference to the JTableContent object that is 
                             being saved which holds the article data
  * @param boolean  $isNew   A boolean which is set to true if the content is about to be created.
  * @return boolean          returns true, if the article should be stored
  */
  function onContentBeforeSave($context, &$article, $isNew) {
   
   // If article is new, continue. If editing existing article, exit function.
  if(!$isNew){
    return;
  }
   
  $alias = $article->alias;
  $catid = $article->catid;
   
  // Check: alias already exists in db and add "-2", "-3", ... if yes:
  $table = JTable::getInstance('content');
  while ($table->load(array('alias' => $alias)))  {
    /* We asking the db to give us the last article id so we put 
      +1 to the result variable and we get the new id */
      $db = JFactory::getDBO();
      $query = $db->getQuery(true);
      $query->select('id')
       ->from('#__content')
       ->where('id = (SELECT MAX(id)FROM #__content)');
      $db->setQuery($query); 
      $result = $db->loadResult(); 
      
      // Taking the result which is a number and we add +1 to give us the next saved item id 
      $item_id = $result +1 ;
	$alias = JString::increment($alias, 'dash', $item_id);
	break;
  }
  $article->alias = $alias;   
  return true;
  }
}
?>