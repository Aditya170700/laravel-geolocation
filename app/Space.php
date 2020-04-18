<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
	protected $guarded = [];

    public function photos()
    {
        return $this->hasMany(SpacePhoto::class, 'space_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getSpaces($latitude, $longitude, $radius)
    {
    	// 6371 -> satuan km globe
    	// 3959 -> satuan mil
    	// question mark -> referensi array
    	return $this->select('spaces.*')
    				->selectRaw(
    					'(6371 *
    						acos( cos( radians(?) ) *
    							cos( radians( latitude ) ) *
    							cos( radians( longitude ) - radians(?) ) +
    							sin( radians(?) ) *
    							sin( radians( latitude ) )
    							)
    					) AS distance', [$latitude, $longitude, $latitude]
    				)
    				->havingRaw("distance < ?", [$radius])
    				->orderBy('distance', 'asc');
    }
}
