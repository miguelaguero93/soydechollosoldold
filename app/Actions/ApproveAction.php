<?php
namespace App\Actions;
use TCG\Voyager\Actions\AbstractAction;
class ApproveAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Aprobar';
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
        if ($this->data->approved == 1) {
            return [
                'style' => 'display:none',
            ];
        }
        return [
            'class' => 'btn btn-sm btn-success pull-right',
        ];
    }
    public function getDefaultRoute()
    {
        return route('chollo.publish', array("id"=>$this->data->{$this->data->getKeyName()}) );
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'chollos';
    }
}