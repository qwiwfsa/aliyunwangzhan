-- 恒信资本CMS数据库初始化脚本
-- 数据库: yaozijin
-- 执行方式: 在宝塔面板数据库管理中导入此SQL

-- 创建CMS页面表
CREATE TABLE IF NOT EXISTS `cms_pages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `page_id` VARCHAR(50) NOT NULL UNIQUE,
    `page_name` VARCHAR(100) NOT NULL,
    `title` VARCHAR(200),
    `subtitle` VARCHAR(200),
    `content` JSON,
    `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 创建CMS区块表
CREATE TABLE IF NOT EXISTS `cms_sections` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `page_id` VARCHAR(50) NOT NULL,
    `section_id` VARCHAR(50) NOT NULL,
    `section_name` VARCHAR(100),
    `content` TEXT,
    `sort_order` INT DEFAULT 0,
    FOREIGN KEY (`page_id`) REFERENCES `cms_pages`(`page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 创建文章分类表
CREATE TABLE IF NOT EXISTS `cms_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` VARCHAR(255),
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 创建文章表
CREATE TABLE IF NOT EXISTS `cms_articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `summary` TEXT,
    `content` LONGTEXT,
    `category_id` INT DEFAULT 0,
    `cover_image` VARCHAR(500),
    `status` ENUM('draft', 'published', 'deleted') DEFAULT 'draft',
    `is_top` TINYINT DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `view_count` INT DEFAULT 0,
    `seo_title` VARCHAR(200),
    `seo_keywords` VARCHAR(255),
    `seo_description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 插入默认页面数据
INSERT IGNORE INTO `cms_pages` (`page_id`, `page_name`, `title`) VALUES
('index', '首页', '恒信资本 - 专业资金服务'),
('services', '业务范围', '业务范围'),
('cases', '成功案例', '成功案例'),
('contact', '联系我们', '联系我们');

-- 插入默认分类
INSERT IGNORE INTO `cms_categories` (`name`, `sort_order`) VALUES
('行业资讯', 0),
('公司动态', 1),
('金融知识', 2),
('政策法规', 3);

-- 完成提示
SELECT '数据库初始化完成！' AS message;
SELECT * FROM `cms_pages`;
SELECT * FROM `cms_categories`;
