<?php

if (isset($_POST)) {
    $servername = "mybeanjar.ckec4ny0gmeu.us-east-1.rds.amazonaws.com";
    $dbname = "mybeanjar_app";
    $username = "beanjar";
    $password = "!Q2w#E4r";


// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $tempcustomeid = $_POST['custom'];
    $customeids = explode(",", $_POST['custom']);
    $email = '';
    $imagear = array();
    for ($i = 0; $i < count($customeids); $i++) {
        if ($customeids != '') {
            $updateordersql = 'UPDATE `userimages` SET `status`="completed" ,`txn_id`= "' . $_POST['txn_id'] . '" WHERE `id`="' . $customeids[$i] . '"';

//echo $updateordersql;


            if ($conn->query($updateordersql) === TRUE) {
                $selectordersql = "SELECT userimages.user_id, userimages.imagename, users.email FROM userimages INNER JOIN users ON userimages.user_id=users.id where userimages.id='" . $customeids[$i] . "'";

                $selectorderresult = $conn->query($selectordersql);
                if ($selectorderresult->num_rows > 0) {
                    while ($row = $selectorderresult->fetch_assoc()) {
                        $email = $row['email'];
                        $image = '../'.$row['imagename'];
                        array_push($imagear, $image);
                    }
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }            // array with filenames to be sent as attachment
    $files = $imagear;

// email fields: to, from, subject, and so on
    $to = $email;
    $from = "info@mxicoders.com";
    $subject = "Your Benas Purchased Image";
    $message = "Please Find Attachment";
    //$headers = "From: $from";
    $headers = 'From: info@mxicoders.com' . "\r\n" .
        "CC: rushit@mxicoders.com";

// boundary 
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

// headers for attachment 
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
    

// multipart boundary 
    $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
    $message .= "--{$mime_boundary}\n";

// preparing attachments
    for ($x = 0; $x < count($files); $x++) {
        $file = fopen($files[$x], "rb");
        $data = fread($file, filesize($files[$x]));
        fclose($file);
        $fileatt_name = "Angrybaby".$x.".jpeg";
        $data = chunk_split(base64_encode($data));
        $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$fileatt_name\"\n" .
                "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
        $message .= "--{$mime_boundary}\n";
    }

// send

    mail($to, $subject, $message, $headers);
}
$conn->close();
?>
