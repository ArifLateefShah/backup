<?php

namespace Ideas2it\ideastalk\Observer;

use Magento\Framework\Event\ObserverInterface;

class IdeasBot implements ObserverInterface
{
   public function execute(\Magento\Framework\Event\Observer $observer)
   {
       $content = '';
       $content=$content.'<html><head> <style>iframe{min-height: 300px !important; background-color:white;}</style></head><body> <div class="fb-customerchat" page_id="403654120069307" > </div><script>window.fbAsyncInit=function(){FB.init({appId : "320936888398517", autoLogAppEvents : true, xfbml : true, version : "v2.11"});}; (function(d, s, id){var js, fjs=d.getElementsByTagName(s)[0]; if (d.getElementById(id)){return;}js=d.createElement(s); js.id=id; js.src="https://connect.facebook.net/en_US/sdk.js"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script></body></html>';

		echo $content;

		$ch = curl_init("https://api.dialogflow.com/v1/query?v=20150910&e=WELCOME&timezone=Europe/Paris&lang=en&sessionId=1234567890");
		
		curl_setopt($ch, 'ece2c3a010434f39b7cdb37d7ce0c605', 0);
		curl_exec($ch);
		curl_close($ch);


   }
}

