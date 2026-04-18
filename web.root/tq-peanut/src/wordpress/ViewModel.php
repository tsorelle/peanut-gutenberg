<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 6/23/2017
 * Time: 7:05 AM
 */

namespace Tops\wordpress;
use \Tops\sys\Request;
use Peanut\sys\ViewModelManager;


class ViewModel
{
    /**
     * @var ViewModel
     */
    public $pathAlias;
    public $vmName;
    public $view;
    // public $nodeId;


    /**
     * See if this request is related to a ViewModel.
     * Check self::$info which is set by Initialize()
     *
     * @return bool
     */
    public static function isVmPage()
    {
        return ViewModelManager::hasVm();
    }

    /**
     * Determine if request is for a page
     *
     * @param Request $request
     * @return bool
     */
    private static function isPage(\Tops\sys\Request $request)
    {
        // exclude posts and AJAX requests
        $method = $request->getMethod();
        $format = $request->getRequestFormat();
        if ($request->getMethod() !== 'GET' || $request->getRequestFormat() !== 'html') {
            return false;
        }
        $scriptName = $request->getScriptName();

        // exclude anything under wp- directories
        if (stripos($scriptName,'/wp-') !== false) {
            return false;
        }
        $parts = explode('/',$scriptName);
        $result = array_pop($parts);

        // script file name for pages is always index.php
        return ($result === 'index.php');
    }

    /**
     *
     * Extracts an alias from the request returns it if it is valid for a view model
     * Rules for ViewModel names exclude the names of first level Drupal and Tops directories
     * and they cannot have a file extension.
     *
     * @param Request $request Tops\web\Request
     * @return bool|null|string
     */
    public static function getVmPathFromRequest(Request $request)
    {
        if (self::isPage($request)) {
            $pathInfo = $request->getPathInfo();
            $parts = explode('/',$pathInfo);
            if ($parts[0] == '') {
                array_shift($parts);
            }
            if ($parts[sizeof($parts) - 1] == '') {
                array_pop($parts);
            }
            $result = join('/',$parts);
            if (strlen($result)) {
                return $result;
            }
            return empty($result) ? false : $result;
        };
        return false;
    }

    public static function Initialize(Request $request) {
        $name = self::getVmPathFromRequest($request);
        if ($name)
        {
            $settings = \Peanut\sys\ViewModelManager::getViewModelSettings($name);
            if (!empty($settings)) {
                ViewModelManager::authorize($settings);
            }
            return $settings;

        }
        return false;
    }
}