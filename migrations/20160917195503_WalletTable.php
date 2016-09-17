<?php

use Phpmig\Migration\Migration;

class WalletTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `wallets` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `title` VARCHAR(32) NOT NULL , `currency_id` VARCHAR(32) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `wallets`;';

        $this->getContainer()['db']->query($sql);
    }
}
