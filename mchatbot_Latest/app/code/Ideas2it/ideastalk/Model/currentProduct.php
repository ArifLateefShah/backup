<?php
namespace Ideas2it\ideastalk\Model;
use Magento\Framework\Model\AbstractModel;

class currentProduct extends AbstractModel {
	/**
	 * Define resource model
	 */
	protected function _construct() {
		$this->_init('Ideas2it\ideastalk\Model\Resource\currentProduct');
	}
}
?>
