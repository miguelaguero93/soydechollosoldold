<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Discount;
use App\Store;
use App\Traits\CommonHelpers;
use Carbon\Carbon;
use Auth;
use Session;

class CuponController extends Controller{

	use CommonHelpers;
    private $pagination = 12;
    private $original_autoload = 5;

    public function getMore(Request $request){
        $pagination = $this->pagination;
        $now = Carbon::now();
        $filter = $this->getFilter();
        $items = $this->getItems($request->page,$request->sent,$request->website,$now,$filter);
        foreach ($items as $key => $value) {
            $value->code_visible = false;
        }
        return $items;
    }

    public function getItems($page,$sent,$website,$now,$filter){
        
        $pagination = $this->pagination;
        $skip = ($page - 1) * $pagination;

        $query = Discount::orderBy('id', 'DESC');

        if (!empty($website) && $website != '0') {
            $query->where('store_id',$website);
        }

        if ($sent != 0) {
            $query->where('user_id',Auth::id());
        }

        $now = $now->toDateTimeString();

        $query->where(function($q)use($now){
          $q->orWhere('from',null);
          $q->orWhere('from','<',$now);
        });

        if (!is_null($filter[0])) {
            if (isset($filter[0]['hide_expired'])) {
                $query->where(function($q)use($now){
                  $q->orWhere('until',null);
                  $q->orWhere('until','>',$now);
                });
            }
        }

        $items = $query->skip($skip)->limit($pagination)->get();
        foreach ($items as $key => $value) {
            $value->code_visible = false;
        }
        return $items;
    }

    private function getAutoload($page,$original_autoload){
        $autoload = $page + ($original_autoload - ($page%$original_autoload));
        return $autoload;
    }

    private function parsePages($request){

        $pagination = $this->pagination;
        $original_autoload = $this->original_autoload;
        if (isset($request->page)) {
            $page = $request->page;
            $autoload = $this->getAutoload($page,$original_autoload);
        }else{
            $page = 1;
            $autoload = $original_autoload;
        }

        return [$page,$pagination,$autoload,$original_autoload];
    }


    public function index(){
        $stores = Store::get();
        $letters = range('A','Z');
        $data = [];
        $options = [];
        
        foreach ($letters as $key => $value) {
            $data[$value] = []; 
        }

        $urls = Discount::orderBy('id','DESC')->where('site_name','!=',null)->select('site_name')->distinct()->limit(10)->pluck('site_name')->toArray();

        $popular1 = store::whereIn('website',$urls)->get();
        
        foreach ($stores as $key => $value) {
            $value->name = strtolower($value->name);
            $name = ucfirst($value->name);
            $first = substr($name,0,1); 
            array_push($data[$first], $value);
            if (!in_array(ucfirst($value->name), $options)) {
                array_push($options, ucfirst($value->name));
            }
        }
        
        sort($options);
        $options = json_encode($options);
        $items = json_encode(array_filter($data));
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Tiendas','url'=>'#']];
        $filter= null;
        
        $cupons = 1;
        return view('stores.index',compact('items','popular1','breadcrumbs','filter','options','cupons'));
           
    }

    public function items(Request $request,$slug){

        setlocale(LC_TIME, 'Spanish');

        $store_object = Store::where('slug',$slug)->first();

        if (!is_null($store_object)) {
        
            $pages = $this->parsePages($request);
            $page = $pages[0];
            $pagination = $pages[1];
            $autoload = $pages[2];
            $original_autoload = $pages[3];

            $now = Carbon::now();
            $sent = 0;
            $website = $store_object->id;
            
            $filter = $this->getFilter();
            if ($filter[0] === null) {
                $filter[0] = ['hide_expired'=>'on'];
            }
            $items = $this->getItems($page,$sent,$website,$now,$filter);
            $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Cupones','url'=>'#']];

            $month =  ucfirst($now->formatLocalized('%B'));
            $year = ucfirst($now->format('Y'));
            
            if(empty($store_object->description)){
                $first_name  =  '';
                if (isset($items[0])) {
                    $first_name = $items[0]->name;
                }
                $description = $first_name.' Nuestros códigos descuento para '.$store_object->name.' están actualizados en '.$month.' '.$year.' ✅ Utiliza estos cupones para comprar mas barato. Tienes algun código/cupón para '.$store_object->name.'? <a href="/seleccionar">Envialo y consigue premios</a>';
                $store_object->description = $description;
            }else{
                $description = $store_object->description;
            }
            $title = 'Códigos descuento y cupones '. $store_object->name .' '.$month.' - '.$year;
            $store_object->name = $title;
            $image = $store_object->image; 
            return view('cupon.index',compact('items','page','sent','pagination','autoload','original_autoload','breadcrumbs','filter','website','store_object','title','description','image'));
        
        }
        abort(404);
    }

    public function sent(Request $request){
        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];

        $sent = 1;
        $filter = $this->getFilter();
        $now = Carbon::now();
        $items = $this->getItems($page,$sent,'0',$now,$filter);
        
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Mis Cupones','url'=>'#']];
        $website = 0;
        return view('cupon.index',compact('items','page','sent','pagination','autoload','original_autoload','breadcrumbs','filter','website'));
    }

    public function copied(Request $request){
        Discount::find($request->id)->increment('copied');
    }
    public function liked(Request $request){
        $item = Discount::find($request->id);
        if ($request->action == 1) {
            $item->works =  $item->works+1;
        }else{
            $item->not_work =  $item->not_work+1;
        }
        $item->save();
    }

    public function save(Request $request){
    
        $data = $request->all();
        $site_name = '';
        $url = '';
        if (empty($data['site_url'])){
            return 'URL No Valida';
        }
        if (!filter_var($data['site_url'], FILTER_VALIDATE_URL)) {
            return 'URL No Valida';
        }

        $link = $this->getSiteName($data['site_url']);
        $site_name = $link[2];
        $url = $link[1];
        $store = $this->addStore($link);
        unset($data['site_url']);
        $item = new Discount;
        $item->url = $url;
        $item->site_name = $site_name;
        $item->store_id = $store->id;
        $item->user_id = Auth::id();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $item->$key = json_encode($value);
            }else{
                $item->$key = $value;
            }
        }
        
        if ( strlen($data['code']) > 0 ) {
            $find = ['í','á','ó','é','ú','Á','É','Í','Ó','Ú'];
            $replace = ['i','a','o','e','u','A','E','I','O','U'];
            $code = str_replace($find,$replace,$data['code']);
            $item->code =  $code;
        }

        $item->save();
        Session::flash('success','Cupón Compartido!');
        return $store->name;
    }


}
