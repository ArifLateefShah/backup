<?php

namespace Ideas2it\ideastalk\Controller\Webhook;

use Ideas2it\ideastalk\Model\FriendFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Index extends Action {
	/**
	 * @var \Ideas2it\ideastalk\Model\FriendFactory
	 */
	protected $_modelFriendFactory;

	/**
	 * @param Context $context
	 * @param FriendFactory $modelFriendFactory
	 */
	public function __construct(
		Context $context,
		FriendFactory $modelFriendFactory
	) {
		parent::__construct($context);
		$this->_modelFriendFactory = $modelFriendFactory;
	}

	public function execute() {
		/**
		 * When Magento get your model, it will generate a Factory class
		 * for your model at var/generaton folder and we can get your
		 * model by this way
		 */
		$friendModel = $this->_modelFriendFactory->create();

		// Load the item with ID is 1
		// $friend = $friendModel->load(1);
		// if($friend){
		//     echo $friend->getid() . " found with Id 1";
		//     echo "<hr/>";
		// }

		// Get friend collection
		// $friendCollection = $friendModel->getCollection(); fetching records from database
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		if (!$connection) {
			die("Connection failed: " . mysqli_connect_error());
		} else {
			$sql = "SELECT GROUP_CONCAT(user_id ORDER BY  id ASC SEPARATOR '#-#') userId,GROUP_CONCAT(CONCAT('User : ', T.user_answer, '<br>Bot : ', T.bot_reply) ORDER BY  id ASC SEPARATOR '<br>') chat, GROUP_CONCAT(DATE_FORMAT(T.created_date,'%d-%m-%Y %H:%i %p') ORDER BY  id ASC SEPARATOR '#-#') date
                FROM (SELECT *
                FROM    bot_user_queries ORDER BY id DESC) AS T
                GROUP BY T.session_id DESC";
			$result = $connection->fetchAll($sql);
		}
		?>
<html>
<head>
<!-- <link rel="stylesheet" type="text/css" href="./assets/style.css"> -->
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.jqueryui.min.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.jqueryui.min.js"></script>
</head>
<style>
body{
    margin:0px;
}
.header{
    height:100px;
    max-height:100px;
    width:auto;
    background-color:#ddd;
    text-align:center;
    padding-top:10px;
    margin:0xp;
}
#example tr td {
   padding:3px 5px 5px 10px !important;
}
#example tr:nth-child(odd) {
   background-color:#ccc;
}
</style>
<body>
<div class="header">
   <h1>Ideas-Talk Chat Log</h1>

</div>
<table id="example"  class="display" cellspacing="0" width="100%" cellpadding="0">
        <thead>
            <tr>
                <th>User Id</th>
                <th>Chat</th>
                <th>Date</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>User Id</th>
                <th>Chat</th>
                <th class="date_col">Date</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
foreach ($result as $rows) {
			// print_r($rows);
			$userArray = explode('#-#', $rows['userId']);
			$dateArray = explode('#-#', $rows['date']);
			?>
                        <tr>
                            <td style="vertical-align:top;font-weight:bold;"><?php print_r($userArray[0]);?></td>
                            <td><?php print_r($rows['chat']);?></td>
                            <td  style="vertical-align:top"><?php print_r($dateArray[0]);?></td>
                        </tr>
                    <?php
}
		?>
        </tbody>
    </table>
</body>
</html>
<script>
$(document).ready(function() {
    $('#example').DataTable({
        "ordering": false
    });
} );
</script>
        <?php

	}
}

// $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
// $orderDatamodel = $objectManager->get('Magento\Sales\Model\Order')->getCollection()->getLastItem();
// $orderId   =   $orderDatamodel->getId();

// $order = $objectManager->create('\Magento\Sales\Model\Order')->load(2);
// $orderItems = $order->getAllItems();

// foreach ($orderItems as $item) {
//    echo $product_name=   $item->getName();
//    echo '<br>';
//    echo $product_id =   $item->getProductId();
// }

?>