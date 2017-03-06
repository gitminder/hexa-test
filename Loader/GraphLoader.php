<?php
namespace Loader;

use Loader\Exception\FileReceiveException;
use Loader\Exception\FileWriteException;

/**
 * Loads image files from given url and puts it to specified folder
 */
class GraphLoader
{
    /**
     * @var array - allowed image file extensions
     */
    private static $allowedExt = ["gif", "jpg", "png"];

    /**
     * Minimal assumed length of the input URI
     * (protocol + slashes + colon + ... etc.)
     */
    const MIN_INPUT_LENGHT = 13;

    /**
     * Minimal assumed length of the input file name
     */
    const MIN_FILE_NAME_LENGHT = 5;

    /**
     * Loads image file from $sourceUrl and puts it to $destinationFolder
     * @param $sourceUrl
     * @param $destinationFolder
     * @throws FileReceiveException
     * @throws FileWriteException
     */
    public static function loadFile($sourceUrl, $destinationFolder)
    {
        $sourceUrl = (string)$sourceUrl;
        $destinationFolder = (string)$destinationFolder;

        $sourceInfo = pathinfo($sourceUrl);
        if (!isset($sourceInfo['extension']))
            throw new \InvalidArgumentException("File have no extension");

        if (!in_array($sourceInfo['extension'], self::$allowedExt))
            throw new \InvalidArgumentException("Image file extension is not supported");

        $content = file_get_contents($sourceUrl);
        if (!$content)
            throw new FileReceiveException("Can't get file from given URI");

        if ($destinationFolder == "" || !file_exists($destinationFolder))
            throw new \InvalidArgumentException("Destination folder not exists");

        $destination = $destinationFolder."/".$sourceInfo['basename'];

        if (file_exists($destination))
            throw new FileWriteException("File already exists");

        $result = file_put_contents($destination, $content);
        if (!$result)
            throw new FileWriteException("Can't write file to destination folder");
    }

}