<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notification;
use App\Price;
use App\User;
use App\Traits\CommonHelpers;
use Carbon\Carbon;
use Session;
use DB;
class NotificationsController extends Controller{
    use CommonHelpers;

    function sendmasive(Request $request){
        $not = strip_tags($request->notification);
        if (empty($not)){
            return back()->with(['message' => "No has escrito nada.", 'alert-type' => 'error']);
        }
        $users = User::all()->pluck('id')->toArray();
        $value = $request->notification;
        $date = date('Y-m-d H:i:s',time());
        $link = $request->link;
        $data = [];

        foreach ($users as $user_id) {
            $array = ['user_id'=>$user_id,'value'=>$value,'created_at'=> $date, 'link' => $link];
            array_push($data, $array);
        }

        Notification::insert($data);
        return redirect('/panel/notifications')->with(['message' => "Notificaciones enviadas.", 'alert-type' => 'success']);
    }

    function masive(Request $request){
        return view('vendor.voyager.notifications.masive');
    }

    function claim(Request $request){
        $auth = Auth::user();
        if (is_null($auth)) {
            return 0;
        }

        $prize = Price::find($request->id);
        if (!is_null($prize)) {
            if ($auth->coins >= $prize->coins) {
                
                $amount = $prize->coins;
                
                $date = date('Y-m-d H:i:s',time());
                
                DB::table('coins')->insert(['user_id'=>$auth->id, 'method_id' => 3, 'amount' => -$amount, 'price_id'=>$prize->id , 'created_at' => $date]);

                $auth->coins -= $amount;
                $auth->save();

                DB::table('canjes')->insert(['user_id'=>$auth->id, 'coins' => $amount, 'price_id'=>$prize->id ,'created_at' => $date]);

                $msg = "<b>".$prize->name.'</b> canjeado por <b>'.$amount.' CholloCoins!</b> recibirás tu premio via email pronto.';
                $this->notifyUser($msg,$auth,null,6);

                $msg = $prize->name.'canjeado por'.$amount.' CholloCoins! recibirás tu premio via email pronto.';
                Session::flash('success',$msg);
                return 1;

            }else{
                return 'No tienes suficientes monedas para canjear este premio. Chollocoins requeridos '.$prize->coins.'. Tienes '.$auth->coins;
            }
        }else{
            return 'Error';
        }


    }
    
    function configureSave(Request $request){
        $user = Auth::user();
        $user->settings = json_encode($request->settings);
        $user->save();  
    }

    function configure(){
        
        $settings = Auth::user()->settings;
        $menu = array('Configuraciones','/app/settings') ;
        $actions = true;
        return view('app.users.settings', compact('menu','actions','settings')); 
    }

    function all(){
        $filter = null;        
        $notifications = Notification::where('user_id',Auth::id())->orderBy('id','DESC')->get();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Mis Notificaciones','url'=>'#']];
        return view('user.notifications', compact('notifications','breadcrumbs','filter')); 
    }

    function index(){

        if (Auth::check()) {

            $from = Carbon::now()->subDays(30);
            
            $n = Notification::where('user_id',Auth::id())->where('read_at',null)->where('created_at','>',$from)->orderBy('id','DESC')->limit(50)->get();
            
            $count = $n->count();
            
            return [$n,$count];
        
        }

    }

    function dismissAll(){
        $n = Notification::where('user_id',Auth::id())->update(['read_at'=> date('Y-m-d h:i:s', time())]);
    }
    
    function dismiss($id){
        $n = Notification::find($id)->update(['read_at'=> date('Y-m-d h:i:s', time())]);
    }


}
