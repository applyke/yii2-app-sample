<?php

use yii\db\Migration;

/**
 * Handles the creation of table `image`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m161220_141517_create_image_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('image', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'path' => $this->string(1024),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-image-user_id',
            'image',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-image-user_id',
            'image',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-image-user_id',
            'image'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-image-user_id',
            'image'
        );

        $this->dropTable('image');
    }
}
