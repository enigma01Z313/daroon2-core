<?php

function daroon2_has_access_to_protected($password, $pageId)
{
	$thisPagePass = '';
	$pagePassword = $_SESSION['simyatechPasswords'];
	if ($pagePassword != null) {
		$thisPagePass = $pagePassword[$pageId];
	}

	if ($thisPagePass == '') return 0;

	if ($thisPagePass != $password) return 1;

	return 2;
}

function daroon2_protected_func($atts, $child)
{
	$langs = array('fa' => 'en', 'en' => 'fa');
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$res = '';
	$pageId = get_queried_object_id();
	$postType = get_post_type($pageId);
	$currentLang = apply_filters('wpml_current_language', null);
	$translatedPageId = apply_filters(
		'wpml_object_id',
		$pageId,
		$postType,
		false,
		$langs[$currentLang]
	);
	$sessionName = "$pageId|$translatedPageId";


	$accessStatus = daroon2_has_access_to_protected($atts['password'], $pageId);

	if ($accessStatus == 2) {
		$res .= do_shortcode($child);
	} else {
		$passwordTxt = (is_rtl()) ? 'رمز عبور' : 'Password';
		$submitTxt = (is_rtl()) ? 'ورود' : 'Submit';
		$validationErrorTxt = (is_rtl()) ? 'رمز عبور اشتباه است' : 'Wrong Password';
		$protectedText = (is_rtl()) ?
			'این درمانگر در حال حاضر مراجع جدید پذیرش نمی کنند' :
			'This therapist is currently not accepting new clients';

		$res .= "<div class='d-flex justify-content-center align-items-center direction-column w-100'>";
		$res .= "<h5 class='title2' style='text-align: center; margin-bottom: 25px'>$protectedText</h5>";
		$res .= "<form class='d-flex align-items-center' method='post' action='' style='align-items:stretch'>";
		$res .= "<input type='hidden' value='$sessionName' name='simyatechPagesId'>";
		$res .= "<input class='text-center' type='text' placeholder='$passwordTxt...' name='simyatechPassword' style='min-width: 200px; border-top-left-radius: 50px; border-bottom-left-radius: 0px; border-bottom-left-radius: 50px;'>";
		$res .= "<button class='btn btn-primary btn-size-l btn-style-black' type='submit' name='simyatechPasswordSubmit' style='border-top-left-radius: 0px; border-bottom-left-radius: 0px; border-top-left-radius: 0px;'><span>$submitTxt</span></button>";
		$res .= "</form>";
		$res .= "</div>";
		if ($accessStatus == 1) {
			$res .= "<span style='color: red; margin-left: auto; margin-right: auto; margin-top: 12px'>$validationErrorTxt...!</span>";
		}
	}
	return $res;
}
add_shortcode('daroon2_protected', 'daroon2_protected_func');
?>