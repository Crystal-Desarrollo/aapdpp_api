<?php

namespace Tests\Unit;

use App\Models\File;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class FileTest extends TestCase
{

    //Tests if the path is empty the default image is returned
    public function test_empty_path_is_default()
    {
        $file = new File();
        assertEquals("/default_image.png", $file->path);
    }

    //Tests the path is auto prefixed with the folder 
    public function test_path_is_correct()
    {

        $filePath = "/files/testImage.png";

        $file = new File([
            "path" => $filePath
        ]);

        assertEquals("/storage/" . $filePath, $file->path);
    }

    //TODO: Test altText is fileName if not specified
}
