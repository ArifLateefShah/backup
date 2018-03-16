<?php
namespace Magento\Braintree\Controller\PayPal\Review;

/**
 * Interceptor class for @see \Magento\Braintree\Controller\PayPal\Review
 */
class Interceptor extends \Magento\Braintree\Controller\PayPal\Review implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Braintree\Model\Config\PayPal $braintreePayPalConfig, \Magento\Paypal\Model\Config $paypalConfig, \Magento\Braintree\Model\CheckoutFactory $checkoutFactory, \Magento\Framework\Json\Helper\Data $jsonHelper)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $checkoutSession, $braintreePayPalConfig, $paypalConfig, $checkoutFactory, $jsonHelper);
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
