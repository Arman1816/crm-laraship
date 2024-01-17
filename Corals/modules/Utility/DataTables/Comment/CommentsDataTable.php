<?php

namespace Corals\Modules\Utility\DataTables\Comment;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Utility\Classes\Comment\CommentManager;
use Corals\Modules\Utility\Models\Comment\Comment;
use Corals\Modules\Utility\Transformers\Comment\CommentTransformer;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;

class CommentsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('utility.models.comment.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new CommentTransformer());
    }

    /**
     * @param Comment $model
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query(Comment $model, Request $request)
    {
        if ($request->has('commentable_id') && $request->has('commentable_type')) {
            return $model
                ->comments($request->input('commentable_id'), $request->input('commentable_type'))
                ->newQuery();
        } else {
            return $model->newQuery();
        }
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'commentable_type' => ['title' => trans('Utility::attributes.comments.object')],
            'commentable_id' => ['title' => trans('Utility::attributes.comments.title')],
            'body' => ['title' => trans('Utility::attributes.comments.body')],
            'author_id' => ['title' => trans('Utility::attributes.comments.author')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'body' => [
                'title' => trans('Utility::attributes.rating.body'),
                'class' => 'col-md-3',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'status' => [
                'title' => trans('Corals::attributes.status'),
                'class' => 'col-md-2',
                'type' => 'select',
                'options' => trans('Utility::attributes.comments.status_options'),
                'active' => true
            ],
            'commentable_type' => [
                'title' => trans('Utility::attributes.comments.object'),
                'class' => 'col-md-2',
                'type' => 'select',
                'options' => CommentManager::getCommentableTypes(),
                'active' => true
            ],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => [
                'title' => trans('Corals::labels.delete'),
                'permission' => 'Utility::comment.delete',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'pending' => [
                'title' => '<i class="fa fa-fw fa-clock-o"></i> ' . trans('Utility::attributes.comments.status_options.pending'),
                'permission' => 'Utility::comment.set_status',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'published' => [
                'title' => '<i class="fa fa-fw fa-check"></i> ' . trans('Utility::attributes.comments.status_options.published'),
                'permission' => 'Utility::comment.set_status',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'trashed' => [
                'title' => '<i class="fa fa-fw fa-trash-o"></i> ' . trans('Utility::attributes.comments.status_options.trashed'),
                'permission' => 'Utility::comment.set_status',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
        ];
    }

    protected function getOptions()
    {
        $url = url(config('utility.models.comment.resource_url'));
        return ['resource_url' => $url];
    }
}
