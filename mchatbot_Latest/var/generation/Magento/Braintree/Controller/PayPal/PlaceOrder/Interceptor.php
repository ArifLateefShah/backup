<?php
namespace Magento\Braintree\Controller\PayPal\PlaceOrder;

/**
 * Interceptor class for @see \Magento\Braintree\Controller\PayPal\PlaceOrder
 */
class Interceptor extends \Magento\Braintree\Controller\PayPal\PlaceOrder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Braintree\Model\Config\PayPal $braintreePayPalConfig, \Magento\Paypal\Model\Config $paypalConfig, \Magento\Braintree\Model\CheckoutFactory $checkoutFactory, \Magento\Checkout\Api\AgreementsValidatorInterface $agreementsValidator)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $checkoutSession, $braintreePayPalConfig, $paypalConfig, $checkoutFactory, $agreementsValidator);
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
