<?php

namespace Corals\Modules\CMS\Services;

use Corals\Foundation\Services\BaseServiceClass;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;

class DownloadService extends BaseServiceClass
{
    public function postStoreUpdate($request, $additionalData = [])
    {
        $model = $this->model;

        foreach ($request->get('downloads', []) as $index => $download) {
            if ($request->hasFile("downloads.$index.file")) {
                $model->addMedia($request->file("downloads.$index.file"))
                    ->withCustomProperties([
                        'root' => 'user_' . user()->hashed_id,
                        'description' => $request->input("downloads.$index.description")
                    ])->toMediaCollection($model->mediaCollectionName, 'secure_media');
            }
        }

        foreach ($request->get('cleared_downloads', []) as $hashedId) {
            $media = Media::find(hashids_decode($hashedId));
            if ($media) {
                $media->delete();
            }
        }
    }
}
