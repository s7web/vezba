<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent{

    protected $table = "new_table";

    protected $fillable = array(
        "id",
        "username",
        "email"
    );

}