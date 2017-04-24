<?php
namespace App;

use App\Events\ModelUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Profile extends Model
{
    use Notifiable;
    
    protected $guarded = [
      'id'
    ];

    protected $events = [
        'updated' => ModelUpdated::class
    ];

    public function users()
    {
      return $this->hasOne(User::class);
    }
}
