<!DOCTYPE html>
<?php
    Ini_Set( 'display_errors', true );
    include '../../init.php';
    include ROOT_DIR . '/assets/php/functions.php';

    $titleText = "";

    if (parseCpMovies("release_status=snatched") != false) {
    	$titleText = "Snatched Movies";
    }
    elseif (parseCpMovies("release_status=available") != false) {
		$titleText = "Available to Download";
    }
    elseif (parseCpMovies("status=active") != false) {
		$titleText = "Actively Searching";
    }
    elseif (parseCpMovies("status=done") != false) {
		$titleText = "Finished Movies";
    }
    else {
        $titleText = "This Week's Weather";
    }

    echo '<h1 class="exoextralight">'.$titleText.'</h1>';
    echo '<hr>';
    echo print_r(parseCpMovies("status=done"));

?>
