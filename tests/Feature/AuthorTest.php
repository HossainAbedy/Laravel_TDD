<?php

namespace Tests\Feature;

use App\Author;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function addAuthor(){
        $this->withoutExceptionHandling();

        $this->post('/authors', [
            'name' => 'Author',
            'dob' => '05/14/1988',
        ]);

        $author = Author::all();
         
        // $response->assertOk();
        $this->assertCount(1,$author);
        $this->assertInstanceOf(Carbon::class,$author->first()->dob);
        $this->assertEquals('1988/14/05',$author->first()->dob->format('Y/d/m'));

    }

    /** @test */
    public function nameRequired()
    {
        $response = $this->post('/authors', array_merge($this->data(), ['name' => '']));
        $response->assertSessionHasErrors('name');
    }
    /** @test */
    public function dobRequired()
    {
        $response = $this->post('/authors', array_merge($this->data(), ['dob' => '']));
        $response->assertSessionHasErrors('dob');
    }
    private function data()
    {
        return [
            'name' => 'Author Name',
            'dob' => '05/14/1988',
        ];
    }
}
