<?php
namespace App\Traits;
use App\Notification;
use App\Chollo;
use App\Comment;
use App\User;
use App\Store;
use App\Category;
use Carbon\Carbon;
use DB;
use Auth;
use Cookie;
use Mail;


trait CommonHelpers{

  private function getCategories($category){
      if (!is_null($category)) {
          array_push($this->breadcrumbs, ['name' => $category->name, 'url' => '/categoria/'.$category->slug,  'id'=>$category->id]);
          if ( !is_null($category->parent_id) ) {
              $category = Category::where('id',$category->parent_id)->first();
              $this->getCategories($category);
          }
      }
  }

  private function getFilter($with_period = false){
        $filter = null;
        $topfilter = null;
        $commfilter = null;
        if( !is_null( Cookie::get('filter') ) ) {
            $filter = json_decode(Cookie::get('filter'),true);
            if (!$with_period) {
                unset($filter['period']);
            }
        }

        if( !is_null( Cookie::get('topfilter') ) ) {
            $topfilter = json_decode(Cookie::get('topfilter'),true);
            if ($topfilter['period'] == 1) {
                $topfilter['name'] = 'Hoy';
            }
            if ($topfilter['period'] == 2) {
                $topfilter['name'] = 'Ultima semana';
            }
            if ($topfilter['period'] == 3) {
                $topfilter['name'] = 'Ultimo mes';
            }
            if ($topfilter['period'] == 4) {
                $topfilter['name'] = 'De siempre';
            }
        }

        if( !is_null( Cookie::get('commfilter') ) ) {
            $commfilter = json_decode(Cookie::get('commfilter'),true);
            if ($commfilter['period'] == 1) {
                $commfilter['name'] = 'Hoy';
            }
            if ($commfilter['period'] == 2) {
                $commfilter['name'] = 'Ultima semana';
            }
            if ($commfilter['period'] == 3) {
                $commfilter['name'] = 'Ultimo mes';
            }
            if ($commfilter['period'] == 4) {
                $commfilter['name'] = 'De siempre';
            }
        }


        return [$filter,$topfilter,$commfilter]; 
  }

  private function addStore($parse_url){
      if($parse_url[0] == 'amazon') {
          $existing = Store::where('website',$parse_url[2])->first();
      }else{
          $existing = Store::where('name',$parse_url[0])->first();
      }

      if (is_null($existing)) {
          $store = new Store;
          $store->name = $parse_url[0];
          $store->visible_name = $parse_url[0];
          $store->slug = $parse_url[0];
          $store->website = $parse_url[2];
          $store->save();  
          return $store;
      }
      return $existing;
  }


  private function checkForEarlyAdopterAwards($user_id){

    $awards = DB::table('medallas')->where('type',7)->where('value','>',$user_id)->get();

    foreach ($awards as $key => $value) {
        $medalla_id = $value->id;
        $msg = "La medalla <b>$value->name</b> te ha sido otorgada! <b>".$value->description."</b>";
        $this->awardMedal($user_id,null,$medalla_id,$msg);                    
    }

  }
  
  private function checkForVotesAwards($user_id,$chollo_id,$type,$count){

    $value = DB::table('medallas')->where('type',$type)->where('value','>=',$count)->orderBy('value','ASC')->first();
    if (!is_null($value)) {
      $msg = "La medalla <b>$value->name</b> te ha sido otorgada! <b>".$value->description."</b>";
      $this->awardMedal($user_id,$chollo_id,$value->id,$msg);                    
    }

  }

  private function checkForAwards($user_id,$chollo_id,$type,$count){

    $awards = DB::table('medallas')->where('type',$type)->where('value',$count)->get();
    foreach ($awards as $key => $value) {
        $medalla_id = $value->id;
        $msg = "La medalla <b>$value->name</b> te ha sido otorgada! <b>".$value->description."</b>";
        $this->awardMedal($user_id,$chollo_id,$medalla_id,$msg);                    
    }

  }

  private function awardMedal($user_id,$chollo_id,$medalla_id,$msg){
      $granted = DB::table('users_medallas')->where('user_id',$user_id)->where('medalla_id',$medalla_id)->first();
      if(is_null($granted)){
          $date = date('Y-m-d h:i:s',time());
          DB::table('users_medallas')->insert(['user_id'=>$user_id, 'chollo_id'=>$chollo_id, 'medalla_id' => $medalla_id,'created_at'=> $date]);
          $link = "/medallas/$user_id/m";
          $user = User::find($user_id);
          $this->notifyUser($msg,$user,$link,4);
          
      }
  }

  private function notifyUser($msg,$user,$link,$type){

    if (!is_null($user)) {
        $user_settings = json_decode($user->notifications,true);
        
        if ($user_settings[$type]['system']) {
          $date = date('Y-m-d H:i:s',time());
          DB::table('notifications')->insert(['user_id' => $user->id, 'value' => $msg, 'created_at'=> $date, 'link' => $link]);
        }
        if ($user_settings[$type]['email']) {
          $data = ['user'=>$user,'msg'=>$msg,'link'=>$link];
          Mail::send('emails.notifications', $data,  function($message)use($user){
              $message->to($user->email)->subject('Nueva Alerta de Chollo recibida');
          });
        }
    }

  }

  private function getSiteName($url){
      $parsed = parse_url($url);
      $exploded = explode('.', $parsed['host']);
      $size = sizeof($exploded); 
      $site_name = '';
      $main_tld = ['com','org','net','int','edu','gov','mil'];
      if ($size == 2) {
        $site_name = $exploded[0];
      }
      if ($size == 3) {
        if ($exploded[0] == 'www') {
          $site_name = $exploded[1];
        }else{
          if (in_array($exploded[2], $main_tld)  ) {
            $site_name = $exploded[1];
          }else{
            $site_name = $exploded[0];
          }
        }
      }
      if ($size == 4) {
        $site_name = $exploded[1];
      }
      

      $site_url = $parsed['scheme'].'://'.$parsed['host'];
      
      $basic_url = $site_url;
      if (isset($parsed['path'])) {
        $basic_url .= $parsed['path'];
      }

      return [$site_name,$basic_url,$site_url];
  }

  private function getTopFilter(){
    $filter = null;
    if( !is_null( Cookie::get('topfilter') ) ) {
        $filter = json_decode(Cookie::get('topfilter'),true);
    }
    return $filter; 
  }

  private function getCommFilter(){
    $filter = null;
    if( !is_null( Cookie::get('commfilter') ) ) {
        $filter = json_decode(Cookie::get('commfilter'),true);
    }
    return $filter; 
  }

  private function getTopVoted(){

    $filter = $this->getTopFilter();
    $from = $this->getFrom($filter);

    $query = Chollo::where('approved',1);
    if (!is_null($from)) {
      $query->where('created_at','>',$from);
    }

    $items = $query->orderBy('votes','DESC')->limit(3)->get();
    
    if (!Auth::guest()) {
        $user_id = Auth::id();
        $ids = $items->pluck('id');

        $user_votes = DB::table('user_votes')->where('user_id',$user_id)->whereIn('chollo_id',$ids)->get();
        foreach ($user_votes as $key => $value) {
            $item = $items->firstWhere('id',$value->chollo_id);
            if (!is_null($item)) {
                $item->user_vote = $value->value;
            }
        }
    }
    return $items;
  }

  private function getFrom($filter){
    if (!is_null($filter) && isset($filter['period']) && $filter['period'] != 2) {              
      switch ($filter['period']) {
        case 1:
            $from = Carbon::now()->subDay()->toDateTimeString();
            break;
        case 3:
            $from = Carbon::now()->submonth()->toDateTimeString();
            break;
        default:
            $from = null;
            break;
      }
    }else{
        $from = Carbon::now()->subWeek()->toDateTimeString();
    }
    return $from;
  }

  private function getLatestComments(){
    
    $filter = $this->getCommFilter();
    $from = $this->getFrom($filter);
    $query = Comment::with('user','chollo')->where('parent_id',null); 
    if (!is_null($from)) {
      $query->where('created_at','>',$from);
    }
    return $query->orderBy('id','DESC')->limit(30)->get();
  
  }

}	