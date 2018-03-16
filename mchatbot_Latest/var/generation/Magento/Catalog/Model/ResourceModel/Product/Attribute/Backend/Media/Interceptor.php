<?php
namespace Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media;

/**
 * Interceptor class for @see
 * \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media
 */
class Interceptor extends \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function createBatchBaseSelect($storeId, $attributeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createBatchBaseSelect');
        if (!$pluginInfo) {
            return parent::createBatchBaseSelect($storeId, $attributeId);
        } else {
            return $this->___callPlugins('createBatchBaseSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function duplicate($attributeId, $newFiles, $originalProductId, $newProductId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'duplicate');
        if (!$pluginInfo) {
            return parent::duplicate($attributeId, $newFiles, $originalProductId, $newProductId);
        } else {
            return $this->___callPlugins('duplicate', func_get_args(), $pluginInfo);
        }
    }
}
