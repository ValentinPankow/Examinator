<?php

    // Creator = VP

    require_once './vendor/autoload.php';
    require_once './autoloader.php';
    require_once("./src/php/db_config.php");

    use Symfony\Component\HttpFoundation\Request;

    class PageController
    {
        public $twig;
        public $container;
        public $GET;
        public $testvar = "jop";

        // Constructor, startet die Session
        public function __construct()
        {
            $this->container = new Core\Container();
            $this->GET = $_GET;
            session_start();
        }

        // Funktion zum anzeigen der Seite
        public function display()
        {

            // Get the request
            $request = Request::createFromGlobals();
            $uri = $request->getPathInfo();

            // Set the folder where the template files are located
            $fileSystem = './templates/twig/';

            // Initialize twig loader and environment
            $loader = new Twig_Loader_Filesystem($fileSystem);
            $this->twig = new Twig_Environment($loader, array(
                'cache' => false,
                'debug' => true,
                'strict_variables' => true
            ));
            $this->twig->addExtension(new Twig_Extension_Debug());

            // Add Custom twig filter function
            $filter = new \Twig\TwigFilter('preg_replace', 
                function ($subject, $pattern, $replacement) {
                    $rtn = preg_replace($pattern, $replacement, $subject);
                    if (strlen(trim($rtn)) > 0) {
                        return $rtn;
                    } else {
                        return false;
                    }
                }
            );
            $this->twig->addFilter($filter);

            // Add custom twig functions
            $formatDate = new \Twig\TwigFunction('formatDate', function($date) {
                // Gibt das Datum im Format dd.mm.yyyy zurück
                return substr($date, 8, 2) . "." . substr($date, 5, 2) . "." . substr($date, 0, 4);
            });
            $formatTime = new \Twig\TwigFunction('formatTime', function($time) {
                // Gibt das Datum im Format dd.mm.yyyy zurück
                return substr($time, 0, 2) . ":" . substr($time, 3, 2);
            });
            $this->twig->addFunction($formatDate);
            $this->twig->addFunction($formatTime);
            $loginController = $this->container->make("loginController");

            // Check login and requested page
            if (!isset($this->GET['page']) || !$this->checkLogin($loginController)) {
                // Load login page if user is not logged in
                if (!$this->checkLogin($loginController)) {
                    $loginController->index("login", $this->twig, false);
                } else { // Redirect to dashboard if the user is logged in
                    header("Refresh:0; url=?page=dashboard");
                }
            } else {
                // If the requested page is the logout page reset user cookie and session
                if($this->GET["page"] == "logout"){
                    if (session_status() === PHP_SESSION_ACTIVE) {
                        session_destroy();
                    }
                    setcookie("ClassesLogin","", time()-3600, "/" );
                    setcookie("UserLogin","", time()-3600, "/" );
                    header("Refresh:0; url=?page=login");
                } else {
                    // Redirect to dashboard if the user is logged in
                    if ($this->checkLogin($loginController) && $this->GET['page'] == "login") {
                        header("Refresh:0; url=?page=dashboard");
                    } 
                    // If the requested page exists create the page controller
                    if (file_exists('templates/twig/' . strtolower($this->GET['page']) . ".twig") && $this->GET['page'] != '404') {
                        $pageController = $this->container->make(strtolower($this->GET['page']) . "Controller");
                        $pageController->index(strtolower($this->GET['page']), $this->twig, $this->checkLogin($loginController));
                    } else {
                        // Requested page does not exists
                        echo $this->twig->render("404.twig", array(
                            'pageTitle' => 'Examinator - 404',
                            'applicationName' => 'Examinator',
                            'tpl' => '404'
                        ));
                    }
                }    
            }
        }

        // Checks if the user is logged in
        private function checkLogin ($loginController){
            $rtn = false;
            if (session_status() === PHP_SESSION_ACTIVE){
                if (isset($_COOKIE['UserLogin'])){
                    $userID = $_COOKIE['UserLogin'];
                    $sessionID = $loginController->getSessionID($userID);
                    if( session_id() == $sessionID){
                        $rtn = true;
                    }
                } else if(isset($_COOKIE['ClassesLogin'])){
                    $rtn = true;
                }
            }
            return $rtn;
        }
    }