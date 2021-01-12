<?php

namespace Skypress\Theme\Hooks;

defined('ABSPATH') or die('Cheatin&#8217; uh?');

use Skypress\Core\Hooks\ExecuteHooks;
use Skypress\Core\Configuration\LoaderConfiguration;

class EnqueueCss implements ExecuteHooks
{
    public function __construct(LoaderConfiguration $loader)
    {
        $this->loader = $loader;
    }

    public function hooks()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue()
    {
        $theme = $this->loader->getData();

        if(empty($theme->getCssConfiguration())){
            return;
        }

        foreach ($theme->getCssConfiguration() as $key => $item) {
            $md5 = md5($item->getUri());
            wp_enqueue_style(sprintf('load-css-%s', $md5), sprintf('%s/%s', get_template_directory_uri(), $item->getUri()), false);
        }
    }
}
