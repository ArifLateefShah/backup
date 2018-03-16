<?php

namespace Ideas2it\ideastalk\Controller\Webhook;

class Webhook extends \Magento\Framework\App\Action\Action {
	protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory) {
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute() {
		$content = $this->test();
		$resultPage = $this->_pageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend(__($content));

		echo $content;
		// return $resultPage;
	}

	public function test() {
		$b = 0;
		for ($i = 0; $i <= 10; $i++) {
			$b = $i + $b;
		}
		// $content =  $b;
		$content = "This is my content here";

		return $content;

	}
}
