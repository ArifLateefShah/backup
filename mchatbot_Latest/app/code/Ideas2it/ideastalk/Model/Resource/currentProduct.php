<?php
namespace Ideas2it\ideastalk\Model\Resource;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class currentProduct extends AbstractDb {
	/**
	 * Define table
	 */
	protected function _construct() {
		$this->_init('bot_current_product', 'id'); // bot_current_product is name of table
	}
}
?>
