<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Repositories\AuthRepository;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $repository;

    public function __construct()
    {
        $this->repository = new ReportRepository();
    }

    public function index(Request $request)
    {
        try {
            $data = $this->repository->index($request);
            return response()->success($data, 'Berhasil!');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode());
        }
    }

    public function summary(Request $request)
    {
        try {
            $data = $this->repository->summary($request);
            return response()->success($data, 'Berhasil!');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode());
        }
    }

}
