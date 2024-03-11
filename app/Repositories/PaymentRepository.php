<?php

namespace App\Repositories;

use App\Constant\GlobalConstant;
use App\Constant\UploadPathConstant;
use App\Models\Admin;
use App\Models\House;
use App\Models\Householder;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Setting;
use App\Traits\GlobalTrait;
use App\Traits\ImageHandlerTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PaymentRepository
{
    use GlobalTrait;

    private $paymentTypeRepository;
    private $householderRepository;
    private $residentRepository;

    public function __construct()
    {
        $this->paymentTypeRepository = new PaymentTypeRepository();
        $this->residentRepository = new ResidentRepository();
        $this->householderRepository = new HouseholderRepository();
    }

    public function index($request)
    {
        try {
            $filter =  [];
            $query = Payment::with(['payment_type', 'resident', 'householder.house'])->whereLike($filter, $request->keyword);
            if (isset($request->type)) {
                $query->where('type', $request->type);
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
            DB::beginTransaction();
            $payload = $request->all();
            $paymentType = $this->paymentTypeRepository->detail($payload['payment_type_id']);

            if ($paymentType->type == GlobalConstant::IN) {
                $resident = $this->residentRepository->detail($payload['resident_id']);
                if (count($payload['months']) === 0) $this->ApiException('Gagal menambahkan pembayaran');
                $houseHolder = $resident->householder;
                if (!$houseHolder) $this->ApiException('Data penghuni tidak valid');

                foreach ($payload['months'] as $key => $value) {
                    Payment::create([
                        'payment_type_id' => $paymentType->id,
                        'householder_id' => $houseHolder->id,
                        'resident_id' => $resident->id,
                        'nominal' => $paymentType->nominal,
                        'month' => $value['month'],
                        'year' => $value['year'],
                        'type' => GlobalConstant::IN
                    ]);
                }
            } else {
                if ($payload['type'] === "monthly") {
                    Payment::create([
                        'type' => GlobalConstant::OUT,
                        'payment_type_id' => $paymentType->id,
                        'nominal' => $payload['nominal'],
                        'month' => $payload['month'],
                        'year' => $payload['year']
                    ]);
                } else {
                    Payment::create([
                        'type' => GlobalConstant::OUT,
                        'payment_type_id' => $paymentType->id,
                        'nominal' => $payload['nominal'],
                        'date' => $payload['date']
                    ]);
                }
            }

            DB::commit();
            return $payload;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            report($e);
            return $e;
        }
    }

    public function store2($request)
    {
        try {
            DB::beginTransaction();
            $payload = $request->all();
            $paymentType = $this->paymentTypeRepository->detail($payload['payment_type_id']);
            $resident = $this->residentRepository->detail($payload['resident_id']);
            if ($paymentType->type == GlobalConstant::IN && (isset($payload['householder_id']) && $payload['householder_id'] != null)) {
                $houseHolder = $this->householderRepository->detail($payload['householder_id']);

                if (!isset($payload['date']) || $payload['date'] == null) {
                    $this->ApiException('Tanggal harus diisi');
                }
                if (!isset($payload['month']) || $payload['month'] == null) {
                    $this->ApiException('Jumlah bulan harus diisi');
                }

                $lastPayment = Payment::where('householder_id', $houseHolder->id)->latest()->first();
                $lastDate = $payload['date'];
                if ($lastPayment) {
                    $parseDate = Carbon::parse($lastPayment->date)->addDays(30)->format('Y-m-d');
                    $lastDate = $parseDate;
                    if ($payload['date'] <= $parseDate) $this->ApiException('Tagihan di tanggal bulan tersebut sudah dibayar.');
                }
                $date = Carbon::parse($lastDate);
                $month = Carbon::parse($lastDate)->month;
                for ($i = 1; $i <= $payload['month']; $i++) {
                    Payment::create([
                        'householder_id' => $houseHolder->id,
                        'resident_id' => $resident->id,
                        'payment_type_id' => $paymentType->id,
                        'date' => $date->format('Y-m-d'),
                        'month' => $month,
                        'year' => $date->year,
                        'nominal' => $paymentType->nominal
                    ]);
                    $month++;
                    if ($month > 12) {
                        $month = 1;
                    }
                    if ($i < $payload['month']) {
                        $date->addMonth();
                    }
                }
            } else {
                Payment::create([
                    'payment_type_id' => $paymentType->id,
                    'date' => $payload['date'],
                    'month' => Carbon::parse($payload['date'])->month,
                    'year' => Carbon::parse($payload['date'])->year,
                    'nominal' => $paymentType->nominal
                ]);
            }
            DB::commit();
            return $payload;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            report($e);
            return $e;
        }
    }
}
