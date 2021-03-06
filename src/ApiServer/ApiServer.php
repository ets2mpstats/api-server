<?php

namespace ApiServer;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\ErrorHandler;
use \Symfony\Component\Yaml\Parser;
use \Symfony\Component\Yaml\Exception\ParseException;
use \Klein\Klein;

class ApiServer {

    private $klein;

    private $config;

    private $logger;

    function __construct($controllers = null) {
        $this->readConfig(__DIR__ . '/../../conf/app.yml');
        $this->registerErrorLog();
        $this->setupDatabase();
        $this->setupRouting($controllers);
        $this->registerServices();
    }

    private function readConfig($file) {
        $yaml = new Parser();

        try {
            $this->config = $yaml->parse(file_get_contents($file));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
            die();
        }
    }

    private function registerErrorLog() {
        $this->logger = new Logger($this->config['logger']['instanceidentifier']);
        $this->logger->pushHandler(
            new StreamHandler($this->config['logger']['logpath'],
            $this->config['logger']['level'])
        );
        ErrorHandler::register($this->logger);
        $this->logger->addDebug('Logger initialized');
    }

    private function setupDatabase() {
      $this->logger->addDebug('Setting up ORM');
        \RedBeanPHP\R::setup(
            'mysql:host=' . $this->config['database']['host'] . ';dbname=' . $this->config['database']['name'],
            $this->config['database']['user'],
            $this->config['database']['pass']
        );
      $this->logger->addDebug('Done setting up ORM');
    }

    private function setupRouting($controllers = null) {
      $this->logger->addDebug('Registering routes', $controllers);
        if($this->klein == null){
            $this->klein = new Klein();
        }
        if(is_array($controllers)) {
            foreach($controllers as $controller) {
                $this->klein->with("/$controller", __DIR__."/../../controllers/$controller.php");
            }
        } elseif ($controllers != null) {
            $this->klein->with("/$controllers", __DIR__."/../../controllers/$controllers.php");
        }
          $this->logger->addDebug('Registered routes', $controllers);
    }

    private function registerServices() {
      $this->logger->addDebug('Registering services');
        $this->klein->respond(function($request, $response, $service, $app) {
            $app->register('logger', function() {
                $logger = new Logger($this->config['logger']['instanceidentifier']);
                $logger->pushHandler(
                    new StreamHandler($this->config['logger']['logpath'],
                        $this->config['logger']['level'])
                );
                return $logger;
            });
        });
      $this->logger->addDebug('Registered services');
    }

}
