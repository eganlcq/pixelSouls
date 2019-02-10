<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190210160959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE achievement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE achievement_user (achievement_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E71294E0B3EC99FE (achievement_id), INDEX IDX_E71294E0A76ED395 (user_id), PRIMARY KEY(achievement_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE armor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, defense INT NOT NULL, description LONGTEXT NOT NULL, weight INT NOT NULL, damage INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE armor_fighter (armor_id INT NOT NULL, fighter_id INT NOT NULL, INDEX IDX_1AFBC84BF5AA3663 (armor_id), INDEX IDX_1AFBC84B34934341 (fighter_id), PRIMARY KEY(armor_id, fighter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fight (id INT AUTO_INCREMENT NOT NULL, fighter_id INT NOT NULL, opponent_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_21AA445634934341 (fighter_id), INDEX IDX_21AA44567F656CDC (opponent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fighter (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, strength INT NOT NULL, dexterity INT NOT NULL, vitality INT NOT NULL, level INT NOT NULL, experience INT NOT NULL, created_at DATETIME NOT NULL, defense_won INT NOT NULL, defense_lost INT NOT NULL, attack_won INT NOT NULL, attack_lost INT NOT NULL, INDEX IDX_7A08C3FC7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, created_at DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, writer_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_5A8A6C8D1BC7E6B6 (writer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, writer_id INT NOT NULL, related_post_id INT NOT NULL, created_at DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_3E7B0BFB1BC7E6B6 (writer_id), INDEX IDX_3E7B0BFB7490C989 (related_post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_332CA4DDD60322AC (role_id), INDEX IDX_332CA4DDA76ED395 (user_id), PRIMARY KEY(role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, score INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weapon (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, power INT NOT NULL, description LONGTEXT NOT NULL, speed INT NOT NULL, parry_chance INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weapon_fighter (weapon_id INT NOT NULL, fighter_id INT NOT NULL, INDEX IDX_DF3F83B895B82273 (weapon_id), INDEX IDX_DF3F83B834934341 (fighter_id), PRIMARY KEY(weapon_id, fighter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achievement_user ADD CONSTRAINT FK_E71294E0B3EC99FE FOREIGN KEY (achievement_id) REFERENCES achievement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE achievement_user ADD CONSTRAINT FK_E71294E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE armor_fighter ADD CONSTRAINT FK_1AFBC84BF5AA3663 FOREIGN KEY (armor_id) REFERENCES armor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE armor_fighter ADD CONSTRAINT FK_1AFBC84B34934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA445634934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id)');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44567F656CDC FOREIGN KEY (opponent_id) REFERENCES fighter (id)');
        $this->addSql('ALTER TABLE fighter ADD CONSTRAINT FK_7A08C3FC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D1BC7E6B6 FOREIGN KEY (writer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB1BC7E6B6 FOREIGN KEY (writer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB7490C989 FOREIGN KEY (related_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weapon_fighter ADD CONSTRAINT FK_DF3F83B895B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weapon_fighter ADD CONSTRAINT FK_DF3F83B834934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE achievement_user DROP FOREIGN KEY FK_E71294E0B3EC99FE');
        $this->addSql('ALTER TABLE armor_fighter DROP FOREIGN KEY FK_1AFBC84BF5AA3663');
        $this->addSql('ALTER TABLE armor_fighter DROP FOREIGN KEY FK_1AFBC84B34934341');
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA445634934341');
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA44567F656CDC');
        $this->addSql('ALTER TABLE weapon_fighter DROP FOREIGN KEY FK_DF3F83B834934341');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB7490C989');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE achievement_user DROP FOREIGN KEY FK_E71294E0A76ED395');
        $this->addSql('ALTER TABLE fighter DROP FOREIGN KEY FK_7A08C3FC7E3C61F9');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D1BC7E6B6');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB1BC7E6B6');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE weapon_fighter DROP FOREIGN KEY FK_DF3F83B895B82273');
        $this->addSql('DROP TABLE achievement');
        $this->addSql('DROP TABLE achievement_user');
        $this->addSql('DROP TABLE armor');
        $this->addSql('DROP TABLE armor_fighter');
        $this->addSql('DROP TABLE fight');
        $this->addSql('DROP TABLE fighter');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE response');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE weapon');
        $this->addSql('DROP TABLE weapon_fighter');
    }
}
