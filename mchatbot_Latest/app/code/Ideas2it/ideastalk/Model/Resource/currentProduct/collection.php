<?php
namespace Ideas2it\ideastalk\Model\Resource\currentProduct;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection {
	/**
	 * Define model & resource model
	 */
	protected function _construct() {
		$this->_init(
			'Ideas2it\ideastalk\Model\currentProduct',
			'Ideas2it\ideastalk\Model\Resource\currentProduct'
		);
	}
}
