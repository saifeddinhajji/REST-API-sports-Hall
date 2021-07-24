<?php

namespace App\BaseModel;

use App\Models\Subscription;
use App\Models\SubscriptionAnnounce;
use App\Libs\Result;
use App\Models\Announce;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Eloquent;

/**
 * @mixin \Eloquent
 * */
trait BaseModel
{
    public $resName = 'model';
    public $roleDataCreate = [];
    public $roleDataUpdate = [];
    public $updateId = null;
    public $relation=[];
    public function filterRequest(array $data)
    {
        $data = array_filter($data, function ($key) {
            return in_array($key, $this->getFillable());
        }, ARRAY_FILTER_USE_KEY);
        return $data;
    }

    public function validate(array $data, $updateId = null)
    {
        if ($updateId && count($this->roleDataUpdate) > 0) {
            $role = $this->roleDataUpdate;
        } else {
            $role = $this->roleDataCreate;
        }
        return Validator::make($data, $role);
    }

    function mapData(array $data, $update = false): array
    {
        return $data;
    }

    public function eventBeforeCreateUpdate(array &$rawData, Result $res, $updateId = null): Result
    {
        return $res;
    }

    public function eventAfterCreateUpdate(array $rawData, Result $res, $createdModel, $updateId = null): Result
    {
        return $res;
    }

    public function CreateOne(array $rawData, $updateId = null): Result
    {
        $res = new Result();
        $res->success([]);
        $validator = $this->validate($rawData, $updateId);
        if ($validator->fails()) {
            $res->fail($validator->errors()->first());
            return $res;
        }
        $res = $this->eventBeforeCreateUpdate($rawData, $res, $updateId);
        if (!$res->success) {
            return $res;
        }
        $data = $this->filterRequest($rawData);
        $data = $this->mapData($data);
        $obj = null;
        try {
            if ($updateId) {
                $obj = $this->find($updateId);
                  if(!$obj)
                  {
                      throw new \Exception(trans('messages.data_not_found'));
                  }
                $update = $this->find($updateId)->update($data);
                if ($update) {
                    $res->message = trans('messsages.success_update');
                    $newInstance = $this->newInstance();
                    $result = $newInstance->where("id",$updateId)->with($this->relation)->first();
                    $res->success($result);
                } else {
                    $res->fail(trans('messages.failed_update'));
                }
            } else {
                $obj = $this->create($data);
                $newInstance = $this->newInstance();
                $result = $newInstance->where("id", $obj['id'])->with($this->relation)->first();
                $res->success($result);
            }
        } catch (\Exception $e) {
            $res->fail($e->getMessage());
           // throw new \Exception($e->getMessage());
        }
        $res = $this->eventAfterCreateUpdate($rawData, $res, $obj, $updateId);
        return $res;
    }

    public function UpdateOne(array $data, $updateId): Result
    {
        return $this->CreateOne($data, $updateId);
    }


}
