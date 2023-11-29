<?php

namespace App\Http\Controllers\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

use App\Http\Models\backend\member\NotificationModel;

class IGWHelper extends Controller{
	public $timestamps = false;
	const CREATED_AT ='posting_date';

  public function createBC($breadcrumbs){
    $html = "<nav class=\"breadcrumb pd-0 mg-0 tx-12\">";
    foreach ($breadcrumbs as $key => $value) {
      if($key==(sizeof($breadcrumbs)-1)){
        $html .= "<span class=\"breadcrumb-item active\">".$value['label']."</span>";
      }else{
        $html .= "<a class=\"breadcrumb-item\" href=\"".$value['url']."\">".$value['label']."</a>";
      }
    }
    $html  .= "</nav>";
    print $html;
  }

  public function showPagging($counter, $url = 'js', $position = "", $current_page = 1, $list_per_page = 10, $type = 1, $open = false, $js = false, $print = true) {
        $totalperpage = $list_per_page;
        $page = 0;
        if ($url == "js") {
            $url = "javascript:;";
        }

        do {
            $page = $page + 1;
            $counter = @$counter - $totalperpage;
        } while ($counter > 0);
        switch ($position) {
            default :
                $pos_a = "";
                $pos_x = "";
                break;
        }
        if ($current_page == "") {
            $current_page = 1;
        }
        $stringret = $pos_a . '<nav aria-label="Page navigation"><ul class="pagination pagination-basic pagination-teal mg-b-0">';
        $maxbtn = $current_page + 4;
        $minbtn = $current_page - 4;
        if ($minbtn <= 0) {
            $minbtn = 1;
        }
        if ($maxbtn > $page) {
            $maxbtn = $page;
        }
        $jumlah = $current_page - $minbtn;
        if ($open && $jumlah >= 4) {
            if ($type == 1) {
                if ($js) {
                    $stringret = $stringret . "<li class='page-item'><a class='page-link' href='javascript:;' onclick='gotopage(1)'>1..</a></li>";
                } else {
                    $stringret = $stringret . "<li class='page-item'><a class='page-link' href='$url?page=1#'>1..</a></li>";
                }
            } else {
                if ($js) {
                    $stringret = $stringret . "<li class='page-item'><a class='page-link' href='javascript:;' onclick='gotopage(1)'>1..</a></li>";
                } else {
                    $stringret = $stringret . "<li class='page-item'><a class='page-link' href='$url&page=1#'>1..</a></li>";
                }
            }
        }
        for ($i = $minbtn; $i <= $maxbtn; $i++) {
            if ($current_page == $i) {
                if ($type == 1) {
                    if ($js) {
                        $stringret = $stringret . "<li class='page-item active disabled'><a class='page-link' disabled href='javascript:;' onclick='gotopage($i)'>" . $i . "</a></li>";
                    } else {
                        $stringret = $stringret . "<li class='page-item active disabled'><a class='page-link' disabled href='$url?page=$i#'>" . $i . "</a></li>";
                    }
                } else {
                    if ($js) {
                        $stringret = $stringret . "<li class='page-item active disabled'><a class='page-link' disabled href='javascript:;' onclick='gotopage($i)'>" . $i . "</a></li>";
                    } else {
                        $stringret = $stringret . "<li class='page-item active disabled'><a class='page-link' disabled href='$url&page=$i#'>" . $i . "</a></li>";
                    }
                }
            } else {
                if ($type == 1) {
                    if ($js) {
                        $stringret = $stringret . "<li class='page-item'><a class='page-link' href='javascript:;' onclick='gotopage($i)'>" . $i . "</a></li>";
                    } else {
                        $stringret = $stringret . "<li class='page-item'><a class='page-link' href='$url?page=$i'>" . $i . "</a></li>";
                    }
                } else {
                    if ($js) {
                        $stringret = $stringret . "<li class='page-item'><a class='page-link' href='javascript:;' onclick='gotopage($i)'>" . $i . "</a></li>";
                    } else {
                        $stringret = $stringret . "<li class='page-item'><a class='page-link' href='$url&page=$i'>" . $i . "</a></li>";
                    }
                }
            }
        }
        $jumlah = $maxbtn - $current_page;
        if ($open && $jumlah >= 4) {
            if ($type == 1) {
                if ($js) {
                    $stringret = $stringret . "<li class='page-item'><a class='page-link' href='javascript:;' onclick='gotopage($page)'>.." . $page . "</a></li>";
                } else {
                    $stringret = $stringret . "<li class='page-item'><a class='page-link' href='$url?page=$page'>.." . $page . "</a></li>";
                }
            } else {
                if ($js) {
                    $stringret = $stringret . "<li class='page-item'><a class='page-link' href='javascript:;' onclick='gotopage($page)'>.." . $page . "</a></li>";
                } else {
                    $stringret = $stringret . "<li class='page-item'><a class='page-link' href='$url&page=$page'>.." . $page . "</a></li>";
                }
            }
        }
        $stringret = $stringret . "</ul></nav>" . $pos_x;
        if ($print) {
            return $stringret;
        } else {
            return $stringret;
        }
    }

	function ubahTanggalPendek2($tanggal) {
        $tahun = substr($tanggal, 0, 4);
        $bulan = substr($tanggal, 5, 2);
        $hari = substr($tanggal, -2);
        if ($bulan == '01') {
            $bln = 'Jan';
        } elseif ($bulan == '02') {
            $bln = 'Feb';
        } elseif ($bulan == '03') {
            $bln = 'Mar';
        } elseif ($bulan == '04') {
            $bln = 'Apr';
        } elseif ($bulan == '05') {
            $bln = 'May';
        } elseif ($bulan == '06') {
            $bln = 'Jun';
        } elseif ($bulan == '07') {
            $bln = 'Jul';
        } elseif ($bulan == '08') {
            $bln = 'Aug';
        } elseif ($bulan == '09') {
            $bln = 'Sep';
        } elseif ($bulan == '10') {
            $bln = 'Oct';
        } elseif ($bulan == '11') {
            $bln = 'Nov';
        } else {
            $bln = 'Des';
        }
        $tg = "$hari $bln $tahun";
        return $tg;
    }

	public function ubahTanggalPanjang2($tanggal) {
        $tahun = substr($tanggal, 0, 4);
        $bulan = substr($tanggal, 5, 2);
        $hari = substr($tanggal, 8, 2);
        $jam = substr($tanggal, 11, 2);
        $menit = substr($tanggal, 14, 2);
        $detik = substr($tanggal, -2);
        if ($bulan == '01') {
            $bln = 'Jan';
        } elseif ($bulan == '02') {
            $bln = 'Feb';
        } elseif ($bulan == '03') {
            $bln = 'Mar';
        } elseif ($bulan == '04') {
            $bln = 'Apr';
        } elseif ($bulan == '05') {
            $bln = 'May';
        } elseif ($bulan == '06') {
            $bln = 'Jun';
        } elseif ($bulan == '07') {
            $bln = 'Jul';
        } elseif ($bulan == '08') {
            $bln = 'Aug';
        } elseif ($bulan == '09') {
            $bln = 'Sep';
        } elseif ($bulan == '10') {
            $bln = 'Oct';
        } elseif ($bulan == '11') {
            $bln = 'Nov';
        } else {
            $bln = 'Des';
        }
        $tg = "$hari $bln $tahun $jam:$menit";
        return $tg;
    }

		function getMenuContent($login, $previlege_model){
			switch($login->level){
				case "admin":
					$menu = "backend.menus.admin_menu";
				break;

				case "user":
					$menu = "backend.menus.user_menu";
				break;
			}

			$view     = View::make($menu,array("login" => $login, "previlege_model" => $previlege_model));
      $content  = $view->render();
			return $content;
		}

		function balikTanggalPendek($tanggal) {
        $tahun = substr($tanggal, -4);
        $bulan = substr($tanggal, 3, 2);
        $hari = substr($tanggal, 0, 2);
        $tg = "$tahun-$bulan-$hari";
        return $tg;
    }

		function checkReferrer($referrer){
			if($referrer){
				if(str_contains($referrer,"google")){
					return "Google";
				}elseif(str_contains($referrer,"facebook")){
					return "Facebook";
				}elseif(str_contains($referrer,"instagram")){
					return "Instagram";
				}elseif(str_contains($referrer,"twitter")){
					return "Twitter";
				}
			}else{
				return "Whatsapp,Telegram, Etc..";
			}
		}

	public function createBCEshopper($breadcrumbs){
		$html = "<div class=\"breadcrumbs\">";
		$html .= "<ol class=\"breadcrumb\">";
		foreach ($breadcrumbs as $key => $value) {
		  if($key==(sizeof($breadcrumbs)-1)){
			$html .= "<li class=\"active\">".$value['label']."</li>";
		  }else{
			$html .= "<li><a href=\"".$value['url']."\">".$value['label']."</a></li>";
		  }
		}
		$html  .= "</ol>";
		$html  .= "</div>";
		print $html;
	}
}
