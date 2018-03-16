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
   }
}

