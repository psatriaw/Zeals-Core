<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{CampaignTracker};

class DashboardController extends Controller
{
    public function performance(Request $request){
        $user = auth('sanctum')->user();

        $logs           = CampaignTracker::where("id_user",$user->id_user)->whereNotIn("type_conversion",["initial"])->orderBy("id_tracker","DESC")->limit(10)->skip(0)->get();
        // $performance    = 
        $currentmonth   = date("m");
        $year           = date("Y");

        for($i=$currentmonth-1;$i>=0;$i--){
            $months_label[]  = date("M",strtotime ( "-$i Months" ));
            $cmonth          = date("m",strtotime ( "-$i Months" ));

            $start           = strtotime("$year-$cmonth-01 00:00:00");
            $end             = strtotime("$year-$cmonth-31 23:59:59");

            if($cmonth=="03"){

                $visits[]        = CampaignTracker::where("id_user",$user->id_user)->where("type_conversion","'visit'")->whereBetween("time_created",[$start,$end])->count();
                $interest[]      = CampaignTracker::where("id_user",$user->id_user)->where("type_conversion","'read'")->whereBetween("time_created",[$start,$end])->count();
                $action[]        = CampaignTracker::where("id_user",$user->id_user)->where("type_conversion","'action'")->whereBetween("time_created",[$start,$end])->count();
                $acquisition[]   = CampaignTracker::where("id_user",$user->id_user)->where("type_conversion","'acquisition'")->whereBetween("time_created",[$start,$end])->count();
            }else{
                $visits[]        = CampaignTracker::where("id_user",$user->id_user)->where("type_conversion","visit")->whereBetween("time_created",[$start,$end])->count();
                $interest[]      = CampaignTracker::where("id_user",$user->id_user)->where("type_conversion","read")->whereBetween("time_created",[$start,$end])->count();
                $action[]        = CampaignTracker::where("id_user",$user->id_user)->where("type_conversion","action")->whereBetween("time_created",[$start,$end])->count();
                $acquisition[]   = CampaignTracker::where("id_user",$user->id_user)->where("type_conversion","acquisition")->whereBetween("time_created",[$start,$end])->count();
            }
        }

        // $months_label[] = date("M",strtotime ( "-$currentmonth Months" ));
        $return = array(
            "status"        => "success",
            "logs"          => @$logs,
            "labels"        => $months_label,
            "performances"  => [
                [
                    "label" => "Visit",
                    "data"  => $visits,
                    "fill"  => false,
                    "borderColor"   => "#cccccc",
                    "backgroundColor"  => "#cccccc"
                ],
                [
                    "label" => "Interest",
                    "data"  => $interest,
                    "fill"  => false,
                    "borderColor"   => "#1ca0e2",
                    "backgroundColor"  => "#1ca0e2"
                ],
                [
                    "label" => "Action",
                    "data"  => $action,
                    "fill"  => false,
                    "borderColor"   => "#c0cb04",
                    "backgroundColor"  => "#c0cb04"
                ],
                [
                    "label" => "Sales",
                    "data"  => $acquisition,
                    "fill"  => false,
                    "borderColor"   => "#11b88e",
                    "backgroundColor"  => "#11b88e"
                ]
            ],
            "earnings"  => [
                "estimation"    => CampaignTracker::where("id_user",$user->id_user)->whereIn("status",['initial','active'])->sum("commission"),
                "earning"       => CampaignTracker::where("id_user",$user->id_user)->whereIn("status",['paid'])->sum("commission")
            ],
            "path"      => url('/'),
            "user"      => $user
        );

        return response()->json($return, 200);
    }
}
