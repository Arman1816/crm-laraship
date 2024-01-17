<?php


namespace Corals\Modules\Marketplace\Traits;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception as CSVException;
use League\Csv\Reader;
use League\Csv\Writer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait ImportTrait
{
    /**
     * @var Writer
     */
    protected $importLogWriter;
    /**
     * @var string
     */
    protected $importLogFile;
    protected $success_records_count = 0;
    protected $failed_records_count = 0;

    /**
     * @throws CSVException
     */
    protected function doImport()
    {
        $this->initHandler();

        $reader = Reader::createFromPath($this->importFilePath, 'r')
            ->setDelimiter(config('corals.csv_delimiter', ','))
            ->setHeaderOffset(0);

        foreach ($reader->getRecords() as $record) {
            DB::beginTransaction();
            try {
                $this->handleImportRecord($record);
                $this->success_records_count++;
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                $this->failed_records_count++;
                if (app()->environment() !== 'production') {
                    report($exception);
                }


                $this->logRecordException($record, $exception->getMessage());
            }
        }

        //send notification
        event('notifications.marketplace.import_status', [
            'user' => $this->user,
            'import_file_name' => basename($this->importFilePath),
            'import_log_file' => $this->importLogWriter ? HtmlElement('a',
                ['href' => asset($this->importLogFile), 'target' => '_blank'],
                basename($this->importLogFile)) : '-',
            'success_records_count' => $this->success_records_count,
            'failed_records_count' => $this->failed_records_count,
        ]);
    }

    protected abstract function initHandler();

    protected abstract function getValidationRules($data, $model): array;

    /**
     * @param array $data
     * @param null $model
     * @throws \Exception
     */
    protected function validateRecord(array $data, $model = null)
    {
        $rules = $this->getValidationRules($data, $model);

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new \Exception(json_encode($validator->errors()->jsonSerialize()));
        }
    }

    /**
     * @param $record
     * @param $message
     * @throws CannotInsertRecord
     */
    protected function logRecordException($record, $message)
    {
        if (!$this->importLogWriter) {
            //we create the CSV into memory
            $logName = basename($this->importFilePath, '.csv') . Str::random(10);

            $logBasePath = 'marketplace/imports';

            $this->importLogFile = "$logBasePath/$logName.csv";

            if (!File::exists(public_path($logBasePath))) {
                File::makeDirectory(public_path($logBasePath), 0755, true);
            }

            $this->importLogWriter = Writer::createFromPath(public_path($this->importLogFile), 'w+')
                ->setDelimiter(config('corals.csv_delimiter', ','));

            $headers = $this->importHeaders;
            $headers[] = 'Import Message';

            //we insert the CSV header
            $this->importLogWriter->insertOne($headers);
        }

        $record['Import Message'] = $message;

        $this->importLogWriter->insertOne($record);
    }

    /**
     * @param $model
     * @param $filePath
     * @param $collection
     * @param $root
     * @param bool $clear
     * @param array $customProperties
     * @return false|Media
     */
    protected function addMediaFromFile($model, $filePath, $collection, $root, $clear = true, $customProperties = [])
    {
        if (empty($filePath)) {
            return false;
        }

        $file = $this->getFilePath($filePath);

        if (!file_exists($file)) {
            return false;
        }

        if ($clear) {
            $model->clearMediaCollection($collection);
        }

        return $model->addMedia($file)
            ->preservingOriginal()
            ->withCustomProperties(array_merge(['root' => $root], $customProperties))
            ->toMediaCollection($collection);
    }

    /**
     * @param $filePath
     * @return string
     */
    protected function getFilePath($filePath)
    {
        return base_path(trim(
            join('', [
                $this->images_root,
                DIRECTORY_SEPARATOR,
                trim($filePath, '/\\ ')
            ])
            , '/\\ '));
    }
}
