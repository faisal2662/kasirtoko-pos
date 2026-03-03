<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleCustomer extends Model
{
    //
    protected $table = 'role_customers';

    protected $guarded = ['id'];

    protected $primaryKey = 'id';

}
