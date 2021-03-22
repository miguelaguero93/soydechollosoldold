<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Notification extends Model{
	public function getValueBrowseAttribute(){
    	return strip_tags($this->value);
	}
	
    protected $perPage = 100;
	protected $fillable = [
        'read_at',
    ];

}