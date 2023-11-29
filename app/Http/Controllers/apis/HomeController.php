<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
use App\Models\{Banner, Campaign};

use App\Services\apis\{ BannerService, CategoryService, CampaignService};

use Session;

class HomeController extends Controller
{
    public function index(Request $request){
        $service_banner     = new BannerService($request);
        $service_category   = new CategoryService($request);
        $service_campaign   = new CampaignService($request);

        $banners    = $service_banner->get();
        $categories = $service_category->get();
        

        $return = array(
            "status"    => "success",
            "data"      => [
                "banners"       => $banners,
                "categories"    => $categories,
                "campaigns"     => [
                    "hot"       => ($request->mode!="search")?$service_campaign->get('hot'):null,
                    "random"    => ($request->mode!="search")?$service_campaign->get('random'):null,
                    "highest"   => ($request->mode!="search")?$service_campaign->get('highest'):null,
                    "search"    => ($request->mode=="search")?$service_campaign->get():null
                ],
            ],
            "path"      => url('/'),
            "request"      => $request->all()
        );

        return response()->json($return, 200);
    }
}
