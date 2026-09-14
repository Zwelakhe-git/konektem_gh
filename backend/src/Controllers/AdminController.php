<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\NewsModel;
use Konektem\Models\MusicModel;
use Konektem\Models\ServicesModel;
use Konektem\Models\EventsModel;
use Konektem\Models\LiveStreamModel;
use Konektem\Models\AdminModel;
use Konektem\Models\AlbumModel;
use Konektem\Models\InterviewsModel;
use Konektem\Models\BooksModel;
use Konektem\Models\PartnersModel;
use Konektem\Models\SiteSettingsModel;
use Konektem\Auth\Auth;
use Konektem\Utils\Log;

Log::init();

class AdminController {
    private $newsModel;
    private $musicModel;
    private $servicesModel;
    private $eventsModel;
    private $streamModel;
    private $auth;
    private $model;
    private $albumModel;
    private $interviewsModel;
    private $booksModel;
    private $partnersModel;
    private $siteSettingsModel;
    
    public function __construct() {
        $this->newsModel = new NewsModel();
        $this->musicModel = new MusicModel();
        $this->servicesModel = new ServicesModel();
        $this->eventsModel = new EventsModel();
        $this->streamModel = new LiveStreamModel();
        $this->albumModel = new AlbumModel();
        $this->interviewsModel = new InterviewsModel();
        $this->booksModel = new BooksModel();
        $this->partnersModel = new PartnersModel();
        $this->siteSettingsModel = new SiteSettingsModel();
        $this->auth = new Auth();
        $this->model = new AdminModel();
    }
    
    public function dashboard() {
        $stats = [
            'news_count' => count($this->newsModel->getAllArticles()),
            'music_count' => count($this->musicModel->getMusicByOwner($owner = $_SESSION['name'])),
            'services_count' => count($this->servicesModel->getServicesByOwner($owner = $_SESSION['name'])),
            'events_count' => count($this->eventsModel->getEventsByOwner($owner = $_SESSION['name'])),
            'streams_count' => count($this->streamModel->getAllStreams())
        ];
    }

    public function index(){
        return [
            'news' => $this->newsModel->getAllArticles(),
            'music' => $this->musicModel->getAllMusic(),
            'interviews' => $this->interviewsModel->getAllInterviews(),
            'events' => $this->eventsModel->getAllEvents(),
            'services' => $this->servicesModel->getAllServices(),
            'partners' => $this->partnersModel->getAllPartners(),
            'books' => $this->booksModel->getAllBooks(),
            'siteSettings' => $this->siteSettingsModel->getAllSettings(),
        ];
    }

    /** grant user login and save (refresh) session
     * @return array : success, message, name
     */
    public function login($role = 'guest'){
        try{
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                if ($this->auth->isLoggedIn($role)){
                    $this->auth->checkSessionTimeout();

                    return $this->auth->isLoggedIn($role) ?
                        ['success' => true, 'message' => $_SESSION['user']['name'], 'user' => $_SESSION['user'], 'token' => $_SESSION['token'] ] :
                        ['success' => false, 'message' => 'session timeout' ]
                    ;
                }
                $response = $role === 'admin' ? $this->model->login($_POST['email'], $_POST['password']) : $this->model->grantLogin($_POST);
                if($response["success"]){
                    $this->auth->login($response['user'], $response['token']);
                    
                    return [
                        'success' => true, 
                        'message' => 'Login successful', 
                        'user' => $response['user'],
                        'token' => $response['token']
                    ];
                } else {
                    return ["success" => false, "message" => $response['message']];
                }
            } else {
                return ["success" => false,'message' => 'missing parameters'];
            }
        } catch(Exception $e){
            Log::error("AdminController - userLogin - {$e->getMessage()}");
            return ["success" => false,'message' => "Server Error"];
        }
    }

    /**
     * validates user login and redirects to login if not logged in or session expired. doesnt return anything
     * @return void
     */
    public function requireLogin(){
        $this->auth->requireLogin();
    }

    public function premiumSubscription($username){
        header("Content-Type: Application/json; charset=utf-8");
        if(!$username){
            echo json_encode(["success" => false, "message" => "invalid username"]);
            exit;
        }
        // function is only called by users so it redirects to users account, not admin account
        $result = $this->model->subscribeUserToPremium($username);
        
        echo $result === true ? json_encode(["success" => true,
                               "redirected" => true,
                               "url" => "/account/me/index.php"]) :
            	json_encode(["success" => false, "message" => $result['message']]);
        exit;
    }
    
    private function validate($response){
        if($response['access'] == 'denied'){
            if($response['message'] == 'unregistered'){
                return ["success" => false, 'message' => 'account not found'];
            } elseif($response['message'] == 'wrong_password'){
               return ["success" => false, 'message' => 'Invalid password'];
            }
        } else {
            $curr_time = time();
            $_SESSION["user"] = $response["user"];
            //$_SESSION["name"] = $response['name'];
            $_SESSION["login_time"] = $curr_time;
            $_SESSION["sessionStart"] = $curr_time;
            $_SESSION["sessionEnd"] = $curr_time + (60 * 60);
            $_SESSION["sessionId"] = session_id();
            //$_SESSION["role"] = "guest";
            session_write_close();
            return true;
        }
        return ["success" => false, 'message' => $response['message']];
    }

    public function register(){
        if($_POST){
            $data = [
                'name' => $_POST['login'],
                'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'email' => $_POST['email']
            ];
            $result = $this->model->registerUser($data);
            return $result;
        }
        return ['success' => false, 'message' => 'missing parameters'];
    }
    public function logout(){
        if($this->model->redisClient){
            Log::info("deleting session from redis");
            $this->model->redisClient->del("session:user:{$_SESSION['user']['id']}");
        }
        $this->auth->logout();
    }
    public function getCurrentUser(){
        return isset($_SESSION['name']) ? ['name' => $_SESSION['name']] : ['name' => 'guest'];
    }
    
    public function mediaActivity(){
        if(isset($_GET["activity"]) && isset($_GET["id"]) && isset($_GET['user']) && isset($_GET['item'])){
            $activity = urldecode($_GET["activity"]);
            $item_id = urldecode($_GET["id"]);
            $user = urldecode($_GET['user']);
            $itemname = urldecode($_GET['item']);
            $response = $this->model->recordMediaActivity($activity, $item_id, $itemname, $user);
            return $response;
        }
        else{
            return ["response" => "failed", "message" => "missing parameters"];
        }
    }
    public function userActivities(){
        if(isset($_GET['u'])){
            $activities = $this->model->getUserActivity(urldecode($_GET['u']));
            return $activities;
        } else {
            return ["error" => 0, "message" => "user not set"];
        }
    }

       public function getGoogleAuthUrl() {
        // Check if Google auth config exists - try multiple paths
        $googleConfigPaths = [
            dirname(ADMIN_DIR) . '/config/google-auth.php',
            dirname(dirname(ADMIN_DIR)) . '/config/google-auth.php',
            ADMIN_DIR . '/../config/google-auth.php',
            ADMIN_DIR . '/../../config/google-auth.php'
        ];

        $googleConfigLoaded = false;
        foreach ($googleConfigPaths as $googleConfigPath) {
            if (file_exists($googleConfigPath)) {
                require_once $googleConfigPath;
                $googleConfigLoaded = true;
                break;
            }
        }

        if (!$googleConfigLoaded) {
            Log::error("Google auth config file not found. Checked paths: " . implode(", ", $googleConfigPaths));
            return ['error' => 'Google authentication not configured - config file missing'];
        }

        // Rest of the method remains the same...
        // Validate that required constants are defined
        if (!defined('GOOGLE_CLIENT_ID') || empty(GOOGLE_CLIENT_ID) || GOOGLE_CLIENT_ID === 'your-google-client-id.apps.googleusercontent.com') {
            return ['error' => 'Google OAuth configuration missing or not set'];
        }

        if (!defined('GOOGLE_REDIRECT_URI') || empty(GOOGLE_REDIRECT_URI)) {
            return ['error' => 'Google redirect URI not configured'];
        }

        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
            'access_type' => 'online',
            'prompt' => 'consent'
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

        return ['auth_url' => $authUrl];
    }
    public function grantStreamAccess(){
        try{
            if(isset($_POST["login"]) && isset($_POST["accessKey"]) && isset($_POST["password"])){
                $login = $_POST["login"];
                $accesskey = $_POST["accessKey"];
                $password = $_POST["password"];
                return $this->model->grantStream($login, $accesskey, $password);
                
            } else {
                return ["access" => "denied", "message" => "missing parameters"];
            }
        } catch(Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ["access" => "denied", "message" => "Server error"];
        }
    }

    public function registerViewer(){
        if(isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["accessKey"])){
            $response = $this->model->registerViewer($_POST["login"], $_POST["password"], $_POST["accessKey"]);
            return $response;
        }
        else{
            return ["success" => false, "message" => "missing fields"];
        }
    }
    
    public function emailRegister($email){
        $this->model->addEmailSubscriber($email);
    }
    
}
?>