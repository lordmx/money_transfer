<?php

use Phpmig\Migration\Migration;

class ExchangeTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `exchange` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `source_currency_id` VARCHAR(32) NOT NULL , `target_currency_id` VARCHAR(32) NOT NULL , `rate` FLOAT(11) NOT NULL , PRIMARY KEY (`id`), INDEX `currency_pair` (`source_currency_id`, `target_currency_id`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `exchange`;';

        $this->getContainer()['db']->query($sql);
    }
}
