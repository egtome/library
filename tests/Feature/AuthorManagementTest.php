<?php

namespace Tests\Feature;
use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function an_author_can_be_created()
    {
        $this->withoutExceptionHandling();
        
        $this->post('/author',[
            'name' => 'Till Lindemann',
            'dob' => '01/04/1963'
        ]);
        
        $author = Author::all();
        
        $this->assertCount(1,$author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('1963/04/01',$author->first()->dob->format('Y/d/m'));
    }
}
