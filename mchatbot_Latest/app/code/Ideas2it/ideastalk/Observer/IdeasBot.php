<?php

namespace Ideas2it\ideastalk\Observer;

use Magento\Framework\Event\ObserverInterface;

class IdeasBot implements ObserverInterface {
	public function execute(\Magento\Framework\Event\Observer $observer) {
		$product = $observer->getData('product');
		$cproduct = $product->getName();
		$sku = $product->getSKU();
		$productId = $product->getId();

		$content = '';
		$content = $content . '<html><head> <style>iframe{min-height: 0px !important; background-color:white;}</style></head><body> <div class="fb-customerchat" page_id="403654120069307" > </div><script>window.fbAsyncInit=function(){FB.init({appId : "320936888398517", autoLogAppEvents : true, xfbml : true, version : "v2.11"});}; (function(d, s, id){var js, fjs=d.getElementsByTagName(s)[0]; if (d.getElementById(id)){return;}js=d.createElement(s); js.id=id; js.src="https://connect.facebook.net/en_US/sdk.js"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script></body></html>';

		echo $content;
		if (0 == 0) {

			$ch = curl_init("http://magentobot.ideas2it.com/index.php/rest/V1/webhook");

			$update = '{
		    "result":{
		        "resolvedQuery":{
		        	"eventPayload":"1",
		        	"pid":' . $productId . '
		        },
		        "action":"startChat"
		    }
		}';
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $update);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$userInfo = curl_exec($ch);
			print_r($userInfo);

		}

	}
}
