<?php

namespace App\Jobs;

use App\Events\CaseDone;
use App\Events\ProgressDone;
use App\Models\Category;
use App\Models\Dataset;
use App\Models\Gender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ParseCSVFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const COUNT_BATCH_LINES = 10000;
    public int $timeout = 9999999;

    private array $availableKeys = ['category', 'firstname', 'lastname', 'email', 'gender', 'birthDate'];

    protected string $fileName;
    private array $categories = [];
    private array $genders = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $insertedLines = 0;
        $patch = Storage::disk('local')->path($this->fileName);
        $fp = file($patch);
        $countLinesInFile = count($fp);

        if (($handle = fopen($patch, "r")) !== false) {
            $firstLine = true;
            $keys = [];
            $lines = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($firstLine) {
                    $firstLine = false;
                    if (count(array_intersect($this->availableKeys, $data)) != count($this->availableKeys)) {
                        Log::error('Have`nt all needle fields');

                        return;
                    }
                    $keys = array_flip($data);
                    continue;
                }
                try {
                    $line = [
                        'firstname' => $data[$keys['firstname']],
                        'lastname' => $data[$keys['lastname']],
                        'email' => $data[$keys['email']],
                        'birthDate' => $data[$keys['birthDate']]
                    ];
                    $line['category_id'] = $this->getCategoryId($data[$keys['category']]);
                    $line['gender_id'] = $this->getGenderId($data[$keys['gender']]);
                } catch (\Exception $e) {
                }
                $lines[] = $line;
                if (count($lines) >= self::COUNT_BATCH_LINES) {
                    DB::beginTransaction();
                    Dataset::insert($lines);
                    DB::commit();
                    $insertedLines += count($lines);
                    event(new ProgressDone(intdiv($insertedLines * 100, $countLinesInFile)));
                    $lines = [];
                }
            }
            if (!empty($lines)) {
                DB::beginTransaction();
                Dataset::insert($lines);
                DB::commit();
                $insertedLines += count($lines);
                event(new ProgressDone(intdiv($insertedLines * 100, $countLinesInFile)));
            }
        }
        event(new CaseDone('Parse CSV [done]'));
    }

    private function getCategoryId(string $category): int
    {
        if (!array_key_exists($category, $this->categories)) {
            $categoryRow = Category::firstOrCreate(['name' => $category]);
            $this->categories[$category] = $categoryRow->id;
        }

        return $this->categories[$category];
    }

    private function getGenderId(string $gender): int
    {
        if (!array_key_exists($gender, $this->genders)) {
            $genderRow = Gender::firstOrCreate(['name' => $gender]);
            $this->genders[$gender] = $genderRow->id;
        }

        return $this->genders[$gender];
    }

}
