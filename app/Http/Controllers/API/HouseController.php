<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HouseRequest;
use App\Http\Requests\ResidentRequest;
use App\Repositories\HouseRepository;
use App\Repositories\ResidentRepository;
use Illuminate\Http\Request;

class HouseController extends Controller
{

    private $repository;

    public function __construct()
    {
        $this->repository = new HouseRepository();
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

    /**
     * Store a newly created resource in storage.`
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HouseRequest $request)
    {
        try {
            $data = $this->repository->store($request);
            return response()->success($data, 'Data berhasil ditambahkan!');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(),  $e->getCode(), $e->getLine(), $e->getFile());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = $this->repository->detail($id);
            return response()->success($data, 'Data berhasil didapatkan!');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(),  $e->getCode(), $e->getLine(), $e->getFile());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HouseRequest $request, $id)
    {
        try {
            $data = $this->repository->update($request, $id);
            return response()->success($data, 'Data berhasil diupdate!');
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
