<?php
require_once($_SERVER["DOCUMENT_ROOT"] .'/classloader.php');

use chillerlan\QRCode\{QROptions,QRCode};

class QR {
    public static function create($token, $link = true) {
        $qroptions = new QROptions([
            "imageBase64" => false,
            "imageTransparent" => false,
            "scale" => 20,
            "eccLevel" => QRCode::ECC_M
        ]);
        $qrcode = new QRCode($qroptions);

        $options = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.ini', true);

        $data = ($link) ? 
            "https://".$options["general"]["host"]."/scan?t=".urlencode($token)
            :
            $token;

        return $qrcode->render($data);
    }
}

?>