<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    private $repository;

    public function __construct()
    {
        $this->repository = new PaymentRepository();
    }

    public function store(PaymentRequest $request)
    {
        try {
            $data = $this->repository->store($request);
            return response()->success($data, 'Data berhasil ditambahkan!');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(),  $e->getCode(), $e->getLine(), $e->getFile());
        }
    }
}
