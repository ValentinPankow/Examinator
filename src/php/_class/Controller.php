<?php

    require_once './vendor/autoload.php';
    require_once './autoloader.php';

    use Symfony\Component\HttpFoundation\Request;

    class Controller
    {
        public $twig;
        public $container;
        public $GET;

        public function __construct()
        {
            $this->container = new Core\Container();
            $this->GET = $_GET;
        }

        public function display()
        {
            $request = Request::createFromGlobals();
            $uri = $request->getPathInfo();

            $subStr = substr($uri, 1);
            $fileSystem = './templates/twig/';

            $loader = new Twig_Loader_Filesystem($fileSystem);
            $this->twig = new Twig_Environment($loader, array(
                'cache' => false,
                'debug' => true,
                'strict_variables' => true
            ));
            $this->twig->addExtension(new Twig_Extension_Debug());

            if (file_exists('templates/twig/' . strtolower($this->GET['page']) . ".twig")) {
                $pageController = $this->container->make(strtolower($this->GET['page']) . "Controller");
                $pageController->index(strtolower($this->GET['page']), $this->twig);
            } else {
                echo $this->twig->render("404.twig", array(
                    'pageTitle' => 'Examinator - 404',
                    'applicationName' => 'Examinator'
                ));
            }

            /*$this->twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
                // implement whatever logic you need to determine the asset path
                return './' . $asset;
            }));*/

            /*if ($uri == "/") {
                $page = "main";
            } else {
                $page = $subStr;
            }

            // Render the main page or the sub page by using require
            switch ($page) {
                case 'login':
                    $loginController = $this->container->make("loginController");
                    $loginController->index('login', $this->twig);
            switch ($uri) {
                case '/':
                    //Nur als Beispiel, noch kein Routing implementiert
                    $dashboardController = $this->container->make("dashboardController");
                    $dashboardController->index('index', $this->twig);
                    break;
                case '/foo':
                    //Nur als Beispiel, noch kein Routing implementiert
                    $barController = $this->container->make("fooController");
                    $barController->index('foo', $this->twig);
                    break;
                case 'main':
                    // Nur als Beispiel, noch kein Routing implementiert
                    $dashboardController = $this->container->make("dashboardController");
                    $dashboardController->index('index', $this->twig);
                    break;
                default:
                    // Nur als Beispiel, noch kein Routing implementiert
                    $dashboardController = $this->container->make("dashboardController");
                    $dashboardController->index('index', $this->twig);
                    break;
            }*/
        }
    }