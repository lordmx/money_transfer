<?php

use Phpmig\Migration\Migration;

class PaymentRuleTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `payment_rules` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `source_wallet_id` INT(11) NOT NULL , `target_wallet_id` INT(11) NOT NULL , `min_amount` FLOAT(11) NULL , `max_amount` FLOAT(11) NULL , `commission` FLOAT(11) NULL , `cross_rate` FLOAT(11) NULL , PRIMARY KEY (`id`), INDEX `wallet_pair` (`source_wallet_id`, `target_wallet_id`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `payment_rules`;';

        $this->getContainer()['db']->query($sql);
    }
}
