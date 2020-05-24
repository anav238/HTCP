<?php

    class GithubConnect extends Controller 
    {
        public function index() 
        {
            if (isset($_GET["code"])) {
                $access_token = $this->getAccessToken();
                $this->loginUser($access_token);
                header( "Location: http://" . getenv('HOST') . '/');
                //exit;
            }
            else {
                //header( "Location: /login" );
                echo "Error";
            }
        }

        public function getAccessToken() {
            $url = 'https://github.com/login/oauth/access_token';

            $headers = array(
                'Accept: application/json'
            );

            $fields = array(
                'client_id' => urlencode(getenv("GITHUB_CLIENT_ID")),
                'client_secret' => urlencode(getenv("GITHUB_CLIENT_SECRET")),
                'code' => urlencode($_GET["code"])
            );

            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            $result_json = json_decode($result);
            curl_close($ch);

            return $result_json->{"access_token"};
        }

        public function loginUser($access_token) {
            $url = 'https://api.github.com/user';

            $headers = array(
                'Accept: application/json', 
                'User-Agent: HTCP',
                'Authorization: token ' . $access_token
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);

            $result = curl_exec($ch);
            $result_json = json_decode($result);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            $username = $result_json->{"login"};
            $avatar =  $result_json->{"avatar_url"};

            require_once __DIR__.'/../models/User.php';
            $newAccessToken = User::loginUser($username, $avatar);
            $_SESSION['accessToken'] = $newAccessToken;
        }
    }
