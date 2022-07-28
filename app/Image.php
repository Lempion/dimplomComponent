<?php

namespace App;

use BulletProof\Image as C_Image;

class Image
{
    private static $paths = ['avatars' => 'user/avatars/'];

    public static function upload($files)
    {
        $image = new C_Image($files);
        $fileName = uniqid();

        $image->setName($fileName)
            ->setMime(["jpeg", "png"])
            ->setLocation(self::$paths['avatars']);
        $upload = $image->upload();

        if ($upload) {
            return $upload->getName() . '.' . $upload->getMime();
        } else {
            return ['message' => $image->getError()];
        }
    }

    public static function delete($fileName)
    {
        if (file_exists(self::$paths['avatars'] . $fileName)) {
            unlink(self::$paths['avatars'] . $fileName);
        }
    }
}