<?php

// files to upload
if(0 < $_FILES['file']['error']) 
	$data = json_encode(array("code"=> 0, "response"=> $_FILES['file']['error']));
else {
    $file = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];

    $filenames = array($file); // type file
    $fields = array("name"=> $name); // type text fields
    
    $files = array();
    foreach ($filenames as $f){
        $files[$f] = file_get_contents($f);
    }
   
    $curl = curl_init();
    $boundary = uniqid();
    $delimiter = '-------------' . $boundary;
    $post_data = build_data_files($boundary, $fields, $files);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://localhost/upload-curl/upload.php",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: multipart/form-data; boundary=" . $delimiter,
            "Content-Length: " . strlen($post_data)
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

  echo "<pre>";
  print_r($response);
  echo "</pre>";
}

function build_data_files($boundary, $fields, $files){
    $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
            . $content . $eol;
    }


    foreach ($files as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="file"; filename="' . $name . '"' . $eol
            //. 'Content-Type: image/png'.$eol
            . 'Content-Transfer-Encoding: binary'.$eol
            ;

        $data .= $eol;
        $data .= $content . $eol;
    }
    $data .= "--" . $delimiter . "--".$eol;


    return $data;
}