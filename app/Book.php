<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\User;
use App\Reservation;

class Book extends Model
{
    protected $fillable = ['title','author','author_id'];
    
    public function path()
    {
        return '/books/' . $this->id . '-' . Str::slug($this->title);
    }
    
    public function checkout(User $user)
    {
        $this->reservations()->create([
            'user_id' => $user->id,
            'checked_out_at' => now()
        ]);
    }
    
    public function checkin(User $user)
    {
        $reservation = $this->reservations()->where('user_id',$user->id)
                             ->whereNotNull('checked_out_at')
                             ->whereNull('checked_in_at')
                             ->first();
        if($reservation === null){
            throw new \Exception('No checkouts registered for this book');
        }
        $reservation->update([
            'checked_in_at' => now()
        ]);
    }
    
    public function setAuthorIdAttribute($author)
    {
        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name' => $author
        ]))->id;
    }    
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
}
