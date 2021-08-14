<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
    /*
     *         WHERE `category` LIKE '%Житлова нерухомість%'
     *          AND (`place` LIKE '%м.Київ%' OR `place` LIKE '%Київська обл.%')
     */

    public function scopeResidential($query){
        $search_cat='Житлова нерухомість';
        return $query->where('category', 'LIKE', '%' . $search_cat . '%');
    }
    public function scopeKiev($query){
        $search='Київ';
        $search2='Київська обл.';
        return $query->where('place', 'LIKE', '%' . $search . '%')
                     ->orWhere('place', 'LIKE', '%' . $search2 . '%');
    }

}
