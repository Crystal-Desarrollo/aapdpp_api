<?php

namespace Tests\Unit;

use App\Models\Link;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class LinkTest extends TestCase
{

    //Tests if the path is empty the default image is returned
    public function test_empty_icon_is_default()
    {
        $link = new Link();
        assertEquals("fas fa-globe", $link->icon);
    }

    //Tests the path is auto prefixed with the folder 
    public function test_icon_is_correct()
    {

        $iconName = "test-icon";

        $link = new Link([
            "icon" => $iconName
        ]);

        assertEquals($iconName, $link->icon);
    }

    //Tests the visible_text is the URL if no visible_text
    public function test_url_is_shown_if_no_visible_text()
    {
        $url = "https://url.test";

        $link = new Link([
            "url" => $url,
        ]);

        assertEquals($url, $link->visible_text);
    }

    //Tests the visible_text is the URL if no visible_text
    public function test_visible_text_is_shown_if_exists()
    {
        $visible_text = "this is the link text";

        $link = new Link([
            "visible_text" => $visible_text
        ]);

        assertEquals($visible_text, $link->visible_text);
    }
}
