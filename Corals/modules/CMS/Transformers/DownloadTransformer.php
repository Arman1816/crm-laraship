<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\CMS\Models\Download;

class DownloadTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('cms.models.download.resource_url');
        parent::__construct($extras);
    }

    /**
     * @param Download $download
     * @return array
     * @throws \Throwable
     */

    public function transform(Download $download)
    {

        $transformedArray = [
            'id' => $download->id,
            'title' => \Str::limit($download->title, 50),
            'files' => $this->generateFilesLinks($download),
            'published' => $download->published ? '<i class="fa fa-check text-success"></i>' : '-',
            'published_at' => $download->published ? format_date($download->published_at) : '-',
            'created_at' => format_date($download->created_at),
            'updated_at' => format_date($download->updated_at),
            'action' => $this->actions($download)
        ];

        return parent::transformResponse($transformedArray);
    }

    public function generateFilesLinks($download)
    {
        $files = '';

        foreach ($download->getFiles() ?? [] as $file) {
            $files .= '<a href="' . url('cms/downloads/download/' . $file['hashed_id']) . '" 
                      target="_blank"> <i class="fa fa-arrow-circle-down fa-fw"></i> ' . $file['name'] . '</a>' . '</br>';
        }
        return $files;
    }
}
