<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MarketplaceImportRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'file' => 'required|mimes:csv,txt|max:' . maxUploadFileSize(),
                'images_root' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!File::exists(base_path($value))) {
                            $fail(sprintf('%s %s', $value,
                                trans('Marketplace::import.exceptions.path_not_exist')));
                        }
                    }
                ],
            ]);
            $target = Str::singular(request()->segments()[1]);
            logger($target);
            if (\Store::isStoreAdmin() && $target == 'product') {
                $rules['store_id'] = 'required';
            }
        }

        return $rules;
    }
}
