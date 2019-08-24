INSERT INTO `product_category` (`id`, `image`, `position`, `updated_at`, `created_at`) VALUES
(2, '/uploads/userfiles/images/banner-hir-style.png', 1, '2019-03-14 08:06:00', '2019-03-12 05:22:00'),
(3, '/uploads/userfiles/images/banner-body-care.png', 3, '2019-03-14 08:06:33', '2019-03-12 05:22:16'),
(4, '/uploads/userfiles/images/banner-face-care.png', 2, '2019-03-14 08:06:19', '2019-03-12 05:22:23'),
(5, '/uploads/userfiles/images/banner-fragrance.png', 4, '2019-03-14 08:06:43', '2019-03-12 05:22:46');


INSERT INTO `product_category_translation` (`id`, `translatable_id`, `title`, `short_desc`, `locale`) VALUES
(2, 2, 'HAIR STYLING', NULL, 'th'),
(3, 3, 'BODY CARE', NULL, 'th'),
(4, 4, 'FACE CARE', NULL, 'th'),
(5, 5, 'FRAGRANCE', NULL, 'th');
