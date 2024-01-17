<?php

namespace Corals\Modules\Utility\Http\Requests\Guide;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Utility\Models\Guide\Guide;

class GuideRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Guide::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $this->setModel(Guide::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            foreach ($guideConfigFields = config('utility.models.guide.guide_config.fields') as $field) {
                $fieldName = str_replace(['[', ']', '{index}'], ['.', '', '*'], $field['name']);

                $fieldFullName = "properties$fieldName";

                $this->customFieldsAttributes[$fieldFullName] = trans($field['label']);
                $guideConfigFieldsRules[$fieldFullName] = $field['validation_rules'];
            }

            $rules = array_merge($rules, $guideConfigFieldsRules ?? []);
        }


        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'route' => 'nullable|required_without:url|max:191|unique:utility_guides',
                'url' => 'nullable|required_without:route|max:191|unique:utility_guides',
            ]);
        }

        if ($this->isUpdate()) {
            $guide = $this->route('guide');
            $rules = array_merge($rules, [
                'route' => 'nullable|required_without:url|max:191|unique:utility_guides,route,' . $guide->id,
                'url' => 'nullable|required_without:route|max:191|unique:utility_guides,url,' . $guide->id,
            ]);
        }

        return $rules;
    }

}
