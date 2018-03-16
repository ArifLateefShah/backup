<?php
namespace Magento\Catalog\Block\Adminhtml\Product\Helper\Form\BaseImage;

/**
 * Interceptor class for @see
 * \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\BaseImage
 */
class Interceptor extends \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\BaseImage implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Data\Form\Element\Factory $factoryElement, \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection, \Magento\Framework\Escaper $escaper, \Magento\Framework\View\Asset\Repository $assetRepo, \Magento\Backend\Model\UrlFactory $backendUrlFactory, \Magento\Catalog\Helper\Data $catalogData, \Magento\Framework\File\Size $fileConfig, \Magento\Framework\View\LayoutInterface $layout, array $data = array())
    {
        $this->___init();
        parent::__construct($factoryElement, $factoryCollection, $escaper, $assetRepo, $backendUrlFactory, $catalogData, $fileConfig, $layout, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function assignBlockVariables(\Magento\Framework\View\Element\Template $block)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'assignBlockVariables');
        if (!$pluginInfo) {
            return parent::assignBlockVariables($block);
        } else {
            return $this->___callPlugins('assignBlockVariables', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createElementHtmlOutputBlock()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createElementHtmlOutputBlock');
        if (!$pluginInfo) {
            return parent::createElementHtmlOutputBlock();
        } else {
            return $this->___callPlugins('createElementHtmlOutputBlock', func_get_args(), $pluginInfo);
        }
    }
}
