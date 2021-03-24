<?php

namespace Skypress\Core\Container;

defined('ABSPATH') or die('Cheatin&#8217; uh?');

class ContainerSkypress
{

    /**
     * List actions WordPress.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * List class services.
     *
     *
     * @var array
     */
    protected $services = [];

    /**
     * Set actions.
     *
     *
     * @param array $actions
     *
     * @return Bootstrap
     */
    public function setActions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    public function setAction($action)
    {
        $this->actions[$action] = $action;

        return $this;
    }

    /**
     * Get services.
     *
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param string $name
     *
     * @return object
     */
    public function getAction($name)
    {
        try {
            if (!array_key_exists($name, $this->actions)) {
                // @TODO : Throw exception
                return null;
            }

            if (is_string($this->actions[$name])) {
                $this->actions[$name] = new $this->actions[$name]();
            }

            return $this->actions[$name];
        } catch (\Exception $th) {
            return null;
        }
    }

    /**
     * Set services.
     *
     *
     * @param array $services
     *
     * @return Bootstrap
     */
    public function setServices($services)
    {
        foreach ($services as $service) {
            $this->setService($service);
        }

        return $this;
    }

    /**
     * Set a service.
     *
     *
     * @param string $service
     *
     * @return Bootstrap
     */
    public function setService($service)
    {
        $name = explode('\\', $service);
        end($name);
        $key = key($name);
        $this->services[$name[$key]] = $service;

        return $this;
    }

    /**
     * Get services.
     *
     *
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Get one service by classname.
     *
     * @param string $name
     *
     * @return object
     */
    public function getService($name)
    {
        try {
            if (!array_key_exists($name, $this->services)) {
                // @TODO : Throw exception
                return null;
            }

            if (is_string($this->services[$name])) {
                $this->services[$name] = new $this->services[$name]();
            }

            return $this->services[$name];
        } catch (\Exception $th) {
            return null;
        }
    }



    /**
     * @static
     * @since 1.0
     * @var Bootstrap|null
     */
    protected static $context;

    /**
     * Create context if not exist
     *
     * @static
     * @since 1.0
     * @return void
     */
    public static function getContext()
    {
        if (null !== self::$context) {
            return self::$context;
        }

        self::$context = new Bootstrap();

        self::getClasses(__DIR__ . '/Services', 'services', 'Services\\');
        self::getClasses(__DIR__ . '/Actions', 'actions', 'Actions\\');



        return self::$context;
    }

}
