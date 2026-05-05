<?php 
// Set CORS headers
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: *");

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if CloudFront cookies are not set
//if (!empty($_COOKIE['CloudFront-Key-Pair-Id'])) {

    // Define the API URL to get cookies (add your API URL here)
    $url = "https://www.tutorix.com/api/sandbox/v2/getVideoURL"; 
    
    // Prepare the data to send in the POST request
    $params_data = array(
        "client_id" => "ESTX24",
        "client_secret" => "SEJKeDdjQW1OV1o2N2tEWGVJVnlxZz09",
        "access_token" => "gnPNnOOcNtnII1j2",
        "class_id" => "3",
        "subject_id" => "2",
        "section_id" => "1",
        "lecture_id" => "1"
    );
    
    $data = json_encode($params_data);  
    
    // Bearer token (add the actual token here)
    $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3d3dy50dXRvcml4LmNvbS8iLCJhdWQiOiJUSEVfQVVESUVOQ0UiLCJpYXQiOjE3Mjk3NjQ4MDAsImV4cCI6MTcyOTc2ODQwMCwiZGF0YSI6eyJjbGllbnRfaWQiOiJFU1RYMjQifX0.oD5z_rZ9gSj8_S6T6N3oqTJAlY4kIsuhcMf7RuyPVAA"; 
    
     $options = array(
           'http' => array(
               //'header'  => 'Content-type: application/x-www-form-urlencoded',
               'header'  =>  array ('Content-type: application/json', 'Authorization: Bearer '.$token),
               "Accept: application/json\r\n",
               'method'  => 'POST',
               //'content' => http_build_query($data)
              'content' => $data
           ),
      );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);  
        $result = json_decode($result,true);
      //print_r($result); 
      // exit;

    // Check if the request was successful and cookies are returned
    if (isset($result['cookies'])) {
        $cookies = $result['cookies'];

        // Set CloudFront cookies (adjust domain as needed)
        foreach ($cookies as $cookie_name => $cookie_value) {
            /* setcookie($cookie_name, $cookie_value, [
                'expires' => time() + 3600,  // 1-hour expiry
                'path' => '/',
                'domain' => '.estudent.tech',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'None'
            ]); */
             setcookie($cookie_name, $cookie_value, 0,'/','estudent.tech');
        }
    } else {
        echo "Failed to retrieve cookies.";
        exit;
    }
//}

// Define the CDN base URL
$cdn_url = 'https://tutorix.estudent.tech';

// Example class, subject, section, and lecture values
$class = 'class8';
$subject = '1';
$section = '1';
$lecture = '1';

// Validate if the required parameters are set
if (empty($class) || empty($subject) || empty($section) || empty($lecture)) {
    echo "Missing required parameters.";
    exit;
}

// Build the final video URL
$final_url = $result['data']['video_url']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorix from Dev Tutorialspoint</title>
    
    <!-- jQuery for dynamic content handling -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" crossorigin="anonymous"></script>
    
    <!-- Video.js CSS and JS for the video player -->
    <link href="https://vjs.zencdn.net/7.14.3/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/7.14.3/video.min.js"></script>

    <!-- Styles for video player layout -->
    <style>
        .divPlayer {
            width: 700px;
            height: 400px;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;         
            margin: auto;
        }
    </style>
</head>
<body align="center">
    <div class="divPlayer">
        <!-- Video.js player container -->
        <div id="divVideo"></div>
    </div>

    <script>
        // Dynamically create video player HTML
        var str = '<div class="media-parent"><div class="media-child"><video id="player_one" class="video-js vjs-fluid vjs-default-skin" controls preload playsinline webkit-playsinline crossorigin="anonymous" ><source src="<?php echo $final_url;?>" type="application/x-mpegurl"></video></div></div>'; 
        
        // Append the video player to the divVideo element
        $('#divVideo').html(str);

        // Video.js player setup
        videojs.options.hls.overrideNative = true;
        var player = videojs('player_one', {
            autoplay: true, 
            html5: { hls: { withCredentials: true } }  // Ensures cookies are sent with HLS requests
        });
    </script>
</body>
</html>
