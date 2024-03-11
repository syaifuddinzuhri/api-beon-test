<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Payment;
use App\Traits\GlobalTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReportRepository
{
    use GlobalTrait;

    public function summary($request)
    {
        try {
            $payload = $request->all();
            $year = isset($payload['year']) ? (int) $payload['year'] : Carbon::now()->year;
            $results = DB::select('
    SELECT
        m.month AS month,
        ? AS year,
        COALESCE(SUM(CASE WHEN p.type = "out" THEN p.nominal ELSE 0 END), 0) AS total_out,
        COALESCE(SUM(CASE WHEN p.type = "in" THEN p.nominal ELSE 0 END), 0) AS total_in,
        (COALESCE(SUM(CASE WHEN p.type = "in" THEN p.nominal ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN p.type = "out" THEN p.nominal ELSE 0 END), 0)) AS difference
    FROM
        (
            SELECT 1 AS month
            UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
            UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
            UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
            UNION ALL SELECT 11 UNION ALL SELECT 12
        ) AS m
    LEFT JOIN
        payments p ON EXTRACT(MONTH FROM p.date) = m.month
               AND EXTRACT(YEAR FROM p.date) = ?
    WHERE p.deleted_at IS NULL
    GROUP BY
        m.month;
', [$year, $year]);

            $totalIn = 0;
            $totalOut = 0;

            foreach ($results as $result) {
                $totalIn += $result->total_in;
                $totalOut += $result->total_out;
            }

            $difference = $totalIn - $totalOut;

            return [
                'year' => $year,
                'in' => $totalIn,
                'out' => $totalOut,
                'diff' => $difference,
                'datas' => $results,
            ];
        } catch (\Exception $e) {
            dd($e->getMessage());
            throw $e;
            report($e);
            return $e;
        }
    }


    public function index($request)
    {
        try {
            $filter =  [];
            $query = Payment::with(['payment_type', 'resident', 'householder.house'])->whereLike($filter, $request->keyword);
            if (isset($request->type)) {
                $query->where('type', $request->type);
            }
            if (isset($request->month)) {
                $query->whereMonth('date', $request->month);
            }
            if (isset($request->year)) {
                $query->whereYear('date', $request->year);
            }
            $results = $this->datatables($request, $query);
            $total = $query->sum('nominal');
            return [
                'sum' => $total,
                'datas' => $results
            ];
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }
}
