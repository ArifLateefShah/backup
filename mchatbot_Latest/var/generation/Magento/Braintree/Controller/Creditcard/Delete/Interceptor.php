<?php
namespace Magento\Braintree\Controller\Creditcard\Delete;

/**
 * Interceptor class for @see \Magento\Braintree\Controller\Creditcard\Delete
 */
class Interceptor extends \Magento\Braintree\Controller\Creditcard\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Braintree\Model\Vault $vault, \Magento\Braintree\Model\Config\Cc $config, \Magento\Customer\Model\Url $customerUrl)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $resultPageFactory, $vault, $config, $customerUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
