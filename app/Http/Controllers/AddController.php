<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Category;
use App\Chollo;
use App\Brand;
use App\Store;
use Auth;
use Mail;
use Session;
use Response;

class AddController extends Controller{
	
	public function sitemap(){
		$items = Chollo::all();
		$file = "sitemap.xml";
		$s = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
		foreach ($items as $key => $value) {
			$day = substr($value->updated_at, 0, 10);
			$time = substr($value->updated_at, 11, 16).'+01:00';
			$slug = html_entity_decode($value->slug);
			$slug = str_replace('&', '&amp;', $slug);
			$s .= "<url><loc>https://soydechollos.com/".$slug."</loc><lastmod>".$day."T".$time."</lastmod><priority>0.51</priority></url>";
		}
		$s .= '</urlset>';
    	$handle = fopen($file, 'w+');
    	fwrite($handle, $s);
    	fclose($handle);
    	$headers = array(
        	'Content-Type' => 'application/xml',
    	);
	    
    	return Response::download($file, $file, $headers);
	
	}
	public function pcontact(Request $request){
		$r = $request->all();

        Mail::send('emails.contact', compact('r'),  function($message){
            $to = setting('admin.email');
            $message->to($to)->subject('Contacto en soydechollos.');
        });
        Session::flash('success','Hemos recibido tu mensaje. Te responderemós tan rápido como nos sea posible.');
        return redirect('/contacto');
	}
	public function contact(){
	    $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Contactános','url'=>'#']];
	    $title = 'Contactanos';
	    $description = 'En cualquier caso, sea cual sea tu duda, estamos aquí para responderte tan rápido como nos sea posible.';
	    return view('contact', compact('breadcrumbs','title','description'));		   
    }
	public function choose(){
		$categories = Category::orderBy('order')->get(); 	    
	    $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Que deseas compartir?','url'=>'#']];
	    $logged = (int) Auth::id();
	    $title = 'Comparte un Chollo';
	    $description = 'Selecciona el tipo de chollo que vas a compartir con la comunidad, chollo, código descuento o sorteo';
	    return view('choose', compact('breadcrumbs','logged','title','description'));		   
    }
    
    public function cupon(){
		$categories = Category::orderBy('order')->get(); 	    
	    $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Que deseas compartir?','url'=>'/seleccionar'],['name'=>'Nuevo Cupon','url'=>'#']];
	    $title = 'Compartir un cupón para una tienda';
	    return view('cupon.create', compact('categories','breadcrumbs','title'));		   
    }

    public function evento(){
		$categories = Category::orderBy('order')->get(); 	    
	    $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Que deseas compartir?','url'=>'/seleccionar'],['name'=>'Nuevo Evento o Sorteo','url'=>'#']];
	    $title = 'Compartir un sorteo o un evento';
	    return view('events.create', compact('categories','breadcrumbs','title'));		   
    }
    public function chollo(){
		$categories = Category::where('parent_id',null)->orderBy('order')->get();
		$admin = (int) !Auth::user()->hasRole('user'); 	    
	    $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Que deseas compartir?','url'=>'/seleccionar'],['name'=>'Nuevo Chollo','url'=>'#']];

	    $title = 'Comparte un Chollo';
	    
    	$all_categories = collect(); 
		$brands = collect();
		$stores = collect();
	    return view('add.index2', compact('categories','breadcrumbs','admin','title','all_categories','brands','stores'));		   
    }


    public function editar($id){
    	$item = Chollo::with('keywords')->findOrFail($id);
    	if ($item->user_id != Auth::id() && Auth::user()->role_id == 2) {
    		abort(404);
    	}
		$categories = Category::where('parent_id',null)->orderBy('order')->get();
		$admin = (int) !Auth::user()->hasRole('user'); 	    
	    $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name'=>'Que deseas compartir?','url'=>'/seleccionar'],['name'=>'Nuevo Chollo','url'=>'#']];
	    $title = 'Editar Chollo';
	    if ($admin==1) {
	    	$all_categories = Category::all(); 
			$brands = Brand::all();
			$stores = Store::all(); 
	    }else{
	    	$all_categories = collect(); 
			$brands = collect();
			$stores = collect();
	    }
	    return view('add.index2', compact('categories','breadcrumbs','admin','title','item','all_categories','brands','stores'));		   
    }
}
