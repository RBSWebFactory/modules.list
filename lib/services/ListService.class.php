<?php
/**
 * @date Thu Mar 01 11:15:53 CET 2007
 * @author inthrycn
 */
class list_ListService extends f_persistentdocument_DocumentService
{
	/**
	 * @var list_ListService
	 */
	private static $instance;

	/**
	 * @return list_ListService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @return list_persistentdocument_list
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_list/list');
	}

	/**
	 * Create a query based on 'modules_list/list' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_list/list');
	}

	/**
	 * @exception BaseException if $listId not found
	 * @param string $listId
	 * @return list_persistentdocument_list
	 */
	public function getDocumentInstanceByListId($listId)
	{
		// Check if List exist in database
		$list = $this->getByListId($listId);
		if ($list !== null)
		{
			return $list;
		}
		else
		{
			throw new BaseException('list ' . $listId . ' not found');
		}
	}

	/**
	 * @param string $listId
	 * @return list_persistentdocument_list or null
	 */
	public function getByListId($listId)
	{
		return $this->createQuery()->add(Restrictions::eq('listid', $listId))->findUnique();
	}

	/**
	 * @param list_persistentdocument_list $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function preInsert($document, $parentNodeId)
	{
		if ($document->getListid() == null || $document->getListid() == 'modules_webapp/xxxx')
		{
			$document->setListid('modules_webapp/usr' .  time());
		}
	}

	/**
	 * @param list_persistentdocument_list $document
	 * @return boolean true if the document is publishable, false if it is not.
	 */
	public function isPublishable($document)
	{
		if (parent::isPublishable($document))
		{
			return (count($document->getItems()) > 0);
		}
		return false;
	}

	/**
	 * @param form_persistentdocument_group $document
	 * @param array<string, string> $attributes
	 * @param integer $mode
	 * @param string $moduleName
	 */
	public function completeBOAttributes($document, &$attributes, $mode, $moduleName)
	{
		$attributes['canBeDeleted'] = ($document->canBeDeleted() ? 'true' : 'false');
		if ($mode & DocumentHelper::MODE_CUSTOM)
		{
			$attributes['listid'] = $document->getListid();
			try
			{
				$attributes['nbitems'] = strval($document->countItems());
			}
			catch (Exception $e)
			{
				Framework::exception($e);
				$attributes['nbitems'] = '-';
			}
		}
	}
}