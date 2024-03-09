<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HouseholderRequest;
use App\Http\Requests\ResidentRequest;
use App\Repositories\HouseholderRepository;
use App\Repositories\ResidentRepository;
use Illuminate\Http\Request;

class HouseholderController extends Controller
{

    private $repository;

    public function __construct()
    {
        $this->repository = new HouseholderRepository();
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
    public function store(HouseholderRequest $request)
    {
        try {
            $data = $this->repository->store($request);
            return response()->success($data, 'Data berhasil!');
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
