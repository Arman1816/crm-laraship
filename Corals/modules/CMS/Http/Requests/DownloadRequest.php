<?php

namespace Corals\Modules\CMS\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\CMS\Models\Download;

class DownloadRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Download::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Download::class);

        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            foreach ($this->get('downloads', []) as $index => $download) {
                $rules["downloads.$index.file"] = 'required|mimes:jpg,jpeg,png,zip,rar,txt,pdf,docs,xls,xlsx,doc|max:' . maxUploadFileSize();
                $rules["downloads.$index.description"] = 'required';
            }

            $rules = array_merge($rules, [
                'title' => 'required|max:191',
                'content' => 'required',
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->get('downloads', []) as $index => $download) {
            $attributes["downloads.$index.file"] = 'file';
            $attributes["downloads.$index.description"] = 'description';
        }

        return $attributes;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $data = $this->all();

        $data['published'] = \Arr::get($data, 'published', false);

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
