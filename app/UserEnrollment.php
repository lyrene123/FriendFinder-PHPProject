<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserEnrollment extends Pivot
{
    protected $table = 'user_enrollments';

}
