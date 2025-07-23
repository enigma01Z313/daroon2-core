<?php

function daroon2_bookly_advanced_form_func($atts = [], $content = null, $tag = '')
{
	$formIds = $atts['form_ids'];
	$staffUrls = $atts['staff_urls'];
	$staff_member_id = $atts['staff_member_id'];
	$formIds = explode(',', $formIds);
	$staffUrls = explode(',', $staffUrls);
    $pasge_url = strtok($_SERVER["REQUEST_URI"], '?');

	$serviceId = 	(isset($_GET['serviceId']) && !empty($_GET['serviceId'])) ? $_GET['serviceId'] : '0';
	$booklySC = "[bookly-form staff_member_id='$staff_member_id' category_id='6' service_id='$serviceId' hide='categories,services,staff_members,date,week_days,time_range']";


	if (!is_rtl()) {
		if ($serviceId == $formIds[0]) {
			$url = $pasge_url . "?serviceId=" . $formIds[1];
			$txt = "Please note, if it's not your first appointment please use this <a class='color-action-ember' href='$url'>link</a> to book";
			echo "<p class='w-100 mb-2'>$txt</p>";
		} else if ($serviceId == $formIds[1]) {
			$url = $pasge_url . "?serviceId=" . $formIds[0];
			$txt = "Please note, if it's your first appointment please use this <a class='color-action-ember' href='$url'>link</a> to book";
			echo "<p class='w-100 mb-2'>$txt</p>";
		}
	} else {
		if ($serviceId == $formIds[0]) {
			$url = $pasge_url . "?serviceId=" . $formIds[1];
			$txt = "لطفا اگر برای بار چندم از این سرویس استفاده میکنید لطفا از این <a class='color-action-ember' href='$url'>لینک</a> برای رزرو وقت مشاوره اقدام نمایید.";
			echo "<p class='w-100 mb-2'>$txt</p>";
		} else if ($serviceId == $formIds[1]) {
			$url = $pasge_url . "?serviceId=" . $formIds[0];
			$txt = "لطفا اگر برای بار اول از این سرویس استفاده میکنید لطفا از این <a class='color-action-ember' href='$url'>لینک</a> برای رزرو وقت مشاوره اقدام نمایید.";
			echo "<p class='w-100 mb-2'>$txt</p>";
		}
	}

	if ($serviceId == 0) {

		if (!is_rtl()) {


			echo "
				<div id='buttonsWrapper' class='d-flex gap-1 w-100 justify-content-center'>
					<div class=''>
						<a class='btn btn-style-outline btn-size-m' 
							href='" . $pasge_url . "?serviceId=" . $formIds[0] . "' 
							title='Book now'>
								<span class='far fa-calendar-alt' style='padding: 0'></span>
								<span class=''>
									For your first psychiatric session please book here
								</span>
						</a>
					</div>
					<div class=''>
						<a class='btn btn-style-outline btn-size-m' 
						href='" . $pasge_url . "?serviceId=" . $formIds[1] . "' 
							title='Book now'>
								<span class='far fa-calendar-alt' style='padding: 0'></span>
								<span class=''>
									For your follow-up psychiatric sessions please book here
								</span>
						</a>
					</div>
				</div>";
		} else {
			echo "
				<div id='buttonsWrapper' class='d-flex gap-1 w-100 justify-content-center'>
					<div class=''>
						<a class='btn btn-style-outline btn-size-m' 
						href='" . $pasge_url . "?serviceId=" . $formIds[0] . "' 
							title='Book now'>
								<span class='far fa-calendar-alt' style='padding: 0'></span>
								<span class=''>
									برای رزرو  جلسه اول  اینجا کلیک کنید 
								</span>
						</a>
					</div>
					<div class=''>
						<a class='btn btn-style-outline btn-size-m' 
						href='" . $pasge_url . "?serviceId=" . $formIds[1] . "' 
							title='Book now'>
								<span class='far fa-calendar-alt' style='padding: 0'></span>
								<span class=''>
									برای رزرو جلسات بعد  اینجا کلیک کنید								
								</span>
						</a>
					</div>
				</div>";
		}
	}

	if ($serviceId == $formIds[0]) {
		echo "<style>
            .points.timeSec:not(.default){display: none !important}
		</style>";
	} else if ($serviceId == $formIds[1]) {
		echo "
		<style>
            .points.timeSec:not(.postid-1545){display: none !important}
		</style>";
	} else {
		echo "
		<style>
            body.rtl #buttonsWrapper > div{margin: 0 8px}
			@media(max-width: 1200px) {
				#buttonsWrapper a{text-align: center}
				#buttonsWrapper a div{display: none}
            }
			@media(max-width: 992px) {
            	#buttonsWrapper {
                  flex-direction: column;
                  align-items: center;
                  margin-top: 20px;
                }
            }
			@media(max-width: 992px) and (min-width: 768px) {
				#buttonsWrapper a{padding-left: 12px; padding-right: 12px}
				#buttonsWrapper a div{display: none}
				#buttonsWrapper > div{margin: 0 5px}
			}
			@media(max-width: 767px) {
				#buttonsWrapper{
					flex-direction: column;
					align-items: center;
				}
			}
		</style>";
	}




	$res = '';
	if ($serviceId == $formIds[0] || $serviceId == $formIds[1]) {
		$res = do_shortcode($booklySC);
	}
	return $res;
}
add_shortcode('daroon2_bookly_advanced_form', 'daroon2_bookly_advanced_form_func');

?>