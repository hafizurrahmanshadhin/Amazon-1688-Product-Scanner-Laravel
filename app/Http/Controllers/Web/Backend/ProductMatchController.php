<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ProductMatch;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ProductMatchController extends Controller {
    public function index(Request $request): JsonResponse | View {
        try {
            if ($request->ajax()) {
                $data = ProductMatch::with(['amazon', 'ali1688'])
                    ->orderByDesc('similarity')
                    ->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('similarity', function ($row) {
                        return number_format((float) $row->similarity, 3);
                    })
                    ->addColumn('amazon_title', function ($row) {
                        return $row->amazon ? (string) $row->amazon->title : '-';
                    })
                    ->addColumn('amazon_marketplace', function ($row) {
                        return $row->amazon ? (string) $row->amazon->marketplace : '-';
                    })
                    ->addColumn('amazon_price', function ($row) {
                        return $row->amazon && $row->amazon->price !== null
                        ? (string) $row->amazon->price
                        : '-';
                    })
                    ->addColumn('ali1688_title', function ($row) {
                        return $row->ali1688 ? (string) $row->ali1688->title : '-';
                    })
                    ->addColumn('ali1688_min', function ($row) {
                        return $row->ali1688 && $row->ali1688->price_min !== null
                        ? (string) $row->ali1688->price_min
                        : '-';
                    })
                    ->addColumn('estimated_margin', function ($row) {
                        return $row->estimated_margin !== null
                        ? (string) $row->estimated_margin
                        : '-';
                    })
                    ->addColumn('already_on_amazon', function ($row) {
                        return $row->already_on_amazon ? 'Yes' : 'No';
                    })
                    ->addColumn('status', function ($row) {
                        return (string) $row->status;
                    })
                    ->rawColumns([])
                    ->make();
            }

            return view('backend.layouts.matches.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
