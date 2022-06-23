<?php

namespace Tests\Unit;

use App\Models\Article;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Str;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ArticleTest extends TestCase
{
    /**
     * Part of Body is returned if no description
     *
     * @return void
     */
    public function test_body_is_shown_if_no_description()
    {
        $bodyExample = Str::random(300);

        $article = new Article([
            "body" => $bodyExample
        ]);

        $expected = Str::of($bodyExample)->limit(253);
        assertEquals($expected, $article->description);
        assertEquals(256, strlen($expected));
    }

    /**
     * Description is returned if it exists
     *
     * @return void
     */
    public function test_description_is_shown_if_exists()
    {
        $bodyExample = Str::random(300);
        $descExample = Str::random(256);

        $article = new Article([
            "body" => $bodyExample,
            "description" => $descExample
        ]);

        assertEquals((string)$bodyExample, $article->body);
        assertEquals($descExample, $article->description);
    }
}
