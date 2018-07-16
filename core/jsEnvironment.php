<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

/**
 * View
 *
 * PHP version 7.1
 */
class jsEnvironment {

    /**
     * env
     *
     * @param string $env
     * @param string $val
     * @return string
     * @throws \Exception
     */
    public static function env($env = "", $val = "") {
        self::parse_env();
        $envValue = self::getEnv($env);

        if(isset($envValue) and !empty($envValue)){
            return $envValue;
        }
        return $val;
    }

    /**
     * getenv
     *
     * @param string $env
     * @return string
     * @throws \Exception
     */
    public static function getEnv($env = "") {
        switch (true) {
            case array_key_exists($env, $_ENV):
                return $_ENV[$env];
            case array_key_exists($env, $_SERVER):
                return $_SERVER[$env];
            default:
                $value = getenv($env);
                return $value === false ? null : $value;
        }
    }

    /**
     * getenv
     *
     * @param string $env
     * @param string $val
     * @return string
     * @throws \Exception
     */
    public static function setEnv($env = "", $val = "") {
        putenv("{$env}={$val}");
        $_ENV[$env] = $val;
        $_SERVER[$env] = $val;
    }

    /**
     * clearEnv
     * Clear environment variable by name.
     *
     * @param  string $env
     */
    public function clearEnv(string $env) {
        putenv($env);
        unset($_ENV[$env]);
        unset($_SERVER[$env]);
    }

    /**
     * parse_env
     *
     * @return array|mixed
     * @throws \Exception
     */

    public static function parse_env(){
        $file = dirname(__DIR__) . '/config/.env';
        if(!file_exists($file)){
            throw new \Exception("$file not found");
        }
        $autodetect = ini_get('auto_detect_line_endings');
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        foreach ($lines as $line) {
            if (self::isLineComment($line) || !self::isLineSetter($line)) {
                continue;
            }

            [$name, $value] = explode('=', $line);
            $name = trim($name);
            $value = trim($value);

            if(empty($value)){
                continue;
            }

            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }

    }

    /**
     * Determine if the given line contains a "#" symbol at the beginning.
     *
     * @param  string $line
     * @return bool
     */
    private static function isLineComment(string $line): bool {
        return strpos(trim($line), '#') === 0;
    }

    /**
     * Determine if the given line contains an "=" symbol.
     *
     * @param  string $line
     * @return bool
     */
    protected static function isLineSetter(string $line): bool {
        return strpos($line, '=') !== false;
    }
}
