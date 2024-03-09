<?php

namespace App\Repositories;

use App\Constant\UploadPathConstant;
use App\Models\Admin;
use App\Models\House;
use App\Models\Resident;
use App\Traits\GlobalTrait;
use App\Traits\ImageHandlerTrait;
use Exception;
use Illuminate\Support\Facades\Hash;

class HouseRepository
{
    use GlobalTrait;

    public function index($request)
    {
        try {
            $filter =  [
                'name',
            ];
            $query = House::whereLike($filter, $request->keyword);
            $result = $this->datatables($request, $query);
            return $result;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function store($request)
    {
        try {
            $payload = $request->all();
            $result = House::create($payload);
            return $this->detail($result->id);
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function update($request, $id)
    {
        try {
            $payload = $request->all();
            $data = $this->detail($id);
            $data->update($payload);
            return $this->detail($data->id);
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function detail($id)
    {
        try {
            $data = House::find($id);
            if (!$data) $this->ApiException('Data rumah tidak ditemukan');
            return $data;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function delete($id)
    {
        try {
            $data = $this->detail($id);
            $data->delete();
            return $data;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }
}
