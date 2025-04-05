<?php

class ResourceLoader {
    public static function load($type, $path) {
        if (!file_exists($path)) {
            throw new Exception("File not found: $path");
        }
        
        $data = file_get_contents($path);

        switch (strtolower($type)) {
            case 'image':
                return $data;  
            case 'pdf':
                return $data; 
            default:
                throw new Exception("Unsupported resource type: $type");
        }
    }

    public static function sendToBrowser($type, $path) {
        $data = self::load($type, $path);

        switch (strtolower($type)) {
            case 'image':
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $mimeType = 'image/' . $ext;
                header('Content-Type: ' . $mimeType);
                header('Content-Length: ' . strlen($data));
                header('Content-Disposition: inline; filename="' . basename($path) . '"');
                echo $data;
                break;

            case 'pdf':
                header('Content-Type: application/pdf');
                header('Content-Length: ' . strlen($data));
                header('Content-Disposition: inline; filename="' . basename($path) . '"');
                echo $data;
                break;

            default:
                throw new Exception("Unsupported type for browser output: $type");
        }
    }
}
