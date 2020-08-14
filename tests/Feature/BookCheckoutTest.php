<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Book;
use App\User;
use App\Reservation;
class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_book_can_be_checked_out_by_a_signed_user()
    {
        //$this->withoutExceptionHandling();
        
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        
        $this->actingAs($user)->post('/checkout/' . $book->id);
        
        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id,Reservation::first()->user_id);
        $this->assertEquals($book->id,Reservation::first()->book_id);
        $this->assertNotNull(Reservation::first()->checked_out_at);
        $this->assertEquals(now(),Reservation::first()->checked_out_at);        
    }
    
    /** @test */
    public function only_signed_user_can_checkout_a_book()
    {
        //$this->withoutExceptionHandling();
        
        $book = factory(Book::class)->create();
        //$user = factory(User::class)->create();
        
        $this->post('/checkout/' . $book->id)
             ->assertRedirect('/login');
        
        $this->assertCount(0, Reservation::all());       
    }
    
    /** @test */
    public function only_real_book_can_be_checked_out()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user)->post('/checkout/7500')
             ->assertStatus(404);
        
        $this->assertCount(0, Reservation::all());
        
    }    
    
    /** @test */
    public function a_book_can_be_checked_in_by_a_signed_user()
    {
        //$this->withoutExceptionHandling();
        
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        
        $this->actingAs($user)->post('/checkout/' . $book->id);
        $this->actingAs($user)->post('/checkin/' . $book->id);
        
        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id,Reservation::first()->user_id);
        $this->assertEquals($book->id,Reservation::first()->book_id);
        $this->assertEquals(now(),Reservation::first()->checked_out_at);        
        $this->assertEquals(now(),Reservation::first()->checked_in_at);        
    }

    /** @test */
    public function only_signed_user_can_checkin_a_book()
    {
        //$this->withoutExceptionHandling();
        
        $book = factory(Book::class)->create();
        
        $this->post('/checkout/' . $book->id)
             ->assertRedirect('/login');
        $this->post('/checkin/' . $book->id)
             ->assertRedirect('/login');
        
        $this->assertCount(0, Reservation::all());       
    }
    
    /** @test */
    public function only_real_book_can_be_checked_in()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        
        $this->actingAs($user)->post('/checkout/' . $book->id);
        $this->actingAs($user)->post('/checkin/9500')
             ->assertStatus(404);   
      
        $this->assertCount(1, Reservation::all()); 
    }   

    
    /** @test */
    public function a_404_is_thrown_if_a_book_is_not_checked_out_first()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        
        //$this->actingAs($user)->post('/checkout/' . $book->id);
        $this->actingAs($user)
             ->post('/checkin/' . $book->id)
             ->assertStatus(404);
        
        $this->assertCount(0, Reservation::all());      
    }    
}
