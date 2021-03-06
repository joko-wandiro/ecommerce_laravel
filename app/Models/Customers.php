<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Storage;

/**
 * Customers Model
 */
class Customers extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
        'name',
        'email',
        'password',
        'phone',
        'address',
        'token',
        'active',
    );

    public static function login($email, $password)
    {
        $Model = new self;
        $query = $Model->where('email', '=', $email)->select($columns);
        $records = $query->get()->toArray();
        return $records;
    }

}

//$password= "J4V4source";
//$options = [
//    'salt' => config('app.key'),
//];
//// Checking Model
//$model       = new \App\Models\Admins;
//$model->email= "joko_wandiro@yahoo.com";
//$model->password= password_hash($password, PASSWORD_BCRYPT, $options);
//$model->save();
//$model         = \App\Models\Admins::get()->first()->toArray();
//dd($model);