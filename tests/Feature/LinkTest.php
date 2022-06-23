<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkTest extends TestCase
{

    use RefreshDatabase;

    /**
     * URL is required to create
     *
     * @return void
     */
    public function test_url_is_required()
    {

        $exampleLink = [
            "visible_text" => "This is a link",
            "icon" => "test-icon-class",
        ];
        $response = $this->JSON("POST", '/api/links', $exampleLink);

        $response->assertUnprocessable();
    }

    /**
     * A link can be created
     *
     * @return void
     */
    public function test_a_link_can_be_saved()
    {

        $exampleLink = [
            "visible_text" => "This is a link",
            "url" => "https://test.com",
            "icon" => "test-icon-class",
        ];
        $response = $this->json("POST", '/api/links', $exampleLink);

        $response->assertCreated();
        $response->assertJsonPath("visible_text", $exampleLink["visible_text"]);
        $response->assertJsonPath("url", $exampleLink["url"]);
        $response->assertJsonPath("icon", $exampleLink["icon"]);

        $this->assertDatabaseCount("links", 1);
    }

    /**
     * Links can be retrieved
     *
     * @return void
     */
    public function test_links_can_be_retrieved()
    {

        $exampleLinks = [
            [
                "visible_text" => "This is a link",
                "icon" => "test-icon-class",
                "url" => "https://test.url"
            ],
            [
                "icon" => "test-class",
                "url" => "https://test.url"
            ]
        ];

        Link::create($exampleLinks[0]);
        Link::create($exampleLinks[1]);

        $response = $this->JSON("GET", '/api/links');

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJson($exampleLinks);
    }

    /**
     * Single link can be retrieved
     *
     * @return void
     */
    public function test_single_link_can_be_retrieved()
    {
        $data = [
            "visible_text" => "This is a link",
            "icon" => "test-icon-class",
            "url" => "https://test.url"
        ];
        $link = Link::create($data);

        $response = $this->JSON("GET", '/api/links/' . $link->id);

        $response->assertOk();
        $response->assertJson($data);
    }

    /**
     * Single link can be retrieved
     *
     * @return void
     */
    public function test_get_single_link_404_if_no_exists()
    {
        $data = [
            "visible_text" => "This is a link",
            "icon" => "test-icon-class",
            "url" => "https://test.url"
        ];
        Link::create($data);

        $response = $this->JSON("GET", '/api/links/123');

        $response->assertNotFound();
    }

    /**
     * Update an existing link
     *
     * @return void
     */
    public function test_existing_link_can_be_updated()
    {
        $data = [
            "visible_text" => "This is a link",
            "icon" => "test-icon-class",
            "url" => "https://test.url"
        ];
        $data2 = [
            "visible_text" => "This is a link updated",
            "icon" => "test-icon-class-updated",
            "url" => "https://test.url.updated"
        ];
        $link = Link::create($data);

        $response = $this->JSON("PUT", '/api/links/' . $link->id, $data2);

        $response->assertOk();
        $response->assertJson($data2);

        array_push($data2, [["id" => $link->id]]);
        $this->assertDatabaseHas("links", $data2);
    }

    /**
     * Test status code is 404 on update if the id is not correct
     *
     * @return void
     */
    public function test_update_link_404_if_no_exists()
    {
        $data = [
            "visible_text" => "This is a link",
            "icon" => "test-icon-class",
            "url" => "https://test.url"
        ];
        Link::create($data);

        $response = $this->JSON("PUT", '/api/links/111', $data);

        $response->assertNotFound();
    }

    /**
     * Delete a link
     *
     * @return void
     */
    public function test_delete_link()
    {
        $data = [
            "visible_text" => "This is a link",
            "icon" => "test-icon-class",
            "url" => "https://test.url"
        ];
        $link = Link::create($data);

        $response = $this->JSON('delete', '/api/links/' . $link->id);

        $response->assertNoContent();
        $this->assertDatabaseCount("links", 0);
    }

    /**
     * Test status code is 404 on delete if the id is not correct
     *
     * @return void
     */
    public function test_delete_link_404_if_no_exists()
    {
        $data = [
            "visible_text" => "This is a link",
            "icon" => "test-icon-class",
            "url" => "https://test.url"
        ];
        Link::create($data);

        $response = $this->JSON("PUT", '/api/links/2231', $data);

        $response->assertNotFound();
    }
}
