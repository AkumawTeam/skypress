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

}
