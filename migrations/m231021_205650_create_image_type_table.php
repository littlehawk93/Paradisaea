<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image_type}}`.
 */
class m231021_205650_create_image_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $queryText = "CREATE TABLE `image_type` (\n";
        $queryText .= "	`id` INTEGER NOT NULL,\n";
        $queryText .= "	`name` VARCHAR(50) UNIQUE NOT NULL,\n";
        $queryText .= "	`description` VARCHAR(255) NULL,\n";
        $queryText .= "	PRIMARY KEY (`id`)\n";
        $queryText .= ");";

        $this->execute($queryText);

        $this->insert('image_type', [
            'id' => 1,
            'name' => '2 Bit B&W',
            'description' => 'Image with pixels that are only pure white or pure black'
        ]);

        $this->insert('image_type', [
            'id' => 2,
            'name' => 'Monochrome',
            'description' => 'Image with pixel as a 8 bit greyscale value'
        ]);

        $this->insert('image_type', [
            'id' => 3,
            'name' => '4 Bit RGB',
            'description' => 'Image with pixels that are RGB with 4 bits per color channel'
        ]);

        $this->insert('image_type', [
            'id' => 4,
            'name' => 'Full Color',
            'description' => 'Image with pixels that are RGB with 8 bits per color channel'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image_type');
    }
}
