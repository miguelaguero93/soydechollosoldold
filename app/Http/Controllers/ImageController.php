<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use App\Category;
use App\Chollo;
use App\Brand;
use Carbon\Carbon;
use App\Store;
use App\Traits\CommonHelpers;
use App\Sdk\AwsV4;
use DOMDocument;

class ImageController extends Controller{
	
	use CommonHelpers;
	
	private function getAmazonImages($basic_url,$site_name){
		$asin_arr = array();
		preg_match('/(?:dp|o|gp|-|dp\/product|gp\/product)\/(B[0-9]{2}[0-9A-Z]{7}|[0-9]{9}(?:X|[0-9]))/', $basic_url, $asin_arr);
		if (sizeof($asin_arr) > 1) {
			$itemID = $asin_arr[1];
		}else{
			return $this->errrorString();
		}

		$serviceName="ProductAdvertisingAPI";
		$region="eu-west-1";

        $accessKey= setting('admin.amazon_access_key');
        $secretKey= setting('admin.amazon_secret_key');
        $tag = setting('admin.amazon_partner_key');
		$payload =[
		 "ItemIds" => [
		  $itemID
		 ],
		 "Resources" => [
		  "Images.Primary.Small",
		  "Images.Primary.Medium",
		  "Images.Primary.Large",
		  "Images.Variants.Small",
		  "Images.Variants.Medium",
		  "Images.Variants.Large",
		  "ItemInfo.Title"
		 ],
		 "PartnerTag" => $tag,
		 "PartnerType" => "Associates",
		 "Marketplace" => "www.amazon.es",
		 "Operation" => "GetItems"
		];	

		$payload = json_encode($payload);

		$host="webservices.amazon.es";
		$uriPath="/paapi5/getitems";
		$awsv4 = new AwsV4 ($accessKey, $secretKey);
		$awsv4->setRegionName($region);
		$awsv4->setServiceName($serviceName);
		$awsv4->setPath ($uriPath);
		$awsv4->setPayload ($payload);
		$awsv4->setRequestMethod ("POST");
		$awsv4->addHeader ('content-encoding', 'amz-1.0');
		$awsv4->addHeader ('content-type', 'application/json; charset=utf-8');
		$awsv4->addHeader ('host', $host);
		$awsv4->addHeader ('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.GetItems');
		$headers = $awsv4->getHeaders();
		$headerString = "";
		foreach ( $headers as $key => $value ) {
		    $headerString .= $key . ': ' . $value . "\r\n";
		}

		$params = array (
		        'http' => array (
		            'header' => $headerString,
		            'method' => 'POST',
		            'content' => $payload
		        )
		);
		
		$stream = stream_context_create ( $params );

		$fp = @fopen ( 'https://'.$host.$uriPath, 'rb', false, $stream );
		if (! $fp) {
			return $this->errrorString();
		}
		$response = @stream_get_contents ( $fp );
		if ($response === false) {
			return $this->errrorString();
		}
		$extractedImages = array();
		$offerTitle = '';
		$offerDescription = '';
		$res = json_decode($response,true);
		if (isset($res['ItemsResult'])) {
			if (isset($res['ItemsResult']['Items'][0])) {
				$item = $res['ItemsResult']['Items'][0];
				$offerTitle = $item['ItemInfo']['Title']['DisplayValue']; 
				if (isset($item['Images']) && sizeof($item['Images']) > 0) {
					if (isset($item['Images']['Primary'])) {
						$extractedImages[] = array(
					        'src' => $item['Images']['Primary']['Large']['URL'],
					        'pixels' => 0,
					        'selected' => false
					    );
					}
					if (isset($item['Images']['Variants'])) {
						foreach ($item['Images']['Variants'] as $key => $value) {
							$extractedImages[] = array(
						        'src' => $value['Large']['URL'],
						        'pixels' => 0,
						        'selected' => false
					    	);		
						}
						
					}
				}
			}

			if (sizeof($extractedImages) > 0 ) {
				if (sizeof($extractedImages)>6) {
					$extractedImages = array_slice($extractedImages, 0, 6);
				}
			}

			return [$extractedImages,$offerTitle,$offerDescription,$site_name];

		}else{
			return $this->errrorString();
		}
	
	}

	private function errrorString(){
		return "Sube tu propia imagen";
	}
	private function scrapImages($url,$site_name){


		$response = Http::withHeaders([
	    	"Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9", 
		    "Accept-Encoding" => "gzip, deflate", 
		    "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36"
		])->get($url);

	    if ($response->successful()) {
			$extractedImages = array();
			$offerTitle = '';		 
			$offerDescription = '';		 

			$html_dom = new DOMDocument;
			libxml_use_internal_errors(true);
			$html_dom->loadHTML(mb_convert_encoding($response->body(), 'HTML-ENTITIES', 'UTF-8'));
			// Try finding meta og:image firt 
			$metaTags = $html_dom->getElementsByTagName('meta');

			foreach ($metaTags as $key => $value) {
			    $property = $value->getAttribute('property');
				if ($property == 'og:image') {					
			    	$image = $value->getAttribute('content');
					$metaImageSize = @getimagesize($image);

					if (is_array($metaImageSize)) {
						$pixels = $metaImageSize[0]*$metaImageSize[1];
			 			if ($pixels > 60000) {
							array_push($extractedImages, array('src'=>$image, 'pixels'=>99999999, 'selected'=>false));
						}	
					}	
				}
				if ($property == 'og:title') {					
					$offerTitle = $value->getAttribute('content');
				}
				if ($property == 'og:description') {					
					$offerDescription = $value->getAttribute('content');
				}
			}
			if (!empty($extractedImages) && strlen($offerTitle) > 0 ) {
				return [$extractedImages,$offerTitle,$offerDescription,$site_name];
			}
		    //Extract all img elements / tags from the HTML.
			$imageTags = $html_dom->getElementsByTagName('img');
			//Create an array to add extracted images to.
			//Loop through the image tags that DOMDocument found.
			foreach($imageTags as $imageTag){
			    $imgSrc = $imageTag->getAttribute('src');
			 	$size = @getimagesize($imgSrc);
			 	if (is_array($size)){
			 		$pixels = $size[0]*$size[1];
			 		if ($pixels > 60000) {
					    $extractedImages[] = array(
					        'src' => $imgSrc,
					        'pixels' => $pixels,
					        'selected' => false
					    );
			 		}
			 	}
			}

			$titleTags = $html_dom->getElementsByTagName('title');
			if (!empty($titleTags)) {
				$offerTitle = $titleTags[0]->nodeValue;
			}

			foreach ($metaTags as $key => $value) {
			    $property = $value->getAttribute('name');
				if ($property == 'description') {					
			    	$offerDescription = $value->getAttribute('content');
				}	
			}


			if (sizeof($extractedImages) > 0 ) {
				usort($extractedImages, function($a,$b){
					return $a['pixels'] < $b['pixels']; 
				});
				if (sizeof($extractedImages)>6) {
					$extractedImages =  array_slice($extractedImages, 0, 6);
				}
			}

			return [$extractedImages,$offerTitle,$offerDescription,$site_name];

		}else{
			return $this->errrorString();
		}
	}
	// public function getSiteImages($url){
	public function getSiteImages(Request $request){

		if (!isset($request->url)) {
			return 'Request invalido. "url" es requerido.';
		}

		$url = $request->url;

		if (!filter_var($url, FILTER_VALIDATE_URL)) {
    		return 'URL No Valida';
    	}
    	$transformed_url = $this->getSiteName($url);
    	$site_name = $transformed_url[0];
    	$basic_url = $transformed_url[1];
    	$unscrappable_site = ['amazon']; 
    	if (!in_array($site_name, $unscrappable_site)) {
		    return $this->scrapImages($basic_url,$site_name);
	    }else{
	    	return $this->getAmazonImages($basic_url,$site_name);
	    }
	}

	public function getSiteImagesTest($url){
		$transformed_url = $this->getSiteName($url);
    	$site_name = $transformed_url[0];
    	$basic_url = $transformed_url[1];
    	$unscrappable_site = ['amazon']; 
    	if (!in_array($site_name, $unscrappable_site)) {
		    return $this->scrapImages($basic_url,$site_name);
	    }else{
	    	return $this->getAmazonImages($basic_url,$site_name);
	    }
	}

    private function catItem($name){
        $ignore = ['con','para','gratis'];
        $find = ['í','á','ó','é','ú'];
        $replace = ['i','a','o','e','u'];
        $name = strtolower(str_replace($find,$replace,$name));
        $name = preg_replace("/[^A-Za-z0-9ñ ]/", '', $name);
        $words = explode(' ', $name);

        $cleaned = array();
        foreach ($words as $key => $value) {
            if (!is_numeric($value) && strlen($value) > 3 && !in_array($value, $ignore)) {
                array_push($cleaned, $value);
            }
        }
        if (sizeof($cleaned) > 0) {
            foreach ($cleaned as $key => $value) {
                $cat = Category::where('name','like', '%'.$value.'%')->first();
                if (!is_null($cat)) {
                    return $cat->id;
                }
            }
        }
        return 900;
    }

    private function brandItem($name){
        $ignore = ['con'];
        $find = ['í','á','ó','é','ú'];
        $replace = ['i','a','o','e','u'];
        $name = strtolower(str_replace($find,$replace,$name));
        $name = preg_replace("/[^A-Za-z0-9ñ ]/", '', $name);
        $words = explode(' ', $name);
        $cleaned = array();
        foreach ($words as $key => $value) {
            if (!is_numeric($value) && strlen($value) > 3 && !in_array($value, $ignore)) {
                array_push($cleaned, $value);
            }
        }
        if (sizeof($cleaned) > 0) {
            $brands = Brand::whereIn('value',$cleaned)->get();
            if ($brands->count()) {
                return $brands->first()->id;
            }
        }

        return null;
    }

    private function getSiteName($url){
      $parsed = parse_url($url);
      if (!isset($parsed['host'])) {
      		return null;
      		// dd($parsed,$url);
      }
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

  	private function addStore($parse_url){
      if($parse_url[0] == 'amazon') {
          $existing = Store::where('website',$parse_url[2])->first();
      }else{
          $existing = Store::where('name',$parse_url[0])->first();
      }

      if (!is_null($existing)) {
          return $existing->id;
      }
      return null;
  	}

  	private function save_image($inPath,$outPath){ 

        $in = @fopen(public_path($inPath), 'rb');
        if ($in !== false) {
            $out = fopen(public_path($outPath), 'wb');
            while ($chunk = fread($in,8192))
            {
                fwrite($out, $chunk, 8192);
            }
            fclose($in);
            fclose($out);
            return true;
        }
        return false;
    }


    private function getItemImage($slug,$image){
        try {

        	$p = strpos($image, 'cdn.grupoelcorteingles.es');
        	$p2 = strpos($image, 'sgfm.elcorteingles.es');
        	$p3 = strpos($image, 'tuimeilibre');
        	
        	if ($p !== false || $p2 !== false || $p3 !== false) {
        		return [$image,null]; 
        	}

        	$path = 'storage/oferta/'.$slug;
        	$in= @fopen($image, "rb");
        	$wa = Image::make($image);
            $width = $wa->width();
            $height = $wa->height();
            $wa->encode('jpg');
            $large_path = $path.'.jpg';
            $wa->save($path);
            
            $ratio = $width/$height;
            $new_width = 220;
            $new_height = $new_width/$ratio;
            
            $wa->resize($new_width, $new_height);
            $path_small = $path.'-220.jpg'; 
            $wa->save($path_small);
            
            $item_image = env('APP_URL').'/'.$large_path;
            $item_image_small = env('APP_URL').'/'.$path_small;
            
        } catch (\Exception $e) {   
        	$path_info = pathinfo($image);
            $extensions = array('jpg', 'JPG', 'png' ,'PNG' ,'jpeg' ,'JPEG','webp');
            $download_image = false;
            $path = 'storage/oferta/'.$slug;
            
            if (isset($path_info['extension']) && in_array($path_info['extension'], $extensions)){
                
                $ext = $path_info['extension'];
                $ext = strtok($ext, '?');
                $path = 'storage/oferta/'.$slug.'.'.$ext;
            
                $download_image = $this->save_image($image,$path);
        
            }else{

                if (isset($path_info['dirname'])) {
                    $image = $path_info['dirname'];
                    $download_image = $this->save_image($image,$path);
                }
            }

            if ($download_image) {
                 $item_image = env('APP_URL').'/'.$path;
            }else{
                $item_image = $image;
            }

            $item_image_small = null;
        }

        return [$item_image,$item_image_small];
    }

    public function index(){


    	dd('done');
    	try {
    		
    	$chollos = Chollo::all();
	    	foreach ($chollos as $key => $value) {
	    		$value->timestamps = false;
	    		$snippet = $value->description;
	    		$stripped = strip_tags($snippet);
				$stripped = substr($stripped, 0, 68);
				$stripped = utf8_encode($stripped);
				$value->snippet = $stripped;
	    		$value->save();
	    	}
    	} catch (\Exception $e) {
    		dd($e);
    	}
    	dd('done');
    	$slug = "https://www.amazon.es/dp/B08GGD43QH/ref=sspa_dk_detail_2?psc=1&pd_rd_i=B08GGD43QH&pd_rd_w=jQrGC&pf_rd_p=af12bbbd-c74b-4d8c-ad16-2ed2a7b363ab&pd_rd_wg=FfZKf&pf_rd_r=M88FJ0VEMAX4XKMXMAZA&pd_rd_r=cbfeb523-6f24-47ec-b273-8f520d0dde27&spLa=ZW5jcnlwdGVkUXVhbGlmaWVyPUEzVkZRNkE4WUpZM0k5JmVuY3J5cHRlZElkPUEwOTQ3OTc4VDNORzcxM1ZHUE1VJmVuY3J5cHRlZEFkSWQ9QTAwMDYzOTUyRUgyVzVTNlpaNTVRJndpZGdldE5hbWU9c3BfZGV0YWlsJmFjdGlvbj1jbGlja1JlZGlyZWN0JmRvTm90TG9nQ2xpY2s9dHJ1ZQ==";
    	// $slug =  " ¿ ? : / . ( ) | [ ] º ! ¡ í á ó é ú % ñ &nbsp;";
    	dd( nicename($slug) );
    	$path = '/path';
    	$slug= 'slug';
    	// dd($path);
    	$i = 'https://www.tuimeilibre.com/14298-large_default/asus-tuf-gaming-f15-fx506lh-bq116-intel-core-i7-10870h16gb1tb-ssdgtx1650156.jpg'; 
    	$images = $this->getItemImage($slug,$i,$path);
		dd($images);
		// $i =  $this->getSiteImagesTest($url);
		// dd($i);
    }

}
