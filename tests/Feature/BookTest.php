<?php

namespace Tests\Feature;

use App\Book;
use App\Author;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    //alias pf='clear && vendor/bin/phpunit --filter'
    //alias pu="./vendor/bin/phpunit"  

    /** @test */
    public function addBook(){
        $this->withoutExceptionHandling();

        $response = $this->post('/books', $this->data());

        $book = Book::first();
         
        // $response->assertOk();
        $this->assertCount(1,Book::all());
        $response->assertRedirect($book->path());

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
        $response = $this->post('/books', array_merge($this->data(),['author_id'=>'']));
        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function bookUpdated()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', $this->data() );

        $book = Book::first();

        $response = $this->patch($book->path(),[
            'title' => 'New Title',
            'author_id' => 'New Author',
        ]);
        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());

    }

    /** @test */
    public function bookDeleted()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());
        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');

    }

      /** @test */
      public function authorAutomaticallyAdded()
      {
          $this->withoutExceptionHandling();
          $this->post('/books', [
              'title' => 'Cool Title',
              'author_id' => 'Victor',
          ]);
          $book = Book::first();
          $author = Author::first();
          $this->assertEquals($author->id, $book->author_id);
          $this->assertCount(1, Author::all());
      }
      private function data()
      {
          return [
              'title' => 'Cool Book Title',
              'author_id' => 'Victor',
          ];
      }

 
}
