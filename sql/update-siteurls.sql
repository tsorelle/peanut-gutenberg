SET @scrUrl = 'http://local.gutenberg';
SET @destUrl = 'https://quakercall.org';

UPDATE wp_options
SET option_value = @destUrl
WHERE option_name IN ('siteurl', 'home');

UPDATE wp_posts
SET post_content = REPLACE(post_content, @srcUrl, @destUrl);

UPDATE wp_posts
SET post_excerpt = REPLACE(post_excerpt, @srcUrl, @destUrl);

UPDATE wp_comments
SET comment_content = REPLACE(comment_content, @srcUrl, @destUrl);