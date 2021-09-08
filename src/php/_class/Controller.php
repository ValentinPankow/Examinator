<?php

    require_once './vendor/autoload.php';
    require_once './autoloader.php';

    use Symfony\Component\HttpFoundation\Request;

    class Controller
    {
        public $twig;
        public $container;

        public function __construct()
        {
            $this->container = new Core\Container();
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

            // Render the main page or the sub page by using require
            switch ($uri) {
                case '/':
                    //Nur als Beispiel, noch kein Routing implementiert
                    $barController = $this->container->make("barController");
                    $barController->index('index', $this->twig);
                    break;
                case '/foo':
                    //Nur als Beispiel, noch kein Routing implementiert
                    $barController = $this->container->make("fooController");
                    $barController->index('foo', $this->twig);
                    break;
                case preg_match("/\/foo\/\\d+$/", $uri)?true:false:
                    //Nur als Beispiel, noch kein Routing implementiert
                    // $id = substr($uri, 5);
                    $barController = $this->container->make("fooController");
                    // $barController->index($id, 'foo', $this->twig);
                    $barController->index('foo', $this->twig);
                    break;
                default:
                    //Nur als Beispiel, noch kein Routing implementiert
                    $barController = $this->container->make("barController");
                    $barController->index('index', $this->twig);
                    break;
            }
        }
    }