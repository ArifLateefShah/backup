<?php
namespace Magento\Indexer\Model\Indexer\State;

/**
 * Interceptor class for @see \Magento\Indexer\Model\Indexer\State
 */
class Interceptor extends \Magento\Indexer\Model\Indexer\State implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Indexer\Model\ResourceModel\Indexer\State $resource, \Magento\Indexer\Model\ResourceModel\Indexer\State\Collection $resourceCollection, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
