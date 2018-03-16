<?php
namespace Magento\SalesRule\Model\ResourceModel\Rule;

/**
 * Interceptor class for @see \Magento\SalesRule\Model\ResourceModel\Rule
 */
class Interceptor extends \Magento\SalesRule\Model\ResourceModel\Rule implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Framework\Stdlib\StringUtils $string, \Magento\SalesRule\Model\ResourceModel\Coupon $resourceCoupon, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $string, $resourceCoupon, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function loadCustomerGroupIds(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadCustomerGroupIds');
        if (!$pluginInfo) {
            return parent::loadCustomerGroupIds($object);
        } else {
            return $this->___callPlugins('loadCustomerGroupIds', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadWebsiteIds(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadWebsiteIds');
        if (!$pluginInfo) {
            return parent::loadWebsiteIds($object);
        } else {
            return $this->___callPlugins('loadWebsiteIds', func_get_args(), $pluginInfo);
        }
    }
}
