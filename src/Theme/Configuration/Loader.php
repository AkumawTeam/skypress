<?php

namespace Skypress\Theme\Configuration;

defined('ABSPATH') or die('Cheatin&#8217; uh?');

use Skypress\Core\Configuration\LoaderConfiguration;
use Skypress\Theme\Entity\Theme;
use Skypress\Theme\Entity\Css;

class Loader implements LoaderConfiguration
{
    /**
     * @param LoaderConfiguration $loader
     */
    public function __construct(LoaderConfiguration $loader)
    {
        $loader->setDirectoryConfiguration(sprintf('%s/%s', get_template_directory(), 'skypress'));
        $loader->setDirectoryTypeData('');
        $this->loader = $loader;
    }

    /**
     *
     * @param Theme $theme
     * @param array $data
     * @return Theme
     */
    protected function initCssConfiguration(Theme $theme, array $data){
        foreach ($data as $key => $item) {
            $css = new Css($item);
            $theme->addCssConfiguration($css);
        }
        
        return $theme;
    }

    /**
     *
     * @return Theme
     */
    public function getData()
    {
        $data = $this->loader->getData();

        foreach ($data as $key => $item) {
            if($key !== 'theme.json'){
                continue;
            }
            $theme = new Theme();
            if(isset($item['css'])){
                $theme = $this->initCssConfiguration($theme, $item['css']);
            }
        }

        return $theme;
    }
}
