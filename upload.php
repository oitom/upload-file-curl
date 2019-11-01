<?php 

if(0 < $_FILES['file']['error']) 
    $data = json_encode(array("code"=> 0, "response"=> $_FILES['file']['error']));
else {
    $file = $_FILES['file']['tmp_name'];
    $name_tmp = explode('.', $_FILES['file']['name']);
    $filepath = 'uploads/'.$_REQUEST['name'];

    if(move_uploaded_file($file, $filepath)) { 		
        $data = json_encode(array("code"=> 1, "response"=> "Upload com sucesso!"));
    }
    else { 
        $data = json_encode(array("code"=> 0, "response"=> $_FILES['file']['error']));
    }
    echo $data;
}