<?php

namespace Corals\Modules\CMS\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\CMS\Models\Testimonial;

class TestimonialRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Testimonial::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Testimonial::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'title' => 'required|max:191',
                'content' => 'required',
                'image' => 'image|max:' . maxUploadFileSize(),
                'properties.*' => 'required'
            ]);
        }

        return $rules;
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
