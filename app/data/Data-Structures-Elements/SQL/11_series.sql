INSERT INTO `series` (`id`, `image`, `position`, `updated_at`, `created_at`, `product_category_id`, `image_banner`) VALUES
(2, '/uploads/userfiles/images/products/body-paper-cool-citrus.png', 0, '2019-03-22 04:51:21', '2019-03-13 10:05:35', 3, '/uploads/userfiles/images/body-paper-main-banner_1584x524.jpg'),
(3, '/uploads/userfiles/images/products/crazy-cool-body-water-ice-citrus.png', 0, '2019-03-22 04:15:06', '2019-03-13 10:22:49', 3, NULL),
(4, '/uploads/userfiles/images/products/facial-paper.png', 0, '2019-03-22 04:14:46', '2019-03-13 11:33:06', 4, NULL);



INSERT INTO `series_translation` (`id`, `translatable_id`, `title`, `sub_title`, `short_desc`, `description`, `locale`) VALUES
(2, 2, 'BODY PAPER', NULL, NULL, NULL, 'th'),
(3, 3, 'CRAZY COOL BODY WATER', NULL, NULL, NULL, 'th'),
(4, 4, 'Facial Paper', NULL, NULL, NULL, 'th');
