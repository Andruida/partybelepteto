<?php
class Config {
    public static function getConfig() {
        return parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.ini', true);
    }
    public static function enabled() {
        $options = self::getConfig();
        return (isset($options["general"]) && isset($options["general"]["enabled"]) && $options["general"]["enabled"]);
    }
}
?>