<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 */
class m231021_205654_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $queryText = "CREATE TABLE `image` (\n";
        $queryText .= "	`id` INTEGER NOT NULL,\n";
        $queryText .= "	`device_id` CHAR(36) NOT NULL,\n";
        $queryText .= "	`filepath` VARCHAR(2000) NOT NULL,\n";
        $queryText .= "	`created_at` CHAR(25) NOT NULL,\n";
        $queryText .= "	`created_by` CHAR(50) NOT NULL,\n";
        $queryText .= "	PRIMARY KEY (`id`),\n";
        $queryText .= "	FOREIGN KEY (`device_id`) REFERENCES `device`(`id`)\n";
        $queryText .= ");";

        $this->execute($queryText);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image');
    }
}
