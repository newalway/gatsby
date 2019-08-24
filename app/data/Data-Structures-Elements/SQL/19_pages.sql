INSERT INTO `pages` (`id`, `name`, `status`, `updated_at`, `created_at`) VALUES
(1, 'about_us', 1, '2019-03-12 09:09:56', '2019-03-12 09:09:56'),
(2, 'style_guide', 1, '2019-03-15 12:34:48', '2019-03-12 09:47:02');



INSERT INTO `pages_translation` (`id`, `translatable_id`, `title`, `content`, `locale`) VALUES
(1, 1, 'About Us', NULL, 'en'),
(2, 1, 'เกี่ยวกับเรา', NULL, 'th'),
(3, 2, 'Style Guide', NULL, 'en'),
(4, 2, 'Style Guide', '<p><img alt=\"\" src=\"/uploads/userfiles/images/indonesia_banner.jpg\" style=\"width: 100%;\" /></p>\r\n\r\n<div class=\"videoEmbed\"><iframe allowfullscreen=\"\" frameborder=\"0\" height=\"349\" mozallowfullscreen=\"\" src=\"https://www.youtube.com/embed/m-NrN0ofUxk\" webkitallowfullscreen=\"\" width=\"100%\"></iframe></div>\r\n\r\n<p>&nbsp;</p>', 'th');
