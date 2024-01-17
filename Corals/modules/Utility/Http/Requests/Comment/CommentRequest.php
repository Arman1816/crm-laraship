<?php

namespace Corals\Modules\Utility\Http\Requests\Comment;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Utility\Models\Comment\Comment;

class CommentRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Comment::class);

        return $this->isAuthorized();
    }

    protected function isAuthorized()
    {
        if ($this->isCreate() || $this->isStore()) {
            // Determine if the user is authorized to create an item,
            return $this->can('create') || \Settings::get('cms_comments_allow_guest');
        }

        return parent::isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Comment::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'body' => 'required|max:191',
            ]);
            if(!user() && \Settings::get('cms_comments_allow_guest')){
                $rules = array_merge($rules, [
                    'properties.author_name' => 'required|max:191',
                    'properties.author_email' => 'required|email',
                ]);
            }
        }


        return $rules;
    }
}
