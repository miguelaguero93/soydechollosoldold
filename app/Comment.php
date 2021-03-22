<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Comment extends Model{
	protected $perPage = 100;
	public function user(){
    	return $this->belongsTo(User::class)->withTrashed();
    }
    public function chollo(){
    	return $this->belongsTo(Chollo::class)->withTrashed();
    }
    public function children(){
    	return $this->hasMany(Comment::class, 'parent_id', 'id')->orderBy('id','DESC');
    }
}