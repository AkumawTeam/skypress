<?php

namespace Skypress\Theme\Entity;

defined('ABSPATH') or die('Cheatin&#8217; uh?');

class Theme
{
    protected $cssConfiguration = [];


    public function __construct(array $options = [])
    {
        if(isset($options['css'])){
            $this->setCssConfiguration($options['css']);
        }
    }

    public function addCssConfiguration($cssConfiguration) {
        $this->cssConfiguration[] = $cssConfiguration;
        return $this;
    }

    public function setCssConfiguration($cssConfiguration)
    {
        $this->cssConfiguration = $cssConfiguration;

        return $this;
    }

    public function getCssConfiguration()
    {
        return $this->cssConfiguration;
    }

}
