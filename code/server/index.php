<?php

class FrontController {

    protected static $_config;

    public static function getConfig() {
        if (!self::$_config) {
            self::$_config = [];
            self::$_config['CURRENT_URL'] = self::currentURL();
            self::$_config['BASE_URL'] = self::baseURL();
            self::$_config['ROUTE_URL'] = self::routeURL();
            self::$_config['DEFAULT_VERSION'] = 'latest';
            self::$_config['DEFAULT_VERSION_FOLDER'] = 'latest';
            self::$_config['VERSION'] = self::version();
            self::$_config['VERSION_FOLDER'] = self::versionFolder();

            // we need to check whether this version's folder exist and if not
            // we still return a default one
            if (!file_exists(__DIR__ . '/' . self::$_config['VERSION_FOLDER'] . '/index.php')) {
                self::$_config['VERSION'] = self::$_config['DEFAULT_VERSION'];
                self::$_config['VERSION_FOLDER'] = self::versionFolder();
            }
        }
        return self::$_config;
    }

    protected static function currentUrl() {
        $pageURL = 'http';
        ($_SERVER["SERVER_PORT"] === 443) ? $pageURL .= "s" : '';
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] .
                $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        // removing trailing slash
        if ($pageURL[strlen($pageURL) - 1] === '/') {
            $pageURL = substr($pageURL, 0, strlen($pageURL) - 1);
        }
        return $pageURL;
    }

    protected static function baseURL($path = '') {

        if ($path) {
            $dirname = dirname($path);
        } else {
            $dirname = dirname($_SERVER['SCRIPT_NAME']);
        }
        // removing trailing slash
        if ($dirname[strlen($dirname) - 1] === '/') {
            $dirname = substr($dirname, 0, strlen($dirname) - 1);
        }
        // base URL can't be a slash only, as then it means it's empty, and if it's
        // empty it should be an empty string as well ''
        if ($dirname === '/') {
            $dirname = '';
        }
        return $dirname;
    }

    protected static function routeURL() {

        $requestURI = $_SERVER["REQUEST_URI"];
        if (self::$_config['BASE_URL'] !== '' && strpos($requestURI, self::$_config['BASE_URL']) === 0) {
            $requestURI = str_replace(self::$_config['BASE_URL'], '', $requestURI);
        }
        // removing trailing slash
        if ($requestURI[strlen($requestURI) - 1] === '/') {
            $requestURI = substr($requestURI, 0, strlen($requestURI) - 1);
        }
        return $requestURI;
    }

    /**
     * Semantic versioning allows any combination of major.minor.patch where all are
     * numbers. This implementation also allows 'latest', 'stable', and 'lts', and
     * rc tags, e.g. v0.1.2-rc.1 Every version supports a config variable appended to
     * the end of a string after colon, e.g. latest:build, or v0.1.2-rc.1:minified.
     * Config is a future thing, not implemented at the moment.
     *
     * @see http://semver.org/
     *
     * @param  [string]         $str
     * @return [array,string]
     */
    protected static function parseSemVer($str) {

        $prefix = 'v';
        $major = '';
        $minor = '';
        $patch = '';
        $rc = 0;
        $config = '';

        // removing prefix
        $str = str_replace($prefix, '', $str);

        if ($str === '') {
            return self::$_config['DEFAULT_VERSION'];
        }

        if (in_array($str, ['latest', 'stable', 'lts'])) {
            return $str;
        }

        // in case there is a config
        $parts = explode(':', $str);
        if (count($parts) > 1) {
            $config = $parts[1];
        }

        // in case there is a rc
        $parts = explode('-', $parts[0]);
        if (count($parts) > 1) {
            $rc = str_replace('rc.', '', $parts[1]);
        }

        $semverStr = $parts[0];
        $semverArr = explode('.', $semverStr);

        if (count($semverArr) < 3) {
            return self::$_config['DEFAULT_VERSION'];
        }

        $major = intval($semverArr[0]);
        $minor = intval($semverArr[1]);
        $patch = intval($semverArr[2]);
        $rc = intval($rc);

        return [
            'major' => $major,
            'minor' => $minor,
            'patch' => $patch,
            'rc' => $rc,
            'config' => $config
        ];
    }

    protected static function version() {

        $version = self::$_config['DEFAULT_VERSION'];
        $split = explode('/', self::$_config['ROUTE_URL']);
        // example ROUTE_URL: /v2.3.4/some/route
        // then first item is an empty string, second is a version
        if (count($split) > 2) {
            $first = $split[1];
            $version = self::parseSemVer($first);
        }
        return $version;
    }

    protected static function versionFolder() {

        $version = self::$_config['VERSION'];
        if (is_string($version)) {
            return $version;
        }
        $str = 'v' . $version['major'] . '.' . $version['minor'] . '.' . $version['patch'];
        if ($version['rc']) {
            $str .= '-rc.' . $version['rc'];
        }
        if ($version['config']) {
            $str .= ':' . $version['config'];
        }
        return $str;
    }
}

$frontControllerConfig = FrontController::getConfig();
include_once __DIR__ . '/' . $frontControllerConfig['VERSION_FOLDER'] . '/index.php';
