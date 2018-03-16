<?php
  namespace Ideas2it\ideastalk\Model\Resource;
  use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

  class Friend extends AbstractDb
  {
      /**
       * Define table
       */
       protected function _construct()
     {
         $this->_init('bot_user_queries', 'id');  // bot_user_queries is name of table
     }
  }
 ?>
