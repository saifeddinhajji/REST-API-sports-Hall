<?php

namespace App\Libs;

use Carbon\Carbon;

/**
 * @property array error
 */
class Result
{
    public $success;
    public $message;
    public $data;

    public function __construct()
    {
        $this->success = false;
        $this->message = "";
        $this->data = array();

    }

    public function success($data = null)
    {
        $this->data = $data;
        $this->success = true;
        $this->message = trans('messages.success');
    }

    public function successPaginate(\Illuminate\Database\Eloquent\Builder $data)
    {
        $paginate = $data->paginate(10);
        $this->data = ['list' => $paginate->items(), 'total' => $paginate->total()];
        $this->success = true;
        $this->message = trans('messages.success');
    }
    public function fail($msg)
    {

        $this->success = false;
        $this->message = $msg;
        $this->data = null;

    }

}
