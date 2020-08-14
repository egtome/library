<?php

namespace Tests\Feature;
use App\Book;
use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();
        
        $response = $this->post('/books',$this->data());
        
        $book = Book::first();
        
        //$response->assertOk();
        $this->assertCount(1, Book::all());
        //Redirect
        $response->assertRedirect($book->path());
    }
    
    /** @test */
    public function a_title_is_required()
    {
        //$this->withoutExceptionHandling();
        
        $response = $this->post('/books',[
            'title' => '',
            'author' => 'Till Lindemann'
        ]);
        $response->assertSessionHasErrors(['title']);       
    }
    
    /** @test */
    public function an_author_is_required()
    {
        //$this->withoutExceptionHandling();
        
        $response = $this->post('/books',array_merge($this->data(),['author_id' => '']));
        $response->assertSessionHasErrors(['author_id']);       
    }
    
    /** @test */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();
        
        $this->post('/books',$this->data());
        
        $book = Book::first();
        
        $response = $this->patch($book->path(),[
            'title' => 'new title',
            'author_id' => 'new author'
        ]);   
        
        $book = Book::first();
        
        $this->assertEquals('new title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        
        //Redirect
        $response->assertRedirect($book->path());
    }
    
    /** @test */
    public function a_book_can_be_deleted()
    {
        //$this->withoutExceptionHandling();
        
        $this->post('/books',$this->data());
        
        $this->assertCount(1, Book::all());
        
        $book = Book::first();
        
        $response = $this->delete($book->path());   
        
        $this->assertCount(0, Book::all());
        //where to redirect after deleting?
        $response->assertRedirect('/books');
    }
    
    /** @test */
    public function a_new_author_is_automatically_created()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/books',[
            'title' => 'Some title',
            'author_id' => 'Mr. Lindemann'
        ]);
        
        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);        
        $this->assertCount(1, Author::all());  
    }
    
    private function data()
    {
        return[
            'title' => 'Books title',
            'author_id' => 'Some'
        ];
    }
}
