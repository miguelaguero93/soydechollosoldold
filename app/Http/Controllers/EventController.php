<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\Traits\CommonHelpers;
use Carbon\Carbon;
use Storage;
use Auth;

class EventController extends Controller{

	use CommonHelpers;

    public function index(){

        setlocale(LC_TIME, 'Spanish');
        
        $now = Carbon::now();
        $from = Carbon::now()->subMonth();
        $items = Event::where('from','>',$from)->get(); 
        $month =  ucfirst($now->formatLocalized('%B'));
        $year =  $now->format('Y');
        
        foreach ($items as $key => $value) {
            $value->visible = true;
        }

        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Cupones','url'=>'#']];
        $filter = null;
        $title = 'Los mejores sorteos y eventos de internet – Soydechollos';
        $description = 'Cientos de sorteos para ganar magníficos premios, completamente gratis. Entra ahora y participa.';

        return view('events.index', compact('items','month','year','breadcrumbs','filter','title','description'));
    
    }

    public function save(Request $request){
    
        $data = $request->all();
        $filename = null;

        if (isset($data['image'])) {
            $img = $data['image'];
            $filename  = 'eventos/'.$img->getClientOriginalName();
            $content = file_get_contents($img->getRealPath());
            Storage::disk('public')->put($filename,$content);
        }
        
        $url = '';

        if (!empty($data['site_url'])){
            if (filter_var($data['site_url'], FILTER_VALIDATE_URL)) {
                $url = $data['site_url'];
            }
        }

        $item = new Event;
        $item->url = $url;
        $item->user_id = Auth::id();
        $item->name = $data['name'];
        $item->type_id = $data['type_id'];
        $item->from = $data['from'];
        $item->to = $data['until'];
        $item->description = $data['description'];
        $item->imagen = $filename;
        $item->sexual_content = (int) $data['sexual_content'];
        $item->save();
        return $item->id;

    }


}
