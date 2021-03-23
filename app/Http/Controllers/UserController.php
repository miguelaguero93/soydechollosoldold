<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Chollo;
use App\Discount;
use App\Event;
use App\Traits\CommonHelpers;
use Carbon\Carbon;
use Hash;
use Session;
use DB;
use Cookie;
use Validator;
use Storage;
use Mail;


class UserController extends Controller{
    
    use CommonHelpers;
    
    public function delete(Request $request){
        $id = Auth::id();
        $u = User::find($id);
        $u->delete();
        $default = 1;
        $items = Chollo::where('user_id',$id)->update(['user_id'=>$default]);
        $discounts = Discount::where('user_id',$id)->update(['user_id'=>$default]);
        $events = Event::where('user_id',$id)->update(['user_id'=>$default]);
        Auth::logout();
        return $id;

    }
    public function updateSettings(Request $request){
        $u = User::find(Auth::id());
        $settings = json_encode($request->settings);
        $u->notifications = $settings;
        $u->save();    
    }

    public function servertime(){
        dd(Carbon::now()->toDateTimeString());
    }
    public function settings(){
        $filter = null;        
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Mis Configuraciones','url'=>'#']];
        $settings = Auth::user()->notifications;
        return view('user.settings', compact('breadcrumbs','filter','settings'));
    }


    public function login(Request $request){
        try {
            if (Auth::check()) {
                return Auth::id();
            }
            $data = $request->all();
            if (filter_var($data['name'], FILTER_VALIDATE_EMAIL)) {
                $existing = User::where('email',$data['name'])->first();
                if (!is_null($existing)) {
                    if (Hash::check($data['password'],$existing->password)) {
                        Session::flash('success','Login exitoso. Bienvenido!');
                        return $this->LogInUser($existing,$data['remember_me']);
                        // $register_forum = $this->loginInForum($existing);
                        // return $register_forum->token;
                    }else{
                        if (!$data['is_social']) {
                            return ['Contraseña Invalida'];
                        }else{
                            Session::flash('success','Login exitoso. Bienvenido!');
                            return $this->LogInUser($existing,$data['remember_me']);
                            // $register_forum = $this->loginInForum($existing);
                            // return $register_forum->token;
                            
                        }
                    }
                }
            }

            $existing = User::where('name',$data['name'])->first();
            if (!is_null($existing)) {
                if (Hash::check($data['password'],$existing->password)) {
                    Session::flash('success','Login exitoso. Bienvenido!');
                    $this->LogInUser($existing,$data['remember_me']);
                    // $register_forum = $this->loginInForum($existing);
                    // return $register_forum->token;
                }else{
                    return ['Contraseña Invalida'];
                }
            }
            
            return ['Usuario o email invalido.'];

            
        } catch (\Exception $e) {
            return $e->getMessage().' - '.$e->getLine();
        }
    }
    
    public function medallas($id,$name){
        $user = User::select('name','avatar','id','deleted_at')->where('id',$id)->withTrashed()->first();
        
        if (is_null($user)) {
            abort(404);
        }

        if ( !is_null($user->deleted_at) ) {
            return view('errors.405'); 
        }
        $logged = (int )Auth::id();
        $follows = 0;
        if (!is_null($logged)){
            $existing = DB::table('user_followers')->where('user_id',$id)->where('follower_id',$logged)->first();
            if(!is_null($existing)){
                $follows = 1;
            }
        }

        $items = DB::table('medallas')->get();
        $awarded = DB::table('users_medallas')->where('user_id',$id)->pluck('medalla_id')->toArray();

        foreach ($items as $key => $value) {
            if (in_array($value->id, $awarded)) {
                $value->selected = true;
            }else{
                $value->selected = false;
            }
        }
        $items = $items->sortBy('selected')->reverse()->values();
        
        $stats = $this->getStats($id);                

        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Medallas '.$name,'url'=>'#']];
        

        $filter = null;
        return view('badges.index',compact('items','breadcrumbs','filter','id','user','logged','follows','stats'));

    }

    public function followers($id,$name){
        $user = User::select('name','avatar','id')->findOrFail($id);
        $logged = (int )Auth::id();
        $follows = 0;
        if (!is_null($logged)){
            $existing = DB::table('user_followers')->where('user_id',$id)->where('follower_id',$logged)->first();
            if(!is_null($existing)){
                $follows = 1;
            }
        }
        
        $stats = $this->getStats($id);                
        $users_ids = DB::table('user_followers')->where('user_id',$id)->pluck('follower_id');
        $items = User::find($users_ids);

        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Seguidores '.$name,'url'=>'#']];
        
        $filter = null;
        return view('badges.followers',compact('breadcrumbs','filter','id','user','logged','follows','stats','items'));

    }

    public function statistics($id,$name){
        $user = User::select('name','avatar','id')->findOrFail($id);
        $logged = Auth::id();
        $follows = 0;
        if (!is_null($logged)){
            $existing = DB::table('user_followers')->where('user_id',$id)->where('follower_id',$logged)->first();
            if(!is_null($existing)){
                $follows = 1;
            }
        }
        $stats = $this->getStats($id);  

        $users_ids = DB::table('user_followers')->where('user_id',$id)->pluck('follower_id');
        $items = User::find($users_ids);
              

        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Estadisticas '.$name,'url'=>'#']];
        
        $filter = null;
        return view('badges.stats',compact('breadcrumbs','filter','id','user','logged','stats','items','follows'));

    }

    public function following($id,$name){
        $user = User::select('name','avatar','id')->findOrFail($id);
        $logged = (int )Auth::id();
        $follows = 0;
        if (!is_null($logged)){
            $existing = DB::table('user_followers')->where('user_id',$id)->where('follower_id',$logged)->first();
            if(!is_null($existing)){
                $follows = 1;
            }
        }
        
        $stats = $this->getStats($id);                
        $users_ids = DB::table('user_followers')->where('follower_id',$id)->pluck('user_id');
        $items = User::find($users_ids);

        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Siguiendo '.$name,'url'=>'#']];
        
        $filter = null;
        return view('badges.following',compact('breadcrumbs','filter','id','user','logged','follows','stats','items'));

    }


    private function getStats($id){

        
        $chollos = DB::table('chollos')->where('user_id',$id)->where('deleted_at',null)->count();
        $coupons = DB::table('discounts')->where('user_id',$id)->where('deleted_at',null)->count();
        $favorites = DB::table('favorites')->where('user_id',$id)->count();
        $keywords = DB::table('user_keywords')->where('user_id',$id)->count();
        
        $highest = (int) DB::table('chollos')->where('user_id',$id)->where('deleted_at',null)->max('votes');
        $average = (int) DB::table('chollos')->where('user_id',$id)->where('deleted_at',null)->avg('votes');
        $prizes = DB::table('canjes')->where('user_id',$id)->count();
        
        $followers = DB::table('user_followers')->where('user_id',$id)->count();
        $following = DB::table('user_followers')->where('follower_id',$id)->count();

        $comments = DB::table('comments')->where('user_id',$id)->count();
        $likes = DB::table('comments_likes')->where('user_id',$id)->count();
        $votes = DB::table('user_votes')->where('user_id',$id)->count();

        return [$chollos,$coupons,$favorites,$keywords,$highest,$average,$prizes,$followers,$following,$comments,$likes,$votes];

    }

    public function follow(Request $request){
        $id = $request->id;
        $logged = Auth::id();
        if ($id != $logged) {
            if ($request->action == 1) {
                $existing = DB::table('user_followers')->where('user_id',$id)->where('follower_id',$logged)->first();
                if (is_null($existing)) {
                    DB::table('user_followers')->insert(['user_id'=>$id,'follower_id'=>$logged]);
                }
            }
            if ($request->action == 0) {
                DB::table('user_followers')->where('user_id',$id)->where('follower_id',$logged)->delete();
            }
        }
        return $id;
    }

    public function update(Request $request){
        
        $user = Auth::user();
        
        if (isset($request->image)) {

            $validator = Validator::make($request->all(), [
                'image' => 'mimes:jpeg,png|max:500000',
            ]);

            if($validator->fails()){
                Session::flash('error','Formato Invalido');
                return back();
            }
            $img = $request->image;
            $wa = Image::make($img);
            $width = $wa->width();
            $height = $wa->height();
            if ($width > $height) {
                $wa->crop($height,$height);
            }else{
                $wa->crop($width,$width);
            }
            $filename  = 'users/'.rand().'.'.$img->getClientOriginalExtension();
            $full_path = 'public/storage/'.$filename;
            $wa->resize(100, 100)->save($full_path);
            $user->avatar = url($full_path);
            $user->save();

            // if (session('log_in_forum') !== null) {
                // $token = session('log_in_forum');
                // return redirect('https://foro.soydechollos.com/auth.php?token='.$token.'&redirect='.$redirect);
            // }
        }
        if (isset($request->about)) {
            $user->about = $request->about;
            $user->save();
        }

        $redirect = url()->previous();
        Session::flash('success','Perfil Actualizado');
        return redirect($redirect);
        // return redirect('/perfil');
    }

    public function updatePassword(Request $request){
        
        if ($request->new_password != $request->confirm_password) {
            return back()->with('error','La contraseña no coincide');
        }
        $user_id = Auth::id();
        $user = User::find($user_id);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return back()->with('success','Contraseña actualizada');
    }
    public function updateEmail(Request $request){
        
        if (!filter_var($request->new_email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error','Email Invalido');
        }

        if ($request->new_email != $request->confirm_email) {
            return back()->with('error','Los emails no coinciden');
        }
        
        $existing = User::where('email',$request->new_email)->first();
        if ( !is_null($existing) ) {
            return back()->with('error','Este email ya esta asociado a otra cuenta');
        }

        $user_id = Auth::id();
        $user = User::find($user_id);
        $user->email = $request->new_email;
        $user->save();
        return back()->with('success','Email actualizado');
    }
    public function perfil(){
        $user = Auth::user();
        $filter = null;
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Perfil '.$user->name,'url'=>'#']];
        return view('user.profile', compact('filter','breadcrumbs','user'));
    }

    public function follows(){
        $user = Auth::user();
        $followers_ids = DB::table('user_followers')->where('follower_id',$user->id)->pluck('user_id');
        $followers = User::find($followers_ids);
        $filter = null;
        $breadcrumbs = [['name' => 'Siguiendo', 'url' => '/'], ['name'=>'Siguiendo '.$user->name,'url'=>'#']];
        return view('user.follows', compact('filter','breadcrumbs','user','followers'));
    }

    public function stats(){
        
        $user = Auth::user();
        $highest = (int) DB::table('chollos')->where('user_id',$user->id)->where('deleted_at',null)->max('votes');
        $average = (int) DB::table('chollos')->where('user_id',$user->id)->where('deleted_at',null)->avg('votes');
        $prizes = DB::table('canjes')->where('user_id',$user->id)->count();
        
        $chollos = DB::table('chollos')->where('user_id',$user->id)->where('deleted_at',null)->count();
        $coupons = DB::table('discounts')->where('user_id',$user->id)->where('deleted_at',null)->count();
        $favorites = DB::table('favorites')->where('user_id',$user->id)->count();
        $keywords = DB::table('user_keywords')->where('user_id',$user->id)->count();
        
        $comments = DB::table('comments')->where('user_id',$user->id)->count();
        $likes = DB::table('comments_likes')->where('user_id',$user->id)->count();
        $votes = DB::table('user_votes')->where('user_id',$user->id)->count();

        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Estadisticas '.$user->name,'url'=>'#']];
        

        $filter = null;

        return view('user.stats',compact('breadcrumbs','filter','highest','average','prizes','chollos','comments','likes','coupons','favorites','keywords','votes'));
    
    }

    

    public function save(Request $request){
        
        try {

            if (Auth::check()) {
                return Auth::id();
            }
            
	        $data = $request->all();

	        $requested = ['email','name','password','picture'];
	        foreach ($requested as $value) {
	        	if (!isset($data[$value])) {
	        		return $value.' is required';
	        	}
	        }

	        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			  return ["Invalid email format"];
			}

            $name = nicename($data['name']);
	        $existing = User::where('email',$data['email'])->first();
	        
            if (!is_null($existing)) {

                if (Hash::check($data['password'],$existing->password)){
                    $existing->name = $name;
                    $existing->avatar = $data['picture'];
                    $existing->save();
                    
                    Session::flash('success','Registro exitoso. Bienvenido!');

                    $user_id = $this->LogInUser($existing,true);
                    // $register_forum = $this->loginInForum($existing);

                    // if (isset($register_forum->token)) {
                    //     return $register_forum->token;                    
                    // }else{
                    //     $register_forum = $this->registerInForum($existing);
                    //     if (isset($register_forum->token)) {
                    //         return $register_forum->token;
                    //     }
                    // }

                    return $user_id;


                }else{
	        	  return ['Este correo ya esta registrado.'];
                }

	        }
            
            $existing = User::where('name',$name)->first();

            if (!is_null($existing)){
                if ($data['auto'] == true) {
                    $i=2;
                    do {
                        $newname = $name.$i;
                        $i++;
                        $existing = User::where('name',$newname)->first();
                    } while (!is_null($existing));
                }else{
                    return ['Un usuario con este nombre de usuario ya existe.'];
                }
            }
        	
            $user = new User;
            if (isset($newname)) {
               $user->name = $newname;
            }else{
        	   $user->name = $name;
            }
        	$user->email = $data['email'];
            $user->source = $data['ref'];

        	if (isset($data['picture']) && !empty($data['picture'])) {
                $user->avatar = $data['picture'];                
            }

            if (isset($data['subscribed']) && !is_null($data['subscribed'])) {
                $user->subscribed = $data['subscribed'];                
            }

        	$user->password = Hash::make($data['password']);

        	$user->save();

            $this->checkForEarlyAdopterAwards($user->id);

            $this->checkReferal($user,$request);
            
    	    Session::flash('success','Registro exitoso. Bienvenido!');
            
            try {
                Mail::send('emails.welcome', [],  function($message)use($user){
                    $message->to($user->email)->subject('Bienvenid@ a Soydechollos!');
                });
            } catch (\Exception $e) {
                
            }

            $user_id = $this->LogInUser($user,true);
            // $register_forum = $this->registerInForum($user);
            
            // if (isset($register_forum->token)) {
                // session(['log_in_forum'=>$register_forum->token]);
                // return $register_forum->token;
            // }


            return $user_id;

        } catch (\Exception $e) {
        	return $e->getMessage().' - '.$e->getLine();
        }
    }

    private function checkReferal($new_user,$request){
        
        $cookie = Cookie::get('invited_friend');
        if (!is_null($cookie)) {
            $user = User::find($cookie);
            if (!is_null($user)) {
                $total_coins = DB::table('coins')->where('user_id',$user->id)->where('method_id',2)->count();
                if ($total_coins <= 80) {
                    $date = date('Y-m-d H:i:s',time());
                    $ip = $request->ip();
                    $existing = DB::table('coins')->where('user_id',$user->id)->where('method_id',2)->where('ip_address',$ip)->count();
                    if (!$existing) {
                        DB::table('coins')->insert(['user_id'=>$user->id, 'method_id' => 2, 'amount' => 1, 'created_at' => $date, 'ip_address' => $ip ]);
                        $user->coins += 1;
                        $user->save();
                        $msg = 'Has recibido <b>1 chollocoin</b> por haber invitado a <b>'.$new_user->name.'</b>'; 
                        $this->notifyUser($msg,$user,'/monedas',5);
                        $new_user->referal_id = $user->id;
                        $new_user->save();
                        Cookie::queue(Cookie::forget('invited_friend'));                    
                    }
                }
            
        }
        }    
    }   
    

    private function LogInUser(User $user,$remember){
    	Auth::login($user, $remember);
    	return $user->id;
    }

    private function registerInForum($user){

        $token = 'KJvDFT6JrW9e5cqcOvQS';
        $url = "https://foro.soydechollos.com/api/";
        $api_url = $url."users";
        
        $data = json_encode(['data' => ['attributes' => ["username"=>$user->name, "password"=>$user->password, "email"=>$user->email,'isEmailConfirmed'=>1 ]]]);
        $ch = curl_init($api_url);  

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Token ' . $token  ]); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
        $result = curl_exec($ch);   
        $new_user = json_decode($result);
        

        if (isset($new_user->errors)) {            
            // log to sentry
            return $new_user->errors;
        }

        return $this->loginInForum($user);

    }

    private function loginInForum($user,$already_logged=false){

        $url = "https://foro.soydechollos.com/api/";
        $api_url = $url."token";
        
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
           'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
          'identification' => $user->name,
          'password'       => $user->password
        ]));

        $result = curl_exec($ch);
        $session = json_decode($result);
        // login in forum failed
        if(isset($session->errors)) {
            if (!$already_logged) {
                return $this->registerInForum($user);
            }
        }
        return $session;
    }

    public function loginInForumAlredyLogged(){
        $user = Auth::user();
        $register_forum  = $this->loginInForum($user,true);
        if (isset($register_forum->token)) {
            $token = $register_forum->token;
            $redirect = 'https://foro.soydechollos.com';
            return redirect('https://foro.soydechollos.com/auth.php?token='.$token.'&redirect='.$redirect);
            
        }else{
            return redirect('https://soydechollos.com');
        }
    }


}
