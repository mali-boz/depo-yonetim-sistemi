-- ============================================================
-- Lojistik Depo Yönetim Sistemi — Veritabanı Şeması
-- ============================================================
-- Bu dosyayı phpMyAdmin > İçe Aktar (Import) ile kullanabilirsiniz.
-- Veritabanı adını kendi ortamınıza göre değiştirin.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------
-- 1. Kullanıcılar tablosu
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT AUTO_INCREMENT PRIMARY KEY,
    `name`          VARCHAR(100)    NOT NULL,
    `email`         VARCHAR(255)    NOT NULL UNIQUE,
    `password_hash` VARCHAR(255)    NOT NULL,
    `created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 2. Depolar tablosu
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `warehouses` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(150)   NOT NULL,
    `location`    VARCHAR(255)   NOT NULL,
    `capacity_m3` DECIMAL(12,2)  NOT NULL DEFAULT 0.00,
    `created_by`  INT            NOT NULL,
    `created_at`  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 3. Sevkiyatlar tablosu
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `shipments` (
    `id`           INT AUTO_INCREMENT PRIMARY KEY,
    `tracking_no`  VARCHAR(50)    NOT NULL,
    `origin`       VARCHAR(255)   NOT NULL,
    `destination`  VARCHAR(255)   NOT NULL,
    `weight_kg`    DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    `status`       ENUM('beklemede','yolda','teslim edildi') NOT NULL DEFAULT 'beklemede',
    `warehouse_id` INT            NOT NULL,
    `created_by`   INT            NOT NULL,
    `created_at`   DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`)   REFERENCES `users`(`id`)      ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 4. Envanter tablosu
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `inventory` (
    `id`              INT AUTO_INCREMENT PRIMARY KEY,
    `item_name`       VARCHAR(200)   NOT NULL,
    `quantity`        DECIMAL(12,2)  NOT NULL DEFAULT 0.00,
    `unit`            VARCHAR(50)    NOT NULL DEFAULT 'adet',
    `warehouse_id`    INT            NOT NULL,
    `last_updated_by` INT            NOT NULL,
    `updated_at`      DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (`warehouse_id`)    REFERENCES `warehouses`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`last_updated_by`) REFERENCES `users`(`id`)      ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
