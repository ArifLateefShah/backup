<?php

namespace Ideas2it\ideastalk\Model;
// namespace Ideas2it\ideastalk\Model\Friend;
include 'app/bootstrap.php';

class Test implements \Ideas2it\ideastalk\Api\TestInterface {

	/**
	 * Define model & resource model
	 */
	protected $_storeManager;

	protected function _construct(
		\Magento\Store\Model\StoreManagerInterface $storeManager

	) {
		$this->_storeManager = $storeManager;
		$this->_init(
			' Ideas2it\ideastalk\Model\Friend',
			' Ideas2it\ideastalk\Model\Resource\Friend',
			' Ideas2it\ideastalk\Model\currentProduct',
			' Ideas2it\ideastalk\Model\Resource\currentProduct',
			' \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory',
			' \Magento\Catalog\Helper\Category $category',
			' \Magento\Catalog\Model\CategoryRepository $categoryRepository'

		);
	}
	/**
	 * Test function
	 *
	 * @api
	 * @return string
	 */

	public function test() {
		$response = new \stdClass();
		$response->speech = " test";
		$response->displayText = "west";
		$response->source = "source";

		$this->sendMessage($response);
		die;
		// $welcome = array(
		// 	'status' => 'success',
		// 	'message' => 'This response is from Test Model of Ideas2it/ideastalk',
		// );
		// return $welcome;
	}

	/**
	 * Info function
	 *
	 * @api
	 * @id $id string
	 * @return string
	 */

	public function info() {
		header('Content-Type: application/json');
		$update_response = file_get_contents("php://input");
		$update = json_decode($update_response, true);
		// print_r($update);
		// die;
		// echo '<br>';
		// print_r($update["pid"]);die;
		if (isset($update["result"]["resolvedQuery"])) {
			$this->processMessage($update);
		} else {
			die('Invalid Request');
		}
	}

	/**
	 * Test function
	 *
	 * @api
	 * @return string
	 */
	public function processMessage($update) {
		$userFbId = "1926000507428698";
		$fbAcessToken = "EAAEj4ZCE6crUBAEFAllzKpaZCevbLmyWCYMIhEZBeu5mISDfVNpRwHUD31dBZCoQmWfD7NMlBRiq98VbTC8404InFWJabF10WHzadbXFk9J6n8E8E7ZBhHamldfP4PyTzehUpUx6pdS4yu3OZBztqKNmndGKLEIpK9WnkkGZAY9ME24LM2ed1UDAADK1fzkUO0ZD";
		// $ch = curl_init("https://graph.facebook.com/v2.6/" . $userFbId . "?access_token=" . $fbAcessToken);

		// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// $userInfo = curl_exec($ch);
		// $userInfo = json_decode($userInfo);
		// $userFirstName = $userInfo->first_name;
		// $userLastName = $userInfo->last_name;

		// $answer = "You are here for Long time. Can I help you?";

		//
		// $test = $this->getProductDetails(67);
		// die;
		// k

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		if (!$connection) {
			die("Connection failed: " . mysqli_connect_error());
		} else {
			$user_id = "ideas.mv.7";
			if ($update["result"]["action"] == "startChat") {
				$sql = "SELECT MAX( session_id ) as max_id FROM bot_user_queries";
				$result = $connection->fetchAll($sql);
				if ($result["0"]["max_id"] > 0) {
					$chatSession = intval($result["0"]["max_id"]) + 1;
				} else {
					$chatSession = 100001;
				}

				if ($update["result"]["resolvedQuery"]["eventPayload"]) {
					$currentProductId = $update["result"]["resolvedQuery"]["pid"];
					$oSaveCurrentProduct = $this->saveCurrentProduct($currentProductId, $user_id);
					// print_r($oSaveCurrentProduct);
					if (isset($oSaveCurrentProduct)) {
						$response = '{
						  "recipient":{
						    "id":"' . $userFbId . '"
						  },"message":{
							    "text": "Hey! I just wanted to let you know that there’s a great discount on this product.Use CHAT20 coupon on checkout.!",
							    "quick_replies":[
							      {
							        "content_type":"text",
							        "title":"Similar Products",
							        "payload":"<POSTBACK_PAYLOAD>"

							      },{
							        "content_type":"text",
							        "title":"Help",
							        "payload":"<POSTBACK_PAYLOAD>"

							      },{
							        "content_type":"text",
							        "title":"Color & Size",
							        "payload":"<POSTBACK_PAYLOAD>"

							      }
							    ]
							  }
							}';

						$url = "https://graph.facebook.com/v2.6/me/messages?access_token=$fbAcessToken";
						$ch = curl_init($url);

						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
						curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

						curl_exec($ch);
						$errors = curl_error($ch);
						$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

						var_dump($errors);
						var_dump($errors);
						curl_close($ch);
						die;
					}
				} else {
					$userFirstName = "Arif";
					$userLastName = "Shah";

					$orderInfo = "Hello " . $userFirstName . ' ' . $userLastName . ".
				I am Ideas-Talk, your virtual assistant.How can I help you today ?
				Did the Overall Customer Experience Meet your Expectations?";

					$logData = array(
						"bot_reply" => $orderInfo,
						"user_answer" => $update["result"]["resolvedQuery"],
						"user_id" => $user_id,
						"action" => $update["result"]["action"],
						"user_session_id" => $chatSession,
					);
				}

				if ($this->saveChatLog($logData)) {
					$response = new \stdClass();
					$response->speech = " " . $orderInfo;
					$response->displayText = $userLastName;
					$response->source = $update["result"]["source"];

					$this->sendMessage($response);
					die;
				}

			} else {
				$chatSessionQuery = "(SELECT session_id FROM bot_user_queries t WHERE t.id = (SELECT MAX(tmp.id) FROM bot_user_queries tmp WHERE tmp.user_id ='" . $user_id . "'))";

				$aChatSessionResult = $connection->fetchAll($chatSessionQuery);
				$chatSession = $aChatSessionResult["0"]["session_id"];
			}

			if ($update["result"]["action"] == "sendMeorderDetails") {
				$orderId = $update["result"]["parameters"]["orderId"];
				$OrderDetailResults = $this->getOrderDetails($orderId);
				if ($OrderDetailResults) {
					$orderInfo = "Your order details are:
Product Name :" . $OrderDetailResults['product_name'] . "

Price: " . $OrderDetailResults["orderAmount"] . "

Status :" . $OrderDetailResults["orderStatus"];

					$logData = array(
						"bot_reply" => $orderInfo,
						"user_answer" => $update["result"]["resolvedQuery"],
						"user_id" => $user_id,
						"action" => $update["result"]["action"],
						"user_session_id" => $chatSession,
					);

					if ($this->saveChatLog($logData)) {
						$orderDetails = array(
							'productName' => $OrderDetailResults['product_name'],
							'productPrice' => $OrderDetailResults["orderAmount"],
							'productImage' => $OrderDetailResults["orderImageUrl"],
							'productStatus' => "Price : " . $OrderDetailResults["orderAmount"] . "
						 	 Status: " . $OrderDetailResults["orderStatus"],
						);

						$orderDetails = json_encode($orderDetails);

						$response = new \stdClass();
						$response->speech = "Your order details are :";
						$response->displayText = "Your order id is:" . $orderId;
						$response->source = $update["result"]["source"];
						$response->data = $orderDetails;
						$this->sendMessage($response);
						die;
					} else {
						echo "something is wrong";
					}
				} else {
					$logData = array(
						"bot_reply" => $orderId . " is an Invalid OrderId. Please check",
						"user_answer" => $update["result"]["resolvedQuery"],
						"user_id" => $user_id,
						"action" => $update["result"]["action"],
						"user_session_id" => $chatSession,
					);
					if ($this->saveChatLog($logData)) {
						$response = new \stdClass();
						$response->speech = $orderId . " is an Invalid OrderId. Please check";
						$response->displayText = $orderId . " is an Invalid OrderId. Please check";
						$response->source = $update["result"]["source"];
						$this->sendMessage($response);
						die;
					}
				}
			} elseif ($update["result"]["action"] == "getSimilarProducts") {
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
				$connection = $resource->getConnection();
				if (!$connection) {
					die("Connection failed: " . mysqli_connect_error());
				} else {
					$currentProductIdQuery = "SELECT product_id FROM bot_current_product WHERE user_id = '$user_id' ";
					$result = $connection->fetchAll($currentProductIdQuery);
					if ($result["0"]["product_id"]) {
						$currentProductId = $result["0"]["product_id"];
						$similarProducts = $this->getSimilarProducts($currentProductId);

						if (isset($similarProducts) && !empty($similarProducts)) {

							$similarProducts = json_encode($similarProducts);
							$response = new \stdClass();
							$response->speech = "Here are similar products.You need any further help";
							// $response->displayText = "";
							$response->source = $update["result"]["source"];
							$response->data = $similarProducts;
							$this->sendMessage($response);
							die;
						}

					}
				}
			} else {
				$logData = array(
					"bot_reply" => $update["result"]["fulfillment"]["messages"]["0"]["speech"],
					"user_answer" => $update["result"]["resolvedQuery"],
					"user_id" => $user_id,
					"action" => $update["result"]["action"],
					"user_session_id" => $chatSession,
				);

				if ($this->saveChatLog($logData)) {
					$response = new \stdClass();
					$response->success = true;
					$this->sendMessage($response);
					die;
				}
			}
		}

	}

	public function getOrderDetails($orderId) {
		try {

			$productName = null;
			$orderSku = null;
			$orderStatus = null;
			$orderAmount = null;
			$productId = null;

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId);

			$orderItems = $order->getAllItems();

			$amount = $order->getGrandTotal();
			$status = $order->getStatus();

			if (!empty($status)) {
				foreach ($orderItems as $item) {
					$product_name = $item->getName();
					$product_id = $item->getProductId();
					$sku = $item->getsku();
					$imageUrl = $item->getImage();
				}

				if (isset($amount)) {$orderAmount = $amount;}
				if (isset($status)) {$orderStatus = $status;}
				if (isset($product_name)) {$productName = $product_name;}
				if (isset($sku)) {$orderSku = $sku;}
				if (isset($product_id)) {$productId = $product_id;}

				$currentproduct = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
				$orderImageUrl = $currentproduct->getImage();
				$orderImageUrl = $_SERVER['SERVER_NAME'] . '/pub/media/catalog/product' . $orderImageUrl;

				$result = array(
					'product_name' => $productName,
					'sku ' => $orderSku,
					'orderStatus' => $orderStatus,
					'orderAmount' => $orderAmount,
					'orderImageUrl' => $orderImageUrl,
				);

				return $result;
			} else {
				return false;
			}

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function getProductDetails($productId) {
		// echo $productId;
		// die;
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		// $productCollectionFactory = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

		$productCollectionFactory = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
		$productName = $productCollectionFactory->getName();
		$productSku = $productCollectionFactory->getSku();
		$productPrice = $productCollectionFactory->getPrice();

		print_r($productName);
		echo '<br>';
		print_r($productSku);
		echo '<br>';
		print_r($productPrice);
		die;

		$productcollection = $productCollectionFactory->create()
			->addAttributeToSelect('*')
			->load();

		foreach ($productcollection as $product) {
			if ($product->getSpecialPrice() == 0) {
				echo $product->getId() . "<br>";
				echo $product->getSku() . "<br>";
				echo $product->getPrice() . "<br>";
				echo $product->getBasePrice() . "<br>";
				echo $product->getRowTotal() . "<br>";
			}
		}

		die;
	}

	public function getOtherProductColors($productId) {

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$productCollectionFactory = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
		$productcollection = $productCollectionFactory->create()
			->addAttributeToSelect('*')
			->load();

		foreach ($productcollection as $product) {
			if ($product->getSpecialPrice() == 0) {
				echo $product->getId() . "<br>";
				echo $product->getSku() . "<br>";
			}
		}
	}

	public function sendMessage($parameters) {
		echo json_encode($parameters);
	}

	public function saveChatLog($logData) {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$model = $objectManager->create('Ideas2it\ideastalk\Model\Friend');

		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');

		$model->setuser_id($logData['user_id']);
		$model->setuser_answer($logData['user_answer']);
		$model->setbot_reply($logData['bot_reply']);
		$model->setsession_id($logData['user_session_id']);
		$model->setcreated_date($date);
		$model->save();

		return true;
	}

	public function saveCurrentProduct($currentProductId, $user_id) {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$model = $objectManager->create('Ideas2it\ideastalk\Model\currentProduct');
		$connection = $resource->getConnection();
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');

		$oCheckUser = "SELECT product_id FROM bot_current_product WHERE user_id = '$user_id' ";
		$result = $connection->fetchAll($oCheckUser);
		if ($result) {
			$oUpdateCurrentProductId = "UPDATE bot_current_product set product_id = $currentProductId  WHERE user_id = '$user_id' ";
			$oUpdateCurrentProductIdResult = $connection->query($oUpdateCurrentProductId);
			if ($oUpdateCurrentProductIdResult) {
				return true;
			} else {
				return false;
			}
		} else {
			$model->setproduct_id($currentProductId);
			$model->setuser_id($user_id);
			$model->setcreated_date($date);
			$model->save();
		}
		return true;
	}

	/**
	 * Test function
	 *
	 * @api
	 * @param $param string
	 * @return string
	 */
	public function test1($param) {
		return $param;
	}

	public function getCategories($productId) {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$categoryFactory = $objectManager->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
		$categories = $categoryFactory->create()
			->addAttributeToSelect('*'); //categories from current store will be fetched

		foreach ($categories as $category) {
			echo $category->getName();
			echo '<br>';
		}die;
	}
	public function getSimilarProducts($productId) {

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$currentproduct = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
		$categoryId = $currentproduct->getCategoryIds();
		$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
		$category = $categoryFactory->create()->load($categoryId['0']);

		$categoryProducts = $category->getProductCollection()
			->addAttributeToSelect('*');

		$sProducts = array();
		$i = 0;
		foreach ($categoryProducts as $product) {
			$sProducts[$i]["productName"] = $product->getName();
			$sProducts[$i]["productUrl"] = $product->getProductUrl();
			$sProducts[$i]["productImage"] = $_SERVER['SERVER_NAME'] . '/pub/media/catalog/product' . $product->getImage();
			$i++;
		}

		return $sProducts;
	}

}
