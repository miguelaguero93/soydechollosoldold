<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Brand;
use App\Category;
use App\Chollo;
use App\CholloCategory;
use App\Diseno;
use App\Keyword;
use App\Store;
use App\Traits\CommonHelpers;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Session;

class HomeController extends Controller
{
    use CommonHelpers;
    private $pagination = 16;
    private $original_autoload = 5;
    private $breadcrumbs = [];
    private $children = [];

    public function filter(Request $request)
    {
        $redirect = $request['url'];
        unset($request['_token']);
        unset($request['url']);
        if (isset($request['topfilter'])) {
            unset($request['topfilter']);
            $filter = json_encode($request->all());
            Cookie::queue('topfilter', $filter, 525600);
        } elseif (isset($request['commfilter'])) {
            unset($request['commfilter']);
            $filter = json_encode($request->all());
            Cookie::queue('commfilter', $filter, 525600);
        } else {
            $filter = json_encode($request->all());
            Cookie::queue('filter', $filter, 525600);
        }

        return redirect($redirect);
    }

    private function getBanner()
    {
        return Banner::where('active', 1)->inRandomOrder()->limit(1)->get()->first();
    }

    public function index(Request $request)
    {
        if (isset($request->login) && !is_null(Auth::user())) {
            return redirect('https://soydechollos.com/api/foro');
        }

        $this->checkForReferal($request);

        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        $popular = 0;
        $new = 0;
        $favorites = 0;
        $category_id = 0;
        $sent = 0;
        $commented = 0;
        $store = 0;
        $tag = 0;
        $user_id = 0;

        $filter = $this->getFilter();

        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);

        $this->processItem($items);
        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/']];
        $ads = Diseno::orderBy('position', 'DESC')->get();
        $show_bar = true;

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'favorites', 'brand', 'popular', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'filter', 'ads', 'user_id', 'show_bar'));
    }

    public function sidebar()
    {
        $top = $this->getTopVoted();
        $comments = $this->getLatestComments();

        return [$top, $comments];
    }

    public function populares(Request $request)
    {
        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        // default one week
        $popular = 1;
        $favorites = 0;
        $category_id = 0;
        $new = 0;
        $sent = 0;
        $commented = 0;
        $store = 0;
        $tag = 0;
        $user_id = 0;
        $filter = $this->getFilter(true);

        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);

        $this->processItem($items);

        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Populares', 'url' => '#']];
        $ads = Diseno::orderBy('position', 'DESC')->get();
        $with_period = true;
        $show_bar = true;
        $title = ' Ofertas y chollos más populares';

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'brand', 'favorites', 'popular', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'filter', 'ads', 'with_period', 'title', 'user_id', 'show_bar'));
    }

    public function nuevos(Request $request)
    {
        $days = 7;
        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        $popular = 0;
        $favorites = 0;
        $category_id = 0;
        $sent = 0;
        $commented = 0;
        $store = 0;
        $tag = 0;
        $new = 1;
        $user_id = 0;
        $filter = $this->getFilter(true);
        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);

        $this->processItem($items);

        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Nuevos', 'url' => '#']];
        $ads = Diseno::orderBy('position', 'DESC')->get();
        $with_period = true;
        $show_bar = true;
        $title = ' Ofertas y chollos más recientes';

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'favorites', 'popular', 'brand', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'filter', 'ads', 'with_period', 'title', 'user_id', 'show_bar'));
    }

    public function comentados(Request $request)
    {
        $commented = 1;
        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        $favorites = 0;
        $popular = 0;
        $new = 0;
        $category_id = 0;
        $sent = 0;
        $store = 0;
        $tag = 0;
        $user_id = 0;
        $filter = $this->getFilter(true);
        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);
        $this->processItem($items);

        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Comentados', 'url' => '#']];
        $ads = Diseno::orderBy('position', 'DESC')->get();
        $with_period = true;
        $show_bar = true;
        $title = ' Ofertas y chollos más comentados';

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'favorites', 'popular', 'new', 'brand', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'filter', 'ads', 'with_period', 'title', 'user_id', 'show_bar'));
    }

    public function favorites(Request $request)
    {
        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        $category_id = 0;
        $favorites = 1;
        $popular = 0;
        $new = 0;
        $sent = 0;
        $commented = 0;
        $store = 0;
        $tag = 0;
        $filter = $this->getFilter();
        $user_id = 0;
        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);
        $this->processItem($items);

        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Favoritos', 'url' => '#']];
        $ads = Diseno::orderBy('position', 'DESC')->get();

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'favorites', 'brand', 'popular', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'filter', 'ads', 'user_id'));
    }

    public function category(Request $request, $slug)
    {
        $store_object = Category::where('slug', $slug)->first();
        if (is_null($store_object)) {
            abort(404);
        }
        setlocale(LC_TIME, 'Spanish');

        $segments = $request->segments();
        $last_segment = end($segments);

        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        $favorites = 0;
        $popular = 0;
        $new = 0;
        $sent = 0;
        $commented = 0;
        $store = 0;
        $tag = 0;
        $category_id = $store_object->id;
        $user_id = 0;

        $with_period = false;
        if ($last_segment == 'populares') {
            $popular = 1;
            $with_period = true;
        }
        if ($last_segment == 'comentados') {
            $commented = 1;
            $with_period = true;
        }
        if ($last_segment == 'nuevos') {
            $new = 1;
            $with_period = true;
        }

        $filter = $this->getFilter($with_period);

        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);
        $this->processItem($items);
        $banner = $this->getBanner();

        $this->getCategories($store_object);
        $breadcrumbs = array_reverse($this->breadcrumbs);

        array_unshift($breadcrumbs, ['name' => 'Inicio', 'url' => '/']);

        $now = Carbon::now();
        $month = $now->formatLocalized('%B');
        $year = $now->format('Y');

        $children = $this->children;
        if (empty($store_object->description)) {
            $count = Chollo::whereIn('category_id', $children)->where('approved', 1)->count();
            $best = Chollo::whereIn('category_id', $children)->where('approved', 1)->where('created_at', '>', $now->subMonth())->orderBy('votes', 'DESC')->select('name')->limit(2)->get();
            $seo_string = '';
            foreach ($best as $key => $value) {
                $seo_string .= ' '.$value->name;
            }
            $description = ucfirst($month).' '.$year.' mejor precio para comprar '.ucfirst($store_object->name).' ➡️ '.$count.' ofertas baratas '.$seo_string;
            $store_object->description = $description;
        } else {
            $description = $store_object->description;
        }
        $title = ucwords('Descuentos y ofertas '.$store_object->name.' - '.$month.' - '.$year);
        $store_object->name = $title;

        $image = $store_object->image;
        $ads = Diseno::orderBy('position', 'DESC')->get();
        $show_bar = true;

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'favorites', 'brand', 'popular', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'filter', 'title', 'description', 'image', 'store_object', 'ads', 'user_id', 'with_period', 'show_bar'));
    }

    public function sent(Request $request)
    {
        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        $favorites = 0;
        $popular = 0;
        $new = 0;
        $category_id = 0;
        $sent = 1;
        $commented = 0;
        $store = 0;
        $tag = 0;
        $user_id = 0;
        $filter = $this->getFilter();
        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);
        $this->processItem($items);

        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Enviados', 'url' => '#']];
        $ads = Diseno::orderBy('position', 'DESC')->get();

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'favorites', 'brand', 'popular', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'filter', 'ads', 'user_id'));
    }

    public function user(Request $request, $id, $name)
    {
        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        $favorites = 0;
        $popular = 0;
        $new = 0;
        $category_id = 0;
        $sent = 0;
        $commented = 0;
        $store = 0;
        $tag = 0;
        $user_id = $id;
        $filter = $this->getFilter();
        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);
        $this->processItem($items);

        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => $name, 'url' => '#']];
        $banner = $this->getBanner();
        $ads = Diseno::orderBy('position', 'DESC')->get();

        $stats = $this->getStats($id);
        $logged = (int) Auth::id();
        $follows = 0;
        if (!is_null($logged)) {
            $existing = DB::table('user_followers')->where('user_id', $id)->where('follower_id', $logged)->first();
            if (!is_null($existing)) {
                $follows = 1;
            }
        }
        $user = User::select('name', 'avatar', 'id')->findOrFail($id);

        return view('index.user', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'favorites', 'popular', 'brand', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'filter', 'ads', 'user_id', 'stats', 'logged', 'follows', 'id', 'user'));
    }

    private function getStats($id)
    {
        $chollos = DB::table('chollos')->where('user_id', $id)->count();
        $coupons = DB::table('discounts')->where('user_id', $id)->count();
        $favorites = DB::table('favorites')->where('user_id', $id)->count();
        $keywords = DB::table('user_keywords')->where('user_id', $id)->count();

        $highest = (int) DB::table('chollos')->where('user_id', $id)->max('votes');
        $average = (int) DB::table('chollos')->where('user_id', $id)->avg('votes');
        $prizes = DB::table('canjes')->where('user_id', $id)->count();

        $followers = DB::table('user_followers')->where('user_id', $id)->count();
        $following = DB::table('user_followers')->where('follower_id', $id)->count();

        $comments = DB::table('comments')->where('user_id', $id)->count();
        $likes = DB::table('comments_likes')->where('user_id', $id)->count();
        $votes = DB::table('user_votes')->where('user_id', $id)->count();

        return [$chollos, $coupons, $favorites, $keywords, $highest, $average, $followers, $following, $prizes, $comments, $likes, $votes];
    }

    public function brand($slug, Request $request)
    {
        $slug = str_replace('-', ' ', $slug);
        setlocale(LC_TIME, 'Spanish');

        $segments = $request->segments();
        $last_segment = end($segments);

        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand_object = Brand::where('value', $slug)->first();
        if (!$brand_object) {
            $brand_object = Brand::where('value', 'LIKE', '%'.$slug.'%')->first();
        }
        $brand = 0;

        $popular = 0;

        $favorites = 0;
        $new = 0;
        $category_id = 0;
        $sent = 0;
        $commented = 0;
        $tag = 0;
        $user_id = 0;
        $with_period = false;
        $store = 0;
        if ($last_segment == 'populares') {
            $popular = 1;
            $with_period = true;
        }
        if ($last_segment == 'comentados') {
            $commented = 1;
            $with_period = true;
        }
        if ($last_segment == 'nuevos') {
            $new = 1;
            $with_period = true;
        }

        if (!is_null($brand_object)) {
            $brand = $brand_object->id;
        } else {
            abort(404);
        }
        $filter = $this->getFilter($with_period);
        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);
        $this->processItem($items);

        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Marcas', 'url' => '/marcas'], ['name' => ucwords($slug), 'url' => '#']];

        $now = Carbon::now();
        $month = $now->formatLocalized('%B');
        $year = ucfirst($now->format('Y'));

        if (empty($brand_object->title)) {
            $title = ucwords('Ofertas '.ucwords($brand_object->value).' - '.$month.' '.$year);
            $brand_object['name'] = $title;
        } else {
            $title = ucwords($brand_object->title);
        }

        if (empty($brand_object->description)) {
            $count = Chollo::where('brand_id', $brand_object->id)->count();
            // Log::info($count);
            $description = 'Tenemos '.$count.' ofertas y chollos para '.ucwords($brand_object->value).'  actualizadas y revisadas en '.$month.' - '.$year.', entra ahora si quieres comprar barato.';
            // $description = 'Tenemos mas de 500 marcas con ofertas y chollos, aprovecha los grandes descuentos en: $top5marcasoultimascincomarcas, actualizadas en $mes $año';
            $brand_object->description = $description;
        } else {
            $description = $brand_object->description;
        }

        $image = $brand_object->image;
        $ads = Diseno::orderBy('position', 'DESC')->get();
        $show_bar = true;

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'favorites', 'store', 'popular', 'new', 'sent', 'commented', 'brand', 'tag', 'banner', 'breadcrumbs', 'brand_object', 'filter', 'title', 'description', 'image', 'ads', 'user_id', 'with_period', 'show_bar'));
    }

    public function store($slug, Request $request)
    {
        setlocale(LC_TIME, 'Spanish');

        $segments = $request->segments();
        $last_segment = end($segments);
        $brand = 0;

        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $store_object = Store::where('slug', $slug)->first();
        $store = 0;

        $popular = 0;

        $favorites = 0;
        $new = 0;
        $category_id = 0;
        $sent = 0;
        $commented = 0;
        $tag = 0;
        $user_id = 0;
        $with_period = false;
        if ($last_segment == 'populares') {
            $popular = 1;
            $with_period = true;
        }
        if ($last_segment == 'comentados') {
            $commented = 1;
            $with_period = true;
        }
        if ($last_segment == 'nuevos') {
            $new = 1;
            $with_period = true;
        }

        if (!is_null($store_object)) {
            $store = $store_object->id;
        } else {
            abort(404);
        }
        $filter = $this->getFilter($with_period);
        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);
        $this->processItem($items);

        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Tienda', 'url' => '/tiendas'], ['name' => ucwords($store_object->visible_name), 'url' => '#']];

        $now = Carbon::now();
        $month = $now->formatLocalized('%B');
        $year = ucfirst($now->format('Y'));

        if (empty($store_object->title)) {
            $title = ucwords('Ofertas '.$store_object->visible_name.' - '.$month.' '.$year);
            $store_object['name'] = $title;
        } else {
            $title = $store_object->title;
        }

        if (empty($store_object->description)) {
            $count = Chollo::where('store_id', $store_object->id)->count();
            $description = 'Tenemos '.$count.' ofertas y chollos para '.$store_object->visible_name.'  actualizadas y revisadas en '.$month.' - '.$year.', entra ahora si quieres comprar barato.';
            $store_object->description = $description;
        } else {
            $description = $store_object->description;
        }

        $image = $store_object->image;
        $ads = Diseno::orderBy('position', 'DESC')->get();
        $show_bar = true;

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'brand', 'favorites', 'popular', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'store_object', 'filter', 'title', 'description', 'image', 'ads', 'user_id', 'with_period', 'show_bar'));
    }

    public function tags(Request $request, $tag)
    {
        setlocale(LC_TIME, 'Spanish');

        $tag = str_replace('-', ' ', $tag);

        $pages = $this->parsePages($request);
        $page = $pages[0];
        $pagination = $pages[1];
        $autoload = $pages[2];
        $original_autoload = $pages[3];
        $brand = 0;

        $favorites = 0;
        $popular = 0;
        $new = 0;
        $category_id = 0;
        $sent = 0;
        $commented = 0;
        $store = 0;
        $user_id = 0;
        $filter = $this->getFilter();
        $items = $this->getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user_id, $brand);
        $this->processItem($items);

        $banner = $this->getBanner();
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Enviados', 'url' => '#']];

        $now = Carbon::now();

        $month = $now->formatLocalized('%B');
        $year = ucfirst($now->format('Y'));
        $title = 'Descuentos, códigos y cupones para comprar '.$tag.' '.$month.' - '.$year;
        $seo_string = '';
        if ($items->count() > 0) {
            if ($items->count() > 1) {
                foreach ($items->take(2) as $key => $value) {
                    $seo_string .= ' '.$value->name;
                }
            } else {
                foreach ($items->take(1) as $key => $value) {
                    $seo_string .= ' '.$value->name;
                }
            }
        }
        $description = 'Comprar '.$tag.' online, aquí encontraras los últimos códigos, descuentos y cupones para comprar al precio más barato - '.$seo_string;

        $description = $description;
        $name = $title;
        $details = null;

        $item = Keyword::where('keyword', $tag)->first();
        if (!is_null($item)) {
            if (!empty($item->seo_title)) {
                $title = $item->seo_title;
            }
            if (!empty($item->seo_description)) {
                $description = $item->seo_description;
            }
        }

        $store_object = compact('description', 'title', 'name', 'details');
        $ads = Diseno::orderBy('position', 'DESC')->get();

        return view('index.index', compact('items', 'page', 'pagination', 'autoload', 'original_autoload', 'category_id', 'brand', 'favorites', 'popular', 'new', 'sent', 'commented', 'store', 'tag', 'banner', 'breadcrumbs', 'store', 'filter', 'title', 'description', 'store_object', 'ads', 'user_id'));
    }

    private function processItem($items)
    {
        foreach ($items as $key => $value) {
            $value->favorite = false;
        }

        if (!Auth::guest()) {
            $user_id = Auth::id();
            $ids = $items->pluck('id');

            $user_favorites = DB::table('favorites')->where('user_id', $user_id)->whereIn('chollo_id', $ids)->get();
            foreach ($user_favorites as $key => $value) {
                $item = $items->firstWhere('id', $value->chollo_id);
                if (!is_null($item)) {
                    $item->favorite = true;
                }
            }

            $user_votes = DB::table('user_votes')->where('user_id', $user_id)->whereIn('chollo_id', $ids)->get();
            foreach ($user_votes as $key => $value) {
                $item = $items->firstWhere('id', $value->chollo_id);
                if (!is_null($item)) {
                    $item->user_vote = $value->value;
                }
            }
        }

        return $items;
    }

    private function checkForReferal($request)
    {
        if (isset($request->r)) {
            Cookie::queue('invited_friend', $request->r, 525600);
        }
    }

    private function findChildrenCategories($category_id)
    {
        array_push($this->children, $category_id);
        $children = Category::where('parent_id', $category_id)->get();
        foreach ($children as $key => $value) {
            $this->findChildrenCategories($value->id);
        }
    }

    private function getItems($page, $pagination, $category_id, $favorites, $popular, $new, $sent, $commented, $store, $tag, $filter, $user, $brand = 0)
    {
        $skip = ($page - 1) * $pagination;
        // Log::info($skip);
        // Log::info($page);
        // Log::info($pagination);

        $query = Chollo::with('category', 'user:id,avatar,name', 'store:id,name,color,slug,visible_name');

        if (!is_null($category_id) && $category_id > 0) {
            $this->findChildrenCategories($category_id);
            $children = $this->children;
            $query->whereIn('category_id', $children);
        }

        if ($favorites == 1) {
            $user_favorites =
            $user_id = Auth::id();
            $user_favorites = DB::table('favorites')->where('user_id', $user_id)->pluck('chollo_id')->toArray();
            $query->whereIn('id', $user_favorites);
        }

        if ($sent == 1) {
            $user_id = Auth::id();
            $query->where('user_id', $user_id);
        }

        if ($user > 0) {
            $query->where('user_id', $user);
        }

        if ($new != 0) {
            $query->where('created_at', '>', $new);
        }

        if ($store != 0) {
            $query->where('store_id', $store);
        }

        if ($brand != 0) {
            $query->where('brand_id', $brand);
        }

        if ($popular == 1 || $commented == 1 || $new == 1) {
            $from = $this->getFrom($filter[0]);

            if (!is_null($from)) {
                $query->where('updated_at', '>', $from);
            }

            if ($popular == 1) {
                $query->where('votes', '>', 0)->orderBy('votes', 'DESC');
            }
            if ($commented == 1) {
                $query->where('comments_count', '>', 0)->orderBy('comments_count', 'DESC');
            }
            if ($new == 1) {
                $query->orderBy('created_at', 'DESC');
            }
        }

        if (!empty($tag)) {
            $c_ids = Keyword::where('keyword', $tag)->pluck('chollo_id')->toArray();
            $query->whereIn('id', $c_ids);
        }

        if ($commented == 0 && $popular == 0) {
            $query->orderBy('updated_at', 'DESC');
        }

        if (!is_null($filter[0])) {
            if (isset($filter[0]['hide_expired'])) {
                $date = Carbon::now()->toDateTimeString();
                $query->where('available', 1)->where(function ($q) use ($date) {
                    $q->orWhere('until', null);
                    $q->orWhere('until', '>', $date);
                });
            }
            if (isset($filter[0]['from_spain'])) {
                $query->where('country', 'España');
            }
        }

        $items = $query->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'brand_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from', 'snippet')->skip($skip)->limit($pagination)->get();

        if (!is_null($category_id) && $category_id > 0) {
            // Log::info('test 1');
            if ($page == 1 && count($items) == 0) {
                // Log::info('test 2');
                $chollosId = CholloCategory::where('category_id', $category_id)->select('chollo_id')->get();
                $new_query = Chollo::with('category', 'user:id,avatar,name', 'store:id,name,color,slug,visible_name')->whereIn('id', $chollosId);
                $chollos = $new_query->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'brand_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from', 'snippet')->get();
                $items = $items->merge($chollos);
            // Log::info('test 3');
            } elseif (count($items) > 0) {
                // Log::info('test 4');
                $chollosId = CholloCategory::where('category_id', $category_id)->select('chollo_id')->get();
                $new_query = Chollo::with('category', 'user:id,avatar,name', 'store:id,name,color,slug,visible_name')->whereIn('id', $chollosId);
                // Log::info('test 5');
                if ($page == 1 && count($items) == 16 || $page != 1) {
                    // Log::info('test 6');
                    if ($page == 1) {
                        $from = date('Y-m-d H:i:s');
                    } else {
                        $from = date($items[0]['updated_at']);
                    }
                    // Log::info($from);
                    $to = date($items[count($items) - 1]['updated_at']);
                    // Log::info($to);
                    $new_query->whereBetween('updated_at', [$to, $from]);
                }
                // Log::info('test 7');
                $chollos = $new_query->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'brand_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from', 'snippet')->get();
                $items = $items->merge($chollos);
            }
        }

        return $items;
    }

    private function parsePages($request)
    {
        $pagination = $this->pagination;
        $original_autoload = $this->original_autoload;
        if (isset($request->page)) {
            $page = $request->page;
            $autoload = $this->getAutoload($page, $original_autoload);
        } else {
            $page = 1;
            $autoload = $original_autoload;
        }

        return [$page, $pagination, $autoload, $original_autoload];
    }

    private function getAutoload($page, $original_autoload)
    {
        $autoload = $page + ($original_autoload - ($page % $original_autoload));

        return $autoload;
    }

    public function page($slug)
    {
        $page = DB::table('pages')->where('slug', $slug)->first();
        if (is_null($page)) {
            abort(404);
        }
        $filter = null;
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => $page->title, 'url' => '#']];

        return view('faq', compact('breadcrumbs', 'filter', 'page'));
    }

    public function monedas()
    {
        $auth = null;
        $total_coins_today = 0;
        $prizes = DB::table('prices')->get();
        if (Auth::check()) {
            $auth = Auth::user();
            $today = Carbon::now()->startOfDay()->toDateTimeString();
            $coins_today = DB::table('coins')->where('user_id', $auth->id)->where('method_id', '!=', 3)->where('created_at', '>=', $today)->get();
            foreach ($coins_today as $key => $value) {
                $total_coins_today += $value->amount;
            }
        }
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Monedas', 'url' => '#']];
        $filter = null;

        return view('monedas.index', compact('auth', 'total_coins_today', 'prizes', 'breadcrumbs', 'filter'));
    }

    public function setdisplay(Request $request)
    {
        $ref = url()->previous();
        if ($request->d == 'horizontal') {
            return redirect($ref)->cookie('display', true, 525600);
        } else {
            Cookie::queue(Cookie::forget('display'));

            return redirect($ref);
        }
    }

    public function getMore(Request $request)
    {
        $pagination = $this->pagination;
        if ($request->popular == 1 || $request->commented == 1 || $request->new == 1) {
            $with_period = true;
        } else {
            $with_period = false;
        }
        $filter = $this->getFilter($with_period);
        $items = $this->getItems($request->page, $pagination, $request->category_id, $request->favorites, $request->popular, $request->new, $request->sent, $request->commented, $request->store, $request->tag, $filter, $request->user_id, $request->brand);
        $this->processItem($items);

        return $items;
    }

    public function setfooter(Request $request)
    {
        $p = url()->previous();
        $categories = DB::table('categories')->orderBy('order')->select('id', 'name', 'slug')->get();
        Session::put('categories', $categories);

        return redirect($p);
    }
}
