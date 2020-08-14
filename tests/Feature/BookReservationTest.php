<?php

namespace Tests\Feature;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();
        
        $response = $this->post('/books',[
            'title' => 'Messer',
            'author' => 'Till Lindemann'
        ]);
        $response->assertOk();
        $this->assertCount(1, Book::all());
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
        
        $this->patch('/books/' . $book->id,[
            'title' => 'new title',
            'author' => 'new author'
        ]);   
        
        $this->assertEquals('new title', Book::first()->title);
        $this->assertEquals('new author', Book::first()->author);
    }
}
