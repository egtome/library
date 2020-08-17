<?php

namespace App\Http\Controllers;
use App\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function store()
    {
        $data = $this->validateRequest();
//        Author::create(request()->only([
//            'name', 'dob'
//        ]));
        Author::create($data);
    }
    
    protected function validateRequest(){
        return request()->validate([
            'name' => 'required',
            'dob' => 'required'
        ]);        
    }    
}
