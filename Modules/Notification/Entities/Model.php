<?php

namespace Modules\Notification\Entities;

use App\Models\BaseModel;

class Model extends BaseModel
{
    protected $guarded = [];

    protected $table = 'notifications';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:m a',
        'updated_at' => 'datetime:Y-m-d h:m a',
    ];

}
