<?php

namespace App\Http\Controllers\Api;

use App\Events\CaseDone;
use App\Http\Controllers\Controller;
use App\Jobs\ParseCSVFileJob;
use App\Models\Category;
use App\Models\Dataset;
use App\Models\Gender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
//        $path = Storage::path($file->getClientOriginalName());
        //      File::append($path, $file->get());
        Storage::disk('local')->append($fileName, $file->getContent(), null);

        if ($request->has('is_last') && $request->boolean('is_last')) {
            $name = basename($fileName, '.part');
            Storage::move($fileName, $name);
            ParseCSVFileJob::dispatch($name)->onQueue('default');
            event(new CaseDone('Upload CSV [done]'));
            event(new CaseDone('Start Parsing JOB'));

        }

        return response()->json(['uploaded' => true, 'path' => $fileName]);
    }

}
