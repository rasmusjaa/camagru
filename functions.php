<?php

include ('functions/db_functions.php');

if ($_POST['function'] == 'reload_user_images')
    reload_user_images($_POST['value1']);

function reload_user_images($user)
{
    $data = get_user_images($user);
    print_r($data);
    $images = '<div id="my_photos">';

    if ($data)
    {
        foreach ($data as $image)
        {
            $images = $images . '<div id="' . $image["id"] . '" class="single_photo">';
            $src = '/user_images/' . $image["filename"] . '.png';
            $images = $images . '<img src="' . $src . '">'  . '<br />' . 
            '<div class="delete_image">X</div></div>';
        }
    }
    $images . '</div>';
    return ($images);
}
?>
