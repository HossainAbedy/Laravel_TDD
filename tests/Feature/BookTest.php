<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function addBook(){
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Victor',
        ]);
         
        $response->assertStatus(200);
        $this->assertCount(1,Book::all());
    }

     /** @test */
     public function titleRequired()
     {
         $response = $this->post('/books', [
             'title' => '',
             'author' => 'Victor',
         ]);
         $response->assertSessionHasErrors('title');
     }

     /** @test */
    public function authorRequired()
    {
        $response = $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => '',
        ]);
        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function bookUpdated()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $response = $this->patch('/books/'.$book->id,[
            'title' => 'New Title',
            'author' => 'New Author',
        ]);
        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);

    }

 
}
