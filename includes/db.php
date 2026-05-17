<?php
require_once __DIR__.'/config.php';

function db(): mysqli {
    static $c = null;
    if ($c) return $c;

    $c = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($c->connect_error) die('<!-- DB:'.$c->connect_error.' -->');

    $c->query("CREATE DATABASE IF NOT EXISTS `".DB_NAME."` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $c->select_db(DB_NAME);
    $c->set_charset('utf8mb4');
    $c->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

    $c->query("CREATE TABLE IF NOT EXISTS `applications` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `phone` VARCHAR(50) DEFAULT NULL,
        `current_role` VARCHAR(500) DEFAULT NULL,
        `q_search_engine` TEXT,
        `q_ai_limitation` TEXT,
        `q_changed_mind` TEXT,
        `q_company_idea` TEXT,
        `q_opinion` TEXT,
        `brand_1` VARCHAR(255),
        `brand_2` VARCHAR(255),
        `brand_3` VARCHAR(255),
        `brand_4` VARCHAR(255),
        `brand_5` VARCHAR(255),
        `made_link` VARCHAR(500) DEFAULT NULL,
        `q_niche` TEXT,
        `q_grava_theory` TEXT,
        `function_interest` VARCHAR(255) DEFAULT NULL,
        `notice_period` VARCHAR(255) DEFAULT NULL,
        `email` VARCHAR(255) NOT NULL,
        `linkedin` VARCHAR(500) DEFAULT NULL,
        `resume_link` VARCHAR(500) DEFAULT NULL,
        `fb_pricing` TEXT DEFAULT NULL,
        `fb_features` TEXT DEFAULT NULL,
        `fb_general` TEXT DEFAULT NULL,
        `ref_code` VARCHAR(16) DEFAULT NULL,
        `referred_by` VARCHAR(16) DEFAULT NULL,
        `fingerprint` VARCHAR(64) DEFAULT NULL,
        `ip_address` VARCHAR(60) DEFAULT NULL,
        `user_agent` VARCHAR(512) DEFAULT NULL,
        `device_type` VARCHAR(20) DEFAULT 'desktop',
        `is_flagged` TINYINT(1) DEFAULT 0,
        `flag_reason` VARCHAR(255) DEFAULT NULL,
        `status` ENUM('pending','reviewed','shortlisted','rejected','hired') DEFAULT 'pending',
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `uq_email` (`email`),
        UNIQUE KEY `uq_ref` (`ref_code`),
        KEY `idx_status` (`status`),
        KEY `idx_created` (`created_at`),
        KEY `idx_ip` (`ip_address`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $c->query("CREATE TABLE IF NOT EXISTS `referrals` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `code` VARCHAR(16) NOT NULL UNIQUE,
        `applicant_id` INT UNSIGNED NOT NULL,
        `uses` INT DEFAULT 0,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        KEY `idx_code` (`code`),
        KEY `idx_uses` (`uses`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $c->query("CREATE TABLE IF NOT EXISTS `referral_invites` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `referrer_code` VARCHAR(16) NOT NULL,
        `invited_email` VARCHAR(255) NOT NULL,
        `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        KEY `idx_code` (`referrer_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $c->query("CREATE TABLE IF NOT EXISTS `rate_limits` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `action_key` VARCHAR(255) NOT NULL UNIQUE,
        `attempts` INT DEFAULT 1,
        `window_start` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `last_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $c->query("CREATE TABLE IF NOT EXISTS `mail_log` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `to_email` VARCHAR(255) NOT NULL,
        `subject` VARCHAR(500) NOT NULL,
        `body` TEXT NOT NULL,
        `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `status` ENUM('sent','failed','logged') DEFAULT 'logged',
        `error_msg` VARCHAR(500) DEFAULT NULL,
        KEY `idx_email` (`to_email`),
        KEY `idx_sent` (`sent_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $c->query("CREATE TABLE IF NOT EXISTS `page_views` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `ip_address` VARCHAR(60) DEFAULT NULL,
        `viewed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        KEY `idx_ip` (`ip_address`),
        KEY `idx_viewed` (`viewed_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $existing = [];
    $r = $c->query("SHOW COLUMNS FROM applications");
    if ($r) while ($row = $r->fetch_assoc()) $existing[] = $row['Field'];

    $newCols = [
        'fb_pricing' => 'TEXT',
        'fb_features' => 'TEXT',
        'fb_general' => 'TEXT',
        'ref_code' => 'VARCHAR(16)',
        'referred_by' => 'VARCHAR(16)',
        'fingerprint' => 'VARCHAR(64)',
        'device_type' => "VARCHAR(20) DEFAULT 'desktop'",
        'is_flagged' => 'TINYINT(1) DEFAULT 0',
        'flag_reason' => 'VARCHAR(255) DEFAULT NULL',
    ];
    foreach ($newCols as $col => $def) {
        if (!in_array($col, $existing, true)) {
            $c->query("ALTER TABLE applications ADD COLUMN `$col` $def");
        }
    }

    $keys = $c->query("SHOW INDEX FROM applications WHERE Key_name='uq_ref'")->num_rows;
    if (!$keys) @$c->query("ALTER TABLE applications ADD UNIQUE KEY `uq_ref` (`ref_code`)");

    return $c;
}

function q(string $sql): array {
    $r = db()->query($sql);
    if (!$r) return [];
    $rows = [];
    while ($row = $r->fetch_assoc()) $rows[] = $row;
    return $rows;
}

function qq(string $sql): ?array {
    $r = db()->query($sql);
    return ($r && $r->num_rows) ? $r->fetch_assoc() : null;
}
