<?php

namespace Modules\Payment\Entities;

use App\Models\BaseModel;

class Model extends BaseModel
{
    protected $guarded = [];

    protected $table = 'payments';
}
