<?php

namespace App\Repositories;

use App\Constant\GlobalConstant;
use App\Constant\UploadPathConstant;
use App\Models\Admin;
use App\Models\House;
use App\Models\Householder;
use App\Models\Resident;
use App\Models\Setting;
use App\Traits\GlobalTrait;
use App\Traits\ImageHandlerTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HouseholderRepository
{
    use GlobalTrait;

    private $houseRepository;
    private $residentRepository;

    public function __construct()
    {
        $this->houseRepository = new HouseRepository();
        $this->residentRepository = new ResidentRepository();
    }

    public function index($request)
    {
        try {
            $filter =  [];
            $query = Householder::with(['resident'])->whereLike($filter, $request->keyword);
            if ($request->house_id) {
                $query->where('house_id', $request->house_id);
            }
            $result = $this->datatables($request, $query);
            return $result;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    private function updateResident($payload, $is_detail = true)
    {
        try {
            if ($is_detail) {
                $this->residentRepository->detail($payload['resident_id']);
            }
            $this->residentRepository->updateStatusResident($payload['resident_id'], $payload['resident_status']);
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    private function checkStatus($payload)
    {
        try {
            unset($payload['status']);
            if ($payload['resident_status'] == GlobalConstant::CONTRACT) {
                if (!isset($payload['start_date']) || !isset($payload['end_date'])) {
                    $this->ApiException('Tanggal kontrak harus diisi');
                }
            } else {
                $payload['end_date'] = NULL;
            }
            return $payload;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $payload = $request->all();

            $payloadHouse = new Request([
                'status' => $payload['status']
            ]);
            $this->houseRepository->update($payloadHouse, $payload['house_id']);

            $data = Householder::where('house_id', $payload['house_id'])->latest()->first();
            $dataAll = Householder::where('house_id', $payload['house_id'])->get();
            $dataResident = Householder::where('resident_id', $payload['resident_id'])->where('is_done', 0)->first();

            if (!$data && $payload['status'] == 1) {
                if ($dataResident) {
                    $this->ApiException('Kamu sudah berpenghuni dirumah lain.');
                }
                $this->updateResident($payload);
                $payload = $this->checkStatus($payload);
                Householder::create($payload);
            } else if ($data && $payload['status'] == 1) {
                if ($data->resident_id != $payload['resident_id']) {
                    if ($data->is_done == 0) $this->ApiException('Sudah ada yang menghuni');
                    $this->updateResident($payload);
                    $payload = $this->checkStatus($payload);
                    Householder::create($payload);
                } else {
                    $this->updateResident($payload, false);
                    $payload = $this->checkStatus($payload);
                    $data->update([
                        'is_done' => 0,
                        'start_date' => $payload['start_date'],
                        'end_date' => $payload['end_date']
                    ]);
                }
            } else if ($data && $payload['status'] == 0) {
                foreach ($dataAll as $key => $value) {
                    $this->residentRepository->updateStatusResident($value->resident_id, NULL);
                    $value->update([
                        'is_done' => 1
                    ]);
                }
            }
            DB::commit();
            return $payload;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            report($e);
            return $e;
        }
    }

    public function detail($id)
    {
        try {
            $data = Householder::find($id);
            if (!$data) $this->ApiException('Data penghuni rumah tidak ditemukan');
            return $data;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }
}
