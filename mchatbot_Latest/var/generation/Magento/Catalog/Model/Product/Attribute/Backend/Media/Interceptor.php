<?php
namespace Magento\Catalog\Model\Product\Attribute\Backend\Media;

/**
 * Interceptor class for @see
 * \Magento\Catalog\Model\Product\Attribute\Backend\Media
 */
class Interceptor extends \Magento\Catalog\Model\Product\Attribute\Backend\Media implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\ResourceModel\ProductFactory $productFactory, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Catalog\Model\Product\Media\Config $mediaConfig, \Magento\Framework\Filesystem $filesystem, \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media $resourceProductAttribute)
    {
        $this->___init();
        parent::__construct($productFactory, $eventManager, $fileStorageDb, $jsonHelper, $mediaConfig, $filesystem, $resourceProductAttribute);
    }

    /**
     * {@inheritdoc}
     */
    public function afterLoad($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterLoad');
        if (!$pluginInfo) {
            return parent::afterLoad($object);
        } else {
            return $this->___callPlugins('afterLoad', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'beforeSave');
        if (!$pluginInfo) {
            return parent::beforeSave($object);
        } else {
            return $this->___callPlugins('beforeSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterSave');
        if (!$pluginInfo) {
            return parent::afterSave($object);
        } else {
            return $this->___callPlugins('afterSave', func_get_args(), $pluginInfo);
        }
    }
}
