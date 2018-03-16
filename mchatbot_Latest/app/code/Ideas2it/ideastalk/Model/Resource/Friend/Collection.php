<?php
namespace Ideas2it\ideastalk\Model\Resource\Friend;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Ideas2it\ideastalk\Model\Friend',
            'Ideas2it\ideastalk\Model\Resource\Friend'
        );
    }
}
