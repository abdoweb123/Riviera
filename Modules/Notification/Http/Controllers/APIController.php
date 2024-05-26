<?php

namespace Modules\Notification\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Modules\Notification\Entities\Model as Notification;

class APIController extends BasicController
{
    public function index()
    {
        $this->CheckAuth();

        $Notifications = Notification::query()
            ->latest()
            ->select('id', 'title_'.lang().' as title', 'ride_id', 'created_at', 'type')
            ->when($this->Client, function ($query) {
                return $query->where('client_id', $this->Client->id);
            })
            ->when($this->Driver, function ($query) {
                return $query->where('driver_id', $this->Driver->id);
            })->get();

        return ResponseHelper::make($Notifications, __('trans.Data_fetched_successfully'));
    }


} //end of class
