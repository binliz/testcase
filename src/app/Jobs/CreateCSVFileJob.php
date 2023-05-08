<?php

namespace App\Jobs;

use App\Events\CaseDone;
use App\Events\ProgressDone;
use App\Filters\DatasetFilters;
use App\Http\Resources\DatasetCollection;
use App\Models\Category;
use App\Models\Dataset;
use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CreateCSVFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const COUNT_BATCH_LINES = 10000;

    protected array $filters;
    protected int $start;
    protected string $fileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $filters, string $fileName, int $start)
    {
        $this->filters = $filters;
        $this->fileName = $fileName;
        $this->start = $start;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $filters = new Request($this->filters);
        $fp = fopen('php://temp', 'r+');
        if ($this->start === 0) {
            fputcsv($fp, ['category', 'firstname', 'lastname', 'email', 'gender', 'birthDate'], ',');
        }
        $datasetCollection = Dataset::filter(new DatasetFilters($filters))->orderBy('id', 'asc')
            ->with('category', 'gender')
            ->select('category_id', 'firstname', 'lastname', 'email', 'gender_id', 'birthDate')
            ->skip($this->start)
            ->take(self::COUNT_BATCH_LINES)
            ->get();
        /** @var Dataset $dataset */
        foreach ($datasetCollection as $dataset) {
            $datasetItem = new \App\Http\Resources\Dataset($dataset);
            fputcsv($fp, json_decode($datasetItem->toJson(), true), ',');
        }
        rewind($fp);
        $data = fread($fp, 10000000);
        Storage::disk('public')->append($this->fileName, $data, null);

        if (count($datasetCollection) === 0) {
            $url = Storage::disk('public')->url($this->fileName);
            event(new CaseDone('Create CSV file [done] <a href="' . $url . '">' . $this->fileName . '</a>'));
        }
        if (count($datasetCollection) > 0) {
            event(new CaseDone('Create CSV file [progress] ' . $this->fileName));
            CreateCSVFileJob::dispatch($this->filters, $this->fileName, $this->start + self::COUNT_BATCH_LINES)
                ->onQueue(
                    'default'
                );
        }
    }
}
