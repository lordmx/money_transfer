<?php

use Phpmig\Migration\Migration;

class TransactionTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `transactions` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `created_at` DATETIME NOT NULL , `user_id` INT(11) NOT NULL , `wallet_id` INT(11) NOT NULL , `document_id` INT(11) NOT NULL , `amount` FLOAT(11) NOT NULL , PRIMARY KEY (`id`), INDEX (`user_id`), INDEX (`wallet_id`), INDEX (`document_id`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `transactions`;';

        $this->getContainer()['db']->query($sql);
    }
}
