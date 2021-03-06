<?php
/**
 * @package modules.list
 */
class list_ExportItemsAction extends f_action_BaseAction
{	
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
    {
		$list = $this->getDocumentInstanceFromRequest($request);
		
		$items = $list->getItems();
		$fieldNames = array(
			f_Locale::translateUI('&modules.list.bo.general.Item-label;'),
			f_Locale::translateUI('&modules.list.bo.general.Item-value;')
		);
		$rows = array();
		foreach ($items as $item)
		{
			$rows[] = array($item->getLabel(), $item->getValue());
		}
		
		$fileName = "export_items_for_list_".f_util_FileUtils::cleanFilename($list->getLabel()).'_'.date('Ymd_His').'.csv';
		$options = new f_util_CSVUtils_export_options();
		$options->separator = ";";
		
		$csv = f_util_CSVUtils::export($fieldNames, $rows, $options);		
		header("Content-type: text/comma-separated-values");
		header('Content-length: '.strlen($csv));
		header('Content-disposition: attachment; filename="'.$fileName.'"');
		echo $csv;
		exit;
    }
}