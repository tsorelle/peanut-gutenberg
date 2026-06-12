<?php

namespace Tops\cms\wordpress;

use Peanut\PeanutPermissions\TAuthorizationPaths;
use Tops\cms\nutshell\NutshellRouter;
use Tops\cms\TRouter;
use Tops\sys\TWebSite;

class WordpressRouter extends NutshellRouter
{
    public static function CheckPageAuthorization($uri)
    {
        if (self::isProtectedUrl($uri)) {
            $authorizations = TAuthorizationPaths::GetInstance();
            if (!$authorizations->isAuthorized($uri)) {
                if ($authorizations->isAuthenticated) {
                    wp_die('You do not have permission to access this page.', 403);
                } else {
                    TRouter::RedirectToLogIn();
                }
            }
        }
    }

   public static function isProtectedUrl(string $url): bool {
        // Must be internal
       if (empty($url) || $url === '/') {
           return false;
       }
       $url = TWebSite::ExpandUrl($url);
        if (strpos($url, home_url()) !== 0) {
            return false; // external — not our concern
        }

        // Exclude REST, admin, and file URLs
        if (strpos($url, rest_url()) === 0) return false;
        if (strpos($url, admin_url()) === 0) return false;
        if (preg_match('/\.(jpg|jpeg|png|gif|pdf|zip|css|js)$/i', $url)) return false;

        // Only protect if it resolves to an actual page or post
        $post_id = url_to_postid($url);
        if ($post_id === 0) {
            return false; // archive, category, front page (posts), etc. — handle separately if needed
        }

        return true;
    }
}