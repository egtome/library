<?php

namespace Tests\Feature;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        //$this->withoutExceptionHandling();
        
        $response = $this->post('/books',[
            'title' => 'Messer',
            'author' => 'Till Lindemann'
        ]);
        
        $book = Book::first();
        
        //$response->assertOk();
        $this->assertCount(1, Book::all());
        //Redirect
        $response->assertRedirect($book->path());
    }
    
    /** @test */
    public function a_title_is_required(){
        //$this->withoutExceptionHandling();
        
        $response = $this->post('/books',[
            'title' => '',
            'author' => 'Till Lindemann'
        ]);
        $response->assertSessionHasErrors(['title']);       
    }
    
    /** @test */
    public function an_author_is_required(){
        //$this->withoutExceptionHandling();
        
        $response = $this->post('/books',[
            'title' => 'Canada',
            'author' => ''
        ]);
        $response->assertSessionHasErrors(['author']);       
    }
    
    /** @test */
    public function a_book_can_be_updated(){
        $this->withoutExceptionHandling();
        
        $this->post('/books',[
            'title' => 'Canada',
            'author' => 'Some guy'
        ]);
        
        $book = Book::first();
        
        $response = $this->patch($book->path(),[
            'title' => 'new title',
            'author' => 'new author'
        ]);   
        
        $book = Book::first();
        
        $this->assertEquals('new title', Book::first()->title);
        $this->assertEquals('new author', Book::first()->author);
        
        //Redirect
        $response->assertRedirect($book->path());
    }
    
    /** @test */
    public function a_book_can_be_deleted(){
        //$this->withoutExceptionHandling();
        
        $this->post('/books',[
            'title' => 'Canada',
            'author' => 'Some guy'
        ]);
        
        $this->assertCount(1, Book::all());
        
        $book = Book::first();
        
        $response = $this->delete($book->path());   
        
        $this->assertCount(0, Book::all());
        //where to redirect after deleting?
        $response->assertRedirect('/books');
    }
}
