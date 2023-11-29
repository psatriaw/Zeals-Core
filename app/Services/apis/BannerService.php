<?php 
namespace App\Services\apis;

use App\Models\ { Banner, Campaign };
 
class BannerService {

    public function __construct($request){
        $this->request = $request;
    }

    protected function getCampaignID($link){
        $links = explode("/",$link);
        $last  = $links[sizeof($links) - 1 ];
        $campaign = Campaign::where('campaign_link',$last)->first();
        if($campaign){
            return $campaign->id_campaign;
        }else{
            return null;
        }
    }

    public function get(){
        $banners = Banner::where("status",'active')->get();
        if($banners){
            foreach($banners as $index=> $banner){
                $banners[$index]['campaign_id'] = $this->getCampaignID($banner->link);
            }
            return $banners;
        }else{
            return null;
        }
    }

}

?>