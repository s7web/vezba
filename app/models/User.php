<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent{

    protected $table = "users";

    protected $fillable = array(
        "id",
        "email",
        "password"
    );

}