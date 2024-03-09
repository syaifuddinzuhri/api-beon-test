<?php

namespace App\Repositories;

use App\Constant\UploadPathConstant;
use App\Models\Admin;
use App\Models\House;
use App\Models\Householder;
use App\Models\Resident;
use App\Traits\GlobalTrait;
use App\Traits\ImageHandlerTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HouseRepository
{
    use GlobalTrait;

    private $residentRepository;

    public function __construct()
    {
        $this->residentRepository = new ResidentRepository();
    }

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
            DB::beginTransaction();
            $data = $this->detail($id);
            $dataAll = Householder::where('house_id', $id)->get();
            foreach ($dataAll as $key => $value) {
                $this->residentRepository->updateStatusResident($value->resident_id, NULL);
                $value->update([
                    'is_done' => 1
                ]);
            }
            $data->delete();
            DB::commit();
            return $data;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            report($e);
            return $e;
        }
    }
}
