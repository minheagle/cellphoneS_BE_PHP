<?php
class HandleImageBase64
{
    public function __construct()
    {
    }

    function checkTypeImage($imageType)
    {
        switch ($imageType) {
            case "image/png":
                return true;
                break;
            case "image/jpeg":
                return true;
                break;
            case "image/jpg":
                return true;
                break;
            default:
                return false;
        }
    }

    function checkSizeImage($imageString)
    {
        // Remove the data URI scheme and base64 encoding to get the raw image data
        $imageString = str_replace('data:image/png;base64,', '', $imageString);
        $imageString = str_replace(' ', '+', $imageString);

        // Decode the base64 encoded image data
        $decodedImage = base64_decode($imageString);

        // Get the size of the image from the decoded data
        $imageSize = getimagesizefromstring($decodedImage);

        // Calculate the size of the image in bytes
        // Accept 2MB 
        $bytes = $imageSize[0] * $imageSize[1];
        if ($bytes > (2 * 1024 * 1024)) {
            return true;
        } else {
            return false;
        }
    }
}
