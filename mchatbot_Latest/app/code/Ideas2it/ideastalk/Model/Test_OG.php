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

		$welcome = array(
			'status' => 'success',
			'message' => 'This response is from Test Model of Ideas2it/ideastalk',
		);
		return $welcome;
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
				$userFbId = $update["originalRequest"]["data"]["sender"]["id"];
				$fbAcessToken = "EAAEj4ZCE6crUBAEDcc4yC7LLOIWY0bDZBtCvVlrSuRo1Lqe6hbOA8dzO3LExRIDEW8ZBrRC4ZBaAwxdke6rMlUZBhQeBM955iUtpjfbiXyvAqBnxUQpswhEbKlAXWbxgNfTV9QZBFyHNrAq8GpHs2L1bBERRtDNlr6ZBZCpGY5MmHONtwzZAUzahJTIMP1nDSrJsZD";
				$ch = curl_init("https://graph.facebook.com/v2.6/" . $userFbId . "?access_token=" . $fbAcessToken);

				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$userInfo = curl_exec($ch);
				$userInfo = json_decode($userInfo);
				$userFirstName = $userInfo->first_name;
				$userLastName = $userInfo->last_name;

				$orderInfo = "Hello " . $userFirstName . ' ' . $userLastName . ".
I am Ideas-Talk, your virtual assistant.How can I help you today ?


Did the Overall Customer Experience Meet your Expectations?
";

				$logData = array(
					"bot_reply" => $orderInfo,
					"user_answer" => $update["result"]["resolvedQuery"],
					"user_id" => $user_id,
					"action" => $update["result"]["action"],
					"user_session_id" => $chatSession,
				);

				if ($this->saveChatLog($logData)) {
					$response = new \stdClass();
					$response->speech = $orderInfo;
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
						$response = new \stdClass();
						$response->speech = $orderInfo;
						$response->displayText = "Your order id is:" . $orderId;
						$response->source = $update["result"]["source"];
						$response->speech = $orderInfo;
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

	public function getProductDetails($orderId) {
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

	public function getCategories() {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$categoryFactory = $objectManager->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
		$categories = $categoryFactory->create()
			->addAttributeToSelect('*'); //categories from current store will be fetched

		foreach ($categories as $category) {
			echo $category->getName();
			echo '<br>';
		}die;
	}
}
