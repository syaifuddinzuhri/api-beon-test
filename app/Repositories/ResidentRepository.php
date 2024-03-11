<?php

namespace App\Repositories;

use App\Constant\UploadPathConstant;
use App\Models\Admin;
use App\Models\Resident;
use App\Traits\GlobalTrait;
use App\Traits\ImageHandlerTrait;
use Exception;
use Illuminate\Support\Facades\Hash;

class ResidentRepository
{
    use GlobalTrait, ImageHandlerTrait;

    public function index($request)
    {
        try {
            $filter =  [
                'name',
            ];
            $query = Resident::with(['householder'])->whereLike($filter, $request->keyword);
            if(isset($request->status) && $request->status == "active"){
                $query->whereNotNull('status');
            }
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

            if (!$this->validatePhone($payload['phone'])) $this->ApiException('Nomor HP tidak valid');
            if (!$this->validatePhoneDigit($payload['phone'])) $this->ApiException('Nomor HP minimal 11 digit dan maksimal 13 digit');

            if (isset($request->id_card_photo) && $request->hasFile('id_card_photo') && $request->id_card_photo != null) {
                $file = $request->file('id_card_photo');
                $file_name = $this->uploadImage($file, UploadPathConstant::ID_CARD_PHOTOS);
                $payload['id_card_photo'] = $file_name;
            }

            $result = Resident::create($payload);

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

            if (!$this->validatePhone($payload['phone'])) $this->ApiException('Nomor HP tidak valid');
            if (!$this->validatePhoneDigit($payload['phone'])) $this->ApiException('Nomor HP minimal 11 digit dan maksimal 13 digit');

            $data = $this->detail($id);

            if (isset($request->id_card_photo) && $request->hasFile('id_card_photo') && $request->id_card_photo != null) {
                $file = $request->file('id_card_photo');
                $file_name = $this->uploadImage($file, UploadPathConstant::ID_CARD_PHOTOS);
                $payload['id_card_photo'] = $file_name;
                if ($data->id_card_photo) {
                    $id_card_photo_old = explode('/', $data->id_card_photo);
                    $this->unlinkImage(UploadPathConstant::ID_CARD_PHOTOS, end($id_card_photo_old));
                }
            }

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
            $data = Resident::with(['householder'])->find($id);
            if (!$data) $this->ApiException('Data penghuni tidak ditemukan');
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

    public function updateStatusResident($id, $status)
    {
        try {
            $data = $this->detail($id);
            $data->update([
                'status' => $status
            ]);
            return true;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }
}
