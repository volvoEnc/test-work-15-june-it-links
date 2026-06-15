<?php

declare(strict_types=1);

use yii\db\Migration;

final class m260615_000001_create_car_and_car_option_tables extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%car}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'price' => $this->decimal(12, 2)->notNull(),
            'photo_url' => $this->string(2048)->notNull(),
            'contacts' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createTable('{{%car_option}}', [
            'id' => $this->primaryKey(),
            'car_id' => $this->integer()->notNull(),
            'brand' => $this->string(255)->notNull(),
            'model' => $this->string(255)->notNull(),
            'year' => $this->integer()->notNull(),
            'body' => $this->string(255)->notNull(),
            'mileage' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_car_created_at_id', '{{%car}}', ['created_at', 'id']);
        $this->createIndex('ux_car_option_car_id', '{{%car_option}}', 'car_id', true);

        $this->addForeignKey(
            'fk_car_option_car_id',
            '{{%car_option}}',
            'car_id',
            '{{%car}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->execute('ALTER TABLE {{%car}} ADD CONSTRAINT chk_car_price_non_negative CHECK (price >= 0)');
        $this->execute('ALTER TABLE {{%car_option}} ADD CONSTRAINT chk_car_option_year_valid CHECK (year BETWEEN 1886 AND 2100)');
        $this->execute('ALTER TABLE {{%car_option}} ADD CONSTRAINT chk_car_option_mileage_non_negative CHECK (mileage >= 0)');
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%car_option}}');
        $this->dropTable('{{%car}}');
    }
}
