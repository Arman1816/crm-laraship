<?php

namespace Corals\Modules\Utility\Http\Requests\SEO;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Utility\Models\SEO\SEOItem;

class SEOItemsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(SEOItem::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(SEOItem::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'title' => 'nullable|max:191',
                'type' => 'nullable|max:191',
                'meta_keywords' => 'nullable',
                'meta_description' => 'nullable',
                'image' => 'image|max:' . maxUploadFileSize()
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'route' => 'nullable|required_without:slug|max:191|unique:utilities_seo_items',
                'slug' => 'nullable|required_without:route|max:191|unique:utilities_seo_items',
            ]);
        }

        if ($this->isUpdate()) {
            $seo_item = $this->route('seo_item');

            $rules = array_merge($rules, [
                'route' => 'nullable|required_without:slug|max:191|unique:utilities_seo_items,route,' . $seo_item->id,
                'slug' => 'nullable|required_without:route|max:191|unique:utilities_seo_items,slug,' . $seo_item->id,
            ]);
        }

        return $rules;
    }
}
