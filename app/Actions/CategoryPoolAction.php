<?php
namespace App\Actions;
use TCG\Voyager\Actions\AbstractAction;
class CategoryPoolAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Pool';
    }
    public function getIcon()
    {
        return 'voyager-check';
    }
    public function getPolicy()
    {
        return 'read';
    }
    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-success pull-right mr-2',
        ];
    }
    public function getDefaultRoute()
    {
        return route('category.pool', array("id"=>$this->data->{$this->data->getKeyName()}) );
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'categories';
    }
}