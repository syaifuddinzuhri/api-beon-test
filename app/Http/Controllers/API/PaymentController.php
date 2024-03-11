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

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $this->repository->index($request);
            return response()->success($data, 'Data berhasil didapatkan!');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(),  $e->getCode(), $e->getLine(), $e->getFile());
        }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = $this->repository->delete($id);
            return response()->success($data, 'Data berhasil dihapus!');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(),  $e->getCode(), $e->getLine(), $e->getFile());
        }
    }
}
