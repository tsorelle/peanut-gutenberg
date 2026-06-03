UPDATE wp_options SET option_value='http://local.gutenberg' WHERE option_name='siteurl';
UPDATE wp_options SET option_value='http://local.gutenberg' WHERE option_name='home';

UPDATE wp_posts
SET guid = CONCAT('http://local.gutenberg', SUBSTRING(guid, LENGTH('http://local.wp7/') + 1))
WHERE guid LIKE 'http://local.wp7/%';
SELECT * FROM wp_posts

-- Options (siteurl, home, widget data, etc.)
UPDATE wp_options SET option_value = REPLACE(option_value, 'http://local.wp7', 'http://local.gutenberg');

-- Post content and excerpts
UPDATE wp_posts SET post_content = REPLACE(post_content, 'http://local.wp7', 'http://local.gutenberg');
UPDATE wp_posts SET post_excerpt = REPLACE(post_excerpt, 'http://local.wp7', 'http://local.gutenberg');
UPDATE wp_posts SET guid = REPLACE(guid, 'http://local.wp7', 'http://local.gutenberg');

-- Post meta (theme settings, page builder data, ACF, etc.)
UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, 'http://local.wp7', 'http://local.gutenberg');

-- User meta
UPDATE wp_usermeta SET meta_value = REPLACE(meta_value, 'http://local.wp7', 'http://local.gutenberg');