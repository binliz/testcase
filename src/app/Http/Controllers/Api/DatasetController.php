<?php

namespace App\Http\Controllers\Api;

use App\Events\CaseDone;
use App\Filters\DatasetFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\DatasetCollection;
use App\Jobs\CreateCSVFileJob;
use App\Models\Dataset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index(Request $request, DatasetFilters $filters)
    {
        return new DatasetCollection(
            Dataset::filter($filters)->orderBy('id', 'asc')
                ->paginate(
                $request->has('perPage') ? $request->get('perPage') : 15
            )
        );
    }

    public function export(Request $request): JsonResponse
    {
        event(new CaseDone('Start exporting CSV...'));
        CreateCSVFileJob::dispatch($request->toArray(), time() . '.csv', 0)->onQueue('default');

        return response()->json(['status' => "Ok"]);
    }
}
