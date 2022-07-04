<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ArticleTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Title is required to create
     *
     * @return void
     */
    public function test_title_is_required()
    {

        $exampleArticle = [
            "body" => Str::random(1024),
            "description" => Str::random(250),
        ];
        $response = $this->json("POST", '/api/articles', $exampleArticle);

        $response->assertUnprocessable();
    }

    /**
     * An article can be created
     *
     * @return void
     */
    public function test_an_article_can_be_saved()
    {

        $exampleArticle = [
            "body" => Str::random(1024),
            "description" => Str::random(250),
            "title" => Str::random(40),
        ];
        $response = $this->post(route('articles.store'), $exampleArticle);

        $response->assertCreated();
        $response->assertJson($exampleArticle);
        $response->assertJsonMissingPath("cover");

        $this->assertDatabaseCount("articles", 1);
    }

    /**
     * An article with cover can be created
     *
     * @return void
     */
    public function test_an_article_with_cover_can_be_saved()
    {

        Storage::fake("public");

        $article = [
            "title" => Str::random(40),
            "body" => Str::random(1024),
            "cover" => UploadedFile::fake()->image("coverImage.png")
        ];

        $response = $this->post(route('articles.store'), $article);

        $articleId = $response->json()['id'];

        $response->assertCreated();
        $response->assertJsonFragment([
            "alt_text" => "coverImage",
            "fileable_type" => Article::class,
            "fileable_id" => (string)$articleId,
        ]);
        $this->assertDatabaseCount("articles", 1);
        $this->assertDatabaseHas("files", [
            "fileable_type" => Article::class,
            "fileable_id" => $articleId
        ]);

        /** 
         * TODO: assert file exists in storage 
         * (it's needed to remove the `/storage` from the path)
         **/
    }

    /**
     * Articles can be retrieved
     *
     * @return void
     */
    public function test_articles_can_be_retrieved()
    {
        $exampleData = [
            [
                "body" => Str::random(1024),
                "description" => Str::random(250),
                "title" => Str::random(40),
            ], [
                "body" => Str::random(1024),
                "description" => Str::random(250),
                "title" => Str::random(40),
            ], [
                "body" => Str::random(1024),
                "description" => Str::random(250),
                "title" => Str::random(40),
            ]
        ];

        Article::create($exampleData[0]);
        Article::create($exampleData[1]);
        Article::create($exampleData[2]);

        $response = $this->json("GET", '/api/articles');

        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJson($exampleData);
    }

    /**
     * Single Article can be retrieved
     *
     * @return void
     */
    public function test_single_article_can_be_retrieved()
    {
        $data
            =  [
                "body" => Str::random(1024),
                "description" => Str::random(250),
                "title" => Str::random(40),
            ];
        $article = Article::create($data);

        $response = $this->json("GET", '/api/articles/' . $article->id);

        $response->assertOk();
        $response->assertJson($data);
    }

    /**
     * Update an existing Article
     *
     * @return void
     */
    public function test_existing_article_can_be_updated()
    {
        $data = [
            "body" => Str::random(1024),
            "description" => Str::random(250),
            "title" => Str::random(40),
        ];

        $article = Article::create($data);

        $data2 = [
            "body" => Str::random(1024),
            "description" => Str::random(250),
            "title" => Str::random(40),
            "id" => $article->id
        ];

        $response = $this->JSON("PUT", '/api/articles/' . $article->id, $data2);

        $response->assertOk();
        $response->assertJson($data2);
        $this->assertDatabaseHas("articles", $data2);
    }

    /**
     * An article with cover can be added on update
     *
     * @return void
     */
    public function test_cover_can_be_added_on_update()
    {
        Storage::fake("public");

        $article = Article::create([
            "title" => Str::random(40),
            "body" => Str::random(1024),
        ]);

        $file = UploadedFile::fake()->image("coverImage.png");
        $response = $this->put(
            route('articles.update', $article->id),
            [
                "title" => $article->title,
                "body" => $article->body,
                "cover" =>  $file
            ]
        );

        $response->assertOk();
        $this->assertDatabaseCount("articles", 1);
        $this->assertDatabaseHas("files", [
            "fileable_type" => Article::class,
            "fileable_id" => $article->id
        ]);

        /** 
         * TODO: assert file exists in storage 
         * (it's needed to remove the `/storage` from the path)
         **/
    }

    /**
     * An article with cover can be updated
     *
     * @return void
     */
    public function test_cover_can_be_updated()
    {
        Storage::fake("public");

        $file = UploadedFile::fake()->image("image1.png");
        $article = [
            "title" => Str::random(40),
            "body" => Str::random(1024),
            "cover" => $file
        ];

        $responsePost = $this->post(route('articles.store'), $article);
        $responsePost->assertCreated();

        $articleId = $responsePost->json("id");
        $file2 = UploadedFile::fake()->image("image2.png");
        $responsePut = $this->put(
            route('articles.update', $articleId),
            [
                "title" => Str::random(40),
                "body" => Str::random(1024),
                "cover" =>  $file2
            ]
        );

        $this->assertDatabaseCount("articles", 1);
        $this->assertDatabaseHas("files", [
            "fileable_type" => Article::class,
            "fileable_id" => $articleId,
            "alt_text" =>
            pathinfo($file2->getClientOriginalName(), PATHINFO_FILENAME),
        ]);
        $this->assertDatabaseMissing("files", [
            "fileable_type" => Article::class,
            "fileable_id" => $articleId,
            "alt_text" =>
            pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ]);

        /** 
         * TODO: assert file exists in storage 
         * (it's needed to remove the `/storage` from the path)
         **/
    }

    /**
     * Delete an Article
     *
     * @return void
     */
    public function test_delete_article()
    {
        $data = [
            "body" => Str::random(1024),
            "description" => Str::random(250),
            "title" => Str::random(40),
        ];
        $article = Article::create($data);

        $response = $this->JSON('delete', '/api/articles/' . $article->id);

        $response->assertNoContent();
        $this->assertDatabaseCount("links", 0);
    }
}
