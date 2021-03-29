<?php

namespace App\Http\Controllers;

use App\Brand;
use App\BrandsWord;
use App\Category;
use App\Chollo;
use App\CholloReport;
use App\Comment;
use App\Keyword;
use App\Notification;
use App\Traits\CommonHelpers;
use App\User;
use App\User_keyword;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Mail;
use Session;
use Storage;

class CholloController extends Controller
{
    use CommonHelpers;

    private $breadcrumbs = [];

    public function report($id)
    {
        $logged = Auth::id();
        $existing = CholloReport::where('user_id', $logged)->where('chollo_id', $id)->first();

        if (is_null($existing)) {
            $c = Chollo::find($id);
            $r = $c->reported + 1;
            $c->reported = $r;
            if ($r == 3) {
                $c->approved = 0;
            }
            $c->save();
            $path = '<a href="/panel/chollos/'.$c->id.'/edit">'.$c->name.'</a>';
            $report = new CholloReport();
            $report->chollo_id = $id;
            $report->user_id = $logged;
            $report->total = $r;
            $report->path = $path;
            $report->save();

            return  'Oferta reportada. Muchas gracias!';
        } else {
            return  'Ya has reportado esta oferta. Muchas gracias!';
        }
    }

    public function dismiss(Request $request)
    {
        $c = Chollo::findOrFail($request->id);
        $c->reviewed = 1;
        $c->save();
    }

    public function publish(Request $request)
    {
        $c = Chollo::findOrFail($request->id);
        $c->approved = 1;
        $c->save();
        $value = 'Tu chollo <b>'.$c->name.'</b> ha sido aprobado!';
        $link = '/'.$c->slug;
        $date = date('Y-m-d H:i:s', time());
        Notification::insert(['user_id' => $c->user_id, 'value' => $value, 'link' => $link, 'created_at' => $date]);

        return back()->with(['message' => 'Aprobado!.', 'alert-type' => 'success']);
    }

    public function savePicture(Request $request)
    {
        $data = $request->all();

        $item = Chollo::find($data['id']);
        if (isset($data['image'])) {
            $img = $data['image'];
            $fileinfo = getimagesize($img->getRealPath());
            if ($fileinfo) {
                $size = $img->getSize();
                if ($size < 2000000) {
                    $content = file_get_contents($img->getRealPath());
                    $filename = 'oferta/'.$item->slug.'.'.$img->extension();
                    Storage::disk('public')->put($filename, $content);
                    $item->image = env('APP_URL').'/storage/'.$filename;
                    $item->image_small = env('APP_URL').'/storage/'.$filename;
                    $item->save();
                } else {
                    $item->image = null;
                    Session::flash('Archivo demasiado grande');
                }
            } else {
                $item->image = null;
                Session::flash('Archivo seleccionado no es una imagen valida');
            }
        } else {
            $item->image = null;
            Session::flash('La imagen no pudo ser guardada');
        }
        $item->save();
    }

    public function related(Request $request)
    {
        return $this->getRelated($request->category_id, $request->item_id);
    }

    public function item($name)
    {
        if (Auth::user()) {
            $logged = Auth::user();
        } else {
            $logged = 0;
        }

        $item = Chollo::with('user:id,name,avatar', 'category', 'comments.user:id,name,avatar', 'comments.children.user:id,name,avatar', 'store:id,name,color,slug', 'keywords')->where('slug', $name)->first();

        if (is_null($item)) {
            abort(404);
        }

        if ($item->approved == 0 && $logged->id != $item->user_id) {
            abort(404);
        }

        $voted = 0;
        $favorite = 0;
        $user_keywords = null;
        $item_comments = $item->comments;
        foreach ($item_comments as $key => $value) {
            $value->replying = false;
            $value->visibleChildren = false;
            $value->reply = '';
            $value->vote = 0;
        }

        if (Auth::check()) {
            $user_id = Auth::id();
            $user_vote = DB::table('user_votes')->where('chollo_id', $item->id)->where('user_id', $user_id)->first();

            if (!is_null($user_vote)) {
                $voted = $user_vote->value;
            }

            $user_vote = DB::table('favorites')->where('chollo_id', $item->id)->where('user_id', $user_id)->first();
            if (!is_null($user_vote)) {
                $favorite = 1;
            }

            $comments_ids = $item_comments->pluck('id');
            $user_likes = DB::table('comments_likes')->where('user_id', $user_id)->whereIn('comment_id', $comments_ids)->get();

            foreach ($user_likes as $key => $value) {
                $comment = $item_comments->firstWhere('id', $value->comment_id);
                if (!is_null($comment)) {
                    $comment->vote = $value->value;
                }
            }

            $user_keywords = User_keyword::where('user_id', $logged->id)->pluck('keyword')->toArray();
        }
        $filter = $this->getFilter();
        $from = Carbon::now()->subHours(48)->toDateTimeString();
        // $top = $this->getTopVoted($from);
        // $comments = $this->getLatestComments($from);
        // $related=  $this->getRelated($item->category_id,$item->id);
        $keywords = $this->parseKeywords($item, $user_keywords);
        $category = $item->category;

        if (!is_null($category)) {
            $this->getCategories($category);
        }
        $breadcrumbs = array_reverse($this->breadcrumbs);

        array_unshift($breadcrumbs, ['name' => 'Inicio', 'url' => '/']);
        array_push($breadcrumbs, ['name' => 'Chollo', 'url' => '#']);

        if (empty($item->seo_title)) {
            $title = $item->name;
        } else {
            $title = $item->seo_title;
        }

        if (empty($item->seo_description)) {
            $description = 'Comprar en oferta ➡️ '.strip_tags(html_entity_decode($item->description));
        } else {
            $description = $item->seo_description;
        }

        $image = $item->image;

        return view('item.index', compact('item', 'voted', 'favorite', 'logged', 'item_comments', 'keywords', 'breadcrumbs', 'filter', 'title', 'description', 'image'));
    }

    private function parseKeywords($item, $user_keywords)
    {
        foreach ($item->keywords as $value) {
            $value->selected = false;
            if (!is_null($user_keywords)) {
                if (in_array(strtolower($value->keyword), $user_keywords)) {
                    $value->selected = true;
                }
            }
        }

        return $item->keywords;
    }

    private function getRelated($category_id, $item_id)
    {
        $date = Carbon::now()->toDateTimeString();

        $items = Chollo::where('category_id', $category_id)->where('id', '!=', $item_id)->where('available', 1)->where('approved', 1)->where(function ($q) use ($date) {
            $q->orWhere('until', null);
            $q->orWhere('until', '>', $date);
        })->orderBy('updated_at', 'DESC')->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes')->limit(4)->get();

        return $items;
    }

    public function keyword(Request $request)
    {
        $auth_id = Auth::id();
        $k = strtolower(trim($request->keyword));
        $e = User_keyword::where('user_id', $auth_id)->where('keyword', $k)->delete();
        if ($e == 0) {
            $item = new User_keyword();
            $item->keyword = $k;
            $item->user_id = $auth_id;
            $item->save();

            return true;
        } else {
            return false;
        }

        return $e;
    }

    private function parseLinks($text)
    {
        $reg_exUrl = '~(?<!")(https://)\S+($|\s)~i';
        $no_tags = trim(preg_replace('#<[^>]+>#', ' ', $text));

        $anchors = preg_match_all($reg_exUrl, $no_tags, $urls);
        $array = [];
        if ($anchors) {
            foreach ($urls[0] as $key => $value) {
                $value = trim($value);
                if (!in_array($value, $array)) {
                    $replace = "<a target='_blank' href='".$value."'>".$value.'</a>';
                    $text = str_replace($value, $replace, $text);
                    array_push($array, $value);
                }
            }
        }

        return $text;
    }

    private function parseBase64Images($des, $slug)
    {
        preg_match_all('/src="(data:image\/[^;]+;base64[^"]+)"/i', $des, $matches);
        if (sizeof($matches[0]) > 0) {
            foreach ($matches[0] as $key => $value) {
                $path = 'https://soydechollos.com/';
                $name = 'storage/oferta/'.$slug.'_'.$key.'.jpg';
                $explode = explode(';', $value);
                $info_image = $explode[0];
                $data_image = $explode[1];
                $explode2 = explode(',', $data_image);
                $image = $explode2[1];
                $image = str_replace('"', '', $image);
                $data = base64_decode($image);
                // dd($name, $data);
                file_put_contents($name, $data);
                $name = $path.$name;
                $replace = "src='$name' alt='$slug'";
                $des = str_replace($value, $replace, $des);
            }

            return $des;
        }

        return $des;
    }

    public function save(Request $request)
    {
        $data = $request->all();

        $link = null;
        $store_id = $data['store_id'];
        $brand_id = $data['brand_id'];
        $available = $data['available'];
        $updated_at = $data['updated_at'];

        if (!empty($data['site_url'])) {
            if (!filter_var($data['site_url'], FILTER_VALIDATE_URL)) {
                return 'URL No Valida';
            }
            $full_url = $data['site_url'];
            $parse_url = $this->getSiteName($full_url);
            $link = $parse_url[1];

            if (is_null($store_id)) {
                $store = $this->addStore($parse_url);
                $store_id = $store->id;
            }
        } else {
            return 'Ingresa la URL del chollo.';
        }

        $keywords = $data['keywords'];
        $description = $data['description'];

        $image = $data['image'];
        $own_image = $data['own_image'];

        if (!empty($data['real_cat'])) {
            $data['category_id'] = $data['real_cat'];
        }

        unset($data['site_url']);
        unset($data['keywords']);
        unset($data['description']);
        unset($data['image']);
        unset($data['own_image']);
        unset($data['real_cat']);
        unset($data['store_id']);
        unset($data['brand_id']);
        unset($data['available']);
        unset($data['updated_at']);

        $image_changed = false;
        if (!is_null($data['id'])) {
            $item = Chollo::findOrFail($data['id']);
            if ($item->user_id != Auth::id() && Auth::user()->role_id == 2) {
                return 'No estas autorizado.';
            }
            $slug = $item->slug;
            if ($image != $item->image) {
                $image_changed = true;
            }
            if (!is_null($updated_at)) {
                $item->timestamps = false;
                $item->updated_at = $updated_at;
            }
        } else {
            $item = new Chollo();
            $slug = substr(nicename($data['name']), 0, 60);

            $existing = Chollo::where('slug', 'LIKE', $slug.'%')->count();
            if ($existing > 0) {
                $slug .= '-nuevo-'.$existing;
            }
            $item->user_id = Auth::id();
            $image_changed = true;
        }

        $item->full_url = $full_url;
        $item->slug = $slug;
        $item->link = $link;
        $item->store_id = $store_id;
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $item->$key = json_encode($value);
            } else {
                $item->$key = $value;
            }
        }

        if (!isset($data['from'])) {
            $item->from = null;
        }
        if (!isset($data['until'])) {
            $item->until = null;
        }

        $item->description = $this->parseBase64Images($description, $slug);
        $item->snippet = $this->generateSnippet($item->description);

        if (is_null($data['id'])) {
            if (setting('admin.offers_approved') == 1) {
                $item->approved = 1;
                $msg = 'Chollo Agregado!';
            } else {
                if (Auth::user()->role_id != 2) {
                    $item->approved = 1;
                    $msg = 'Chollo Agregado!';
                } else {
                    $msg = 'Chollo Agregado! Esperando aprobación.';
                }
            }
        } else {
            $msg = 'Chollo Editado';
        }

        if (is_null($brand_id)) {
            $brand_id = $this->brandItem($item->name, $item->category_id);
            if (!is_null($brand_id)) {
                $item->brand_id = $brand_id;
            }
        } else {
            $item->brand_id = $brand_id;
        }

        if (!is_null($available)) {
            $item->available = $available;
        }

        if (!$own_image && $image_changed) {
            $images = $this->getItemImage($slug, $image);
            $item->image = $images[0];
            if (is_null($images[1])) {
                $item->image_small = $images[0];
            } else {
                $item->image_small = $images[1];
            }
        }

        $item->save();
        $this->storeKeywords($keywords, $item->id);
        Session::flash('success', $msg);

        return [$item->id, $slug];
    }

    private function generateSnippet($description)
    {
        $stripped = strip_tags($description);
        $stripped = substr($stripped, 0, 70);
        $stripped = utf8_encode($stripped);

        return $stripped;
    }

    private function getItemImage($slug, $image)
    {
        try {
            $p = strpos($image, 'sgfm.elcorteingles.es');
            $p2 = strpos($image, 'cdn.grupoelcorteingles.es');
            if ($p !== false || $p2 !== false) {
                return [$image, null];
            }

            $path = 'storage/oferta/'.$slug;

            $wa = Image::make($image);
            $width = $wa->width();
            $height = $wa->height();
            $ext = 'jpg';
            $wa->encode($ext);
            $large_path = $path.'.'.$ext;
            $wa->save($large_path);

            $ratio = $width / $height;
            $new_width = 220;
            $new_height = $new_width / $ratio;

            $wa->resize($new_width, $new_height);
            $path_small = $path.'-220.'.$ext;
            $wa->save($path_small);

            $item_image = env('APP_URL').'/'.$large_path;
            $item_image_small = env('APP_URL').'/'.$path_small;
        } catch (\Exception $e) {
            $path_info = pathinfo($image);
            $extensions = ['jpg', 'JPG', 'png', 'PNG', 'jpeg', 'JPEG', 'webp'];
            $download_image = false;
            $path = 'storage/oferta/'.$slug;

            if (isset($path_info['extension']) && in_array($path_info['extension'], $extensions)) {
                $ext = $path_info['extension'];
                $ext = strtok($ext, '?');
                $path = 'storage/oferta/'.$slug.'.'.$ext;

                $download_image = $this->save_image($image, $path);
            } else {
                if (isset($path_info['dirname'])) {
                    $image = $path_info['dirname'];
                    $download_image = $this->save_image($image, $path);
                }
            }

            if ($download_image) {
                $item_image = env('APP_URL').'/'.$path;
            } else {
                $item_image = $image;
            }

            $item_image_small = null;
        }

        return [$item_image, $item_image_small];
    }

    private function addCategoryPool($category_id, $cleaned)
    {
        $array = [];
        foreach ($cleaned as $key => $value) {
            array_push($array, ['category_id' => $category_id, 'word' => $value]);
        }
        DB::table('category_words')->insert($array);
    }

    private function brandItem($name, $category_id)
    {
        $ignore = DB::table('ignore_words')->pluck('word')->toArray();
        $find = ['í', 'á', 'ó', 'é', 'ú'];
        $replace = ['i', 'a', 'o', 'e', 'u'];
        $name = strtolower(str_replace($find, $replace, $name));
        $name = preg_replace('/[^A-Za-z0-9ñ ]/', '', $name);
        $words = explode(' ', $name);
        $cleaned = [];
        foreach ($words as $key => $value) {
            if (!is_numeric($value) && strlen($value) > 1 && !in_array($value, $ignore)) {
                array_push($cleaned, $value);
            }
        }
        if (sizeof($cleaned) > 0) {
            foreach ($cleaned as $key => $value) {
                $brand = Brand::where('value', $value)->first();
                if (!is_null($brand)) {
                    return $brand->id;
                }
            }

            foreach ($cleaned as $key => $value) {
                $brand = BrandsWord::where('word', $value)->first();
                if (!is_null($brand)) {
                    $b = Brand::where('id', $brand->brand_id)->where('deleted_at', null)->first();
                    if (!is_null($b)) {
                        return $brand->brand_id;
                    }
                }
            }
        }

        return null;
    }

    private function save_image($inPath, $outPath)
    {
        $in = @fopen(public_path($inPath), 'rb');
        if ($in !== false) {
            $out = fopen(public_path($outPath), 'wb');
            while ($chunk = fread($in, 8192)) {
                fwrite($out, $chunk, 8192);
            }
            fclose($in);
            fclose($out);

            return true;
        }

        return false;
    }

    private function storeKeywords($keywords, $id)
    {
        $a = [];
        Keyword::where('chollo_id', $id)->delete();
        foreach ($keywords as $key => $value) {
            $slug = nicename($value);
            $item = ['chollo_id' => $id, 'keyword' => $value, 'slug' => $slug];
            array_push($a, $item);
        }
        if (sizeof($a)) {
            Keyword::insert($a);
        }
    }

    public function processJob(Request $request)
    {
        if ($request->key == '321') {
            $items = Chollo::where('approved', 1)->where('processed', 0)->get();
            foreach ($items as $key => $item) {
                $count = Chollo::where('user_id', $item->user_id)->count();
                $this->checkForAwards($item->user_id, $item->id, 5, $count);
                $this->notifyUsers($item);
                $this->notifyFollowers($item);
                $item->processed = 1;
                $item->save();
            }
        }
    }

    private function notifyUsers($item)
    {
        $title = explode(' ', $item->name);
        $tags = $item->keywords->pluck('keyword')->toArray();
        $keywords = array_merge($title, $tags);
        foreach ($keywords as $key => $value) {
            $value = strtolower($value);
            $acc = ['á', 'é', 'í', 'ó', 'ú'];
            $nacc = ['a', 'e', 'i', 'o', 'u'];
            $keyword[$key] = str_replace($acc, $nacc, $value);
        }

        $r = User_keyword::whereIn('keyword', $keywords)->select('user_id')->distinct()->pluck('user_id')->toArray();
        if (sizeof($r) > 0) {
            $data = [];
            $date = date('Y-m-d H:i:s', time());
            $value = "Avisador PRO! <b>$item->name</b>";
            if ($item->price > 0) {
                $value .= " por <b>€$item->price</b>";
            }
            $link = chollo_url($item);
            foreach ($r as $user_id) {
                if ($item->user_id != $user_id) {
                    $user = User::select('notifications', 'email', 'name')->find($user_id);

                    if (!is_null($user) && $item->user_id != $user->id) {
                        $user_settings = json_decode($user->notifications, true);
                        if ($user_settings[1]['system']) {
                            $array = ['user_id' => $user_id, 'value' => $value, 'created_at' => $date, 'link' => $link];
                            array_push($data, $array);
                        }
                        if ($user_settings[1]['email']) {
                            $emaildata = ['user' => $user, 'msg' => $value, 'link' => $link];
                            Mail::send('emails.notifications', $emaildata, function ($message) use ($user) {
                                $message->to($user->email)->subject('Nueva Alerta de Chollo recibida');
                            });
                        }
                    }
                }
            }
            Notification::insert($data);
        }
    }

    private function notifyFollowers($item)
    {
        $user = $item->user;
        $follers_ids = DB::table('user_followers')->where('user_id', $user->id)->pluck('follower_id');

        if (sizeof($follers_ids) > 0) {
            $data = [];
            $users = User::select('id', 'notifications', 'name', 'email')->find($follers_ids);
            $date = date('Y-m-d H:i:s', time());
            $value = '<b>'.$user->name."</b> ha compartido un nuevo chollo: <b>$item->name</b>";
            if ($item->price > 0) {
                $value .= " por <b>€$item->price</b>";
            }
            $link = chollo_url($item);
            foreach ($users as $follower) {
                $user_settings = json_decode($follower->notifications, true);
                if ($user_settings[6]['system']) {
                    $array = ['user_id' => $follower->id, 'value' => $value, 'created_at' => $date, 'link' => $link];
                    array_push($data, $array);
                }
                if ($user_settings[6]['email']) {
                    $emaildata = ['user' => $follower, 'msg' => $value, 'link' => $link];
                    Mail::send('emails.notifications', $emaildata, function ($message) use ($follower) {
                        $message->to($follower->email)->subject('Nueva Alerta de Chollo recibida');
                    });
                }
            }
            Notification::insert($data);
        }
    }

    public function like(Request $request)
    {
        $user_id = Auth::id();
        $already = DB::table('comments_likes')->where('user_id', $user_id)->where('comment_id', $request->item_id)->first();
        $c = Comment::where('id', $request->item_id)->first();
        $p = 0;
        $m = 0;
        if ($request->value == 1) {
            ++$c->plus;
            ++$p;
        }
        if ($request->value == -1) {
            ++$c->minus;
            --$m;
        }

        if (!is_null($already)) {
            if ($already->value == 1) {
                if ($request->value == 1) {
                    $c->plus -= 2;
                    --$p;
                } else {
                    --$c->plus;
                }
            }
            if ($already->value == -1) {
                if ($request->value == -1) {
                    $c->minus -= 2;
                    ++$m;
                } else {
                    --$c->minus;
                }
            }
        }
        $f = $p + $m;
        DB::table('comments_likes')->where('user_id', $user_id)->where('comment_id', $request->item_id)->delete();
        if ($f != 0) {
            DB::table('comments_likes')->insert(['user_id' => $user_id, 'comment_id' => $request->item_id, 'value' => $f]);
        }
        $c->save();
        $c->vote = $f;

        return $c;
    }

    public function vote(Request $request)
    {
        if (Auth::guest()) {
            return 0;
        }

        $auth_id = Auth::id();
        $existing = DB::table('user_votes')->where('chollo_id', $request->id)->where('user_id', $auth_id)->first();

        if (!is_null($existing)) {
            return 'Ya has votado este chollo';
        }

        $item = Chollo::find($request->id);

        if ($item->user_id == Auth::id()) {
            return 'Lo sentimos, no puedes votar tu propio chollo.';
        }
        if ($request->operation > 0) {
            $item->votes += $request->operation;
        }

        if ($request->operation == -1) {
            --$item->votes;
        }

        $item->timestamps = false;
        $item->save();

        $date = date('Y-m-d h:i:s', time());

        DB::table('user_votes')->insert(['user_id' => $auth_id, 'chollo_id' => $request->id, 'value' => $request->operation, 'created_at' => $date]);

        $this->checkForAwards($item->user_id, $item->id, 2, $item->votes);

        $count = DB::table('user_votes')->where('user_id', $auth_id)->count();

        $this->checkForAwards($auth_id, $item->id, 3, $count);

        $this->checkForVotesAwards($auth_id, $item->id, 8, $item->votes);

        return $request->operation;
    }

    public function popular(Request $request)
    {
        if ($request->key == '321') {
            $date = Carbon::now()->subHours(24)->toDateTimeString();
            $items = DB::table('user_votes')->where('created_at', '>', $date)->get()->groupBy('chollo_id');
            foreach ($items as $id => $value) {
                $total = 0;
                foreach ($value as $item) {
                    $total += $item->value;
                }
                $value->total = $total;
            }
            $items = $items->sortByDesc('total');
            // dd($items);
            if ($items->count()) {
                $popular = $items->first();
                $item = $popular[0];
                // por mas popular
                $method_id = 1;
                $amount = 1;
                $chollo = Chollo::find($item->chollo_id);
                $this->awardCoins($chollo->user_id, $item->chollo_id, $method_id, $amount, $date);

                $count = DB::table('coins')->where('user_id', $chollo->user_id)->where('method_id', $method_id)->count();
                $this->checkForAwards($chollo->user_id, $item->chollo_id, 1, $count);
            }

            echo 'hecho';
        } else {
            echo 'not allowed';
        }
    }

    private function awardCoins($user_id, $item_id, $method_id, $amount, $date)
    {
        DB::table('coins')->insert(['user_id' => $user_id, 'chollo_id' => $item_id, 'method_id' => $method_id, 'amount' => $amount, 'created_at' => $date]);

        $user = User::find($user_id);

        if (!is_null($user)) {
            $chollo = Chollo::withTrashed()->find($item_id);
            $user->coins += $amount;
            $user->save();
            $msg = 'Tu chollo <b>'.$chollo->name.'</b> ha sido bastante popular. Has obtenido 1 chollo coin!';
            $link = chollo_url($chollo);
            $this->notifyUser($msg, $user, $link, 5);
            $this->checkForAwards($user->id, $item_id, 9, $user->coins);
        }
    }

    public function comment(Request $request)
    {
        isset($request->parent_user_id) ? $action = 2 : $action = 1;

        $c = new Comment();
        $auth = Auth::user();
        $c->user_id = $auth->id;
        $c->value = $request->comment;
        $c->parent_id = $request->parent_id;
        $c->chollo_user_id = $request->item_user_id;
        if ($action == 1) {
            $c->chollo_id = $request->item_id;
        }
        $c->save();
        $c->children = [];
        $c->replying = false;
        $c->visibleChildren = false;
        $c->reply = '';
        $c->vote = 0;
        $c->plus = 0;
        $c->minus = 0;

        $count = DB::table('comments')->where('chollo_user_id', $c->chollo_user_id)->count();
        $this->checkForAwards($c->chollo_user_id, $c->chollo_id, 4, $count);

        $count = DB::table('comments')->where('user_id', $auth->id)->where('chollo_id', '!=', null)->select('chollo_id', 'user_id')->distinct()->get()->count();
        $this->checkForAwards($c->user_id, $c->chollo_id, 6, $count);

        $link = chollo_url_alt($request->item_slug);
        if ($action == 1) {
            $link .= '#comments';
            $offer = Chollo::find($c->chollo_id)->increment('comments_count');
            if ($auth->id != $request->item_user_id) {
                $msg = '<b>'.$auth->name.'</b> ha comentado en <b>'.$request->item_name.'</b>';

                $user = User::find($request->item_user_id);

                $this->notifyUser($msg, $user, $link, 2);
            }
        } else {
            $link .= '?parent_id='.$request->parent_id.'#comments';
            if ($auth->id != $request->parent_user_id) {
                $msg = '<b>'.$auth->name.'</b> ha respondido a tu comentario en <b>'.$request->item_name.'</b>';

                $user = User::find($request->parent_user_id);

                $this->notifyUser($msg, $user, $link, 3);
            }
        }

        return $c;
    }

    public function favorite(Request $request)
    {
        if (Auth::guest()) {
            return 0;
        }

        if ($request->value) {
            DB::table('favorites')->where('user_id', Auth::id())->where('chollo_id', $request->id)->delete();

            return 'Chollo removido de favoritos!';
        } else {
            DB::table('favorites')->insert(['user_id' => Auth::id(), 'chollo_id' => $request->id]);

            return 'Chollo agregado a favoritos!';
        }

        return 1;
    }

    public function find(Request $request)
    {
        $url = $request->url;
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return 'URL No Valida';
        }
        $transformed_url = $this->getSiteName($url);
        $basic_url = $transformed_url[1];
        $store = $transformed_url[0];
        if ($store == 'amazon') {
            $asin_arr = [];
            preg_match('/(?:dp|o|gp|-|dp\/product|gp\/product)\/(B[0-9]{2}[0-9A-Z]{7}|[0-9]{9}(?:X|[0-9]))/', $basic_url, $asin_arr);
            if (sizeof($asin_arr) > 1) {
                $itemID = $asin_arr[1];
                $query = Chollo::where('link', 'LIKE', '%'.$itemID.'%');
            } else {
                $query = Chollo::where('link', $basic_url);
            }
        } else {
            $query = Chollo::where('link', $basic_url);
        }

        if (Auth::user()->role_id == 2) {
            $now = Carbon::now()->subDays(7);
            $query->where('updated_at', '>', $now);
        }

        return $query->select('id', 'name', 'link', 'image', 'price', 'slug', 'created_at', 'updated_at')->first();
    }
}
