<?php

namespace Skypress\Theme\Entity;

defined('ABSPATH') or die('Cheatin&#8217; uh?');

class Css
{
    protected $uri = null;


    public function __construct(array $options)
    {
        if(isset($options['uri'])){
            $this->setUri($options['uri']);
        }
        
        if(isset($options['version'])){
            $this->setVersion($options['version']);
        }
    }

    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

}
