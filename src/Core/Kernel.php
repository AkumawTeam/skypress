<?php

namespace Skypress\Core;

defined('ABSPATH') or die('Cheatin&#8217; uh?');

use Skypress\Core\Container\ManageContainer;
use Skypress\Core\Container\ContainerSymfony;
use Skypress\Core\Container\ContainerSkypress;
use Skypress\Core\Hooks\ExecuteHooksBackend;
use Skypress\Core\Hooks\ExecuteHooksFrontend;
use Skypress\Core\Hooks\ExecuteHooks;
use Skypress\Core\Hooks\ActivationHook;
use Skypress\Core\Hooks\DeactivationHook;

abstract class Kernel
{
    protected static $container = null;
    
    protected static $containerSkypress = null;

    protected static $data = [
        'slug' => null, 
        'file' => null, 
        'root' => null,
        'namespace' => null
    ];

    protected static $options = [
        'custom-post-type' => false,
        'taxonomy' => false,
        'menu' => false,
        'theme' => false,
        'headless' => false
    ];

    public static function setContainer(ManageContainer $container)
    {
        self::$container = self::getDefaultContainer();
    }

    protected static function getDefaultContainer()
    {
        return new ContainerSymfony();
    }

    protected static function getDefaultContainerSkypress(){
        return new ContainerSkypress();
    }

    public static function getContainer()
    {
        if (null === self::$container) {
            self::$container = self::getDefaultContainer();
        }

        return self::$container;
    }

    public static function getContainerSkypress()
    {
        if (null === self::$containerSkypress) {
            self::$containerSkypress = self::getDefaultContainerSkypress();
        }

        return self::$containerSkypress;
    }

    public static function handleHooks()
    {
        foreach (self::getContainer()->getServicesByTag('hooks') as $id => $tags) {
            $class = self::getContainer()->getBuilder()->get($id);

            switch (true) {
                case $class instanceof ExecuteHooksBackend:
                    if (is_admin()) {
                        $class->hooks();
                    }
                    break;

                case $class instanceof ExecuteHooksFrontend:
                    if (!is_admin()) {
                        $class->hooks();
                    }
                    break;

                case $class instanceof ExecuteHooks:
                    $class->hooks();
                    break;
            }
        }

        foreach (self::getContainerSkypress()->getActions() as $key => $class) {
            $class = new $class();

            switch (true) {
                case $class instanceof ExecuteHooksBackend:
                    if (is_admin()) {
                        $class->hooks();
                    }
                    break;

                case $class instanceof ExecuteHooksFrontend:
                    if (!is_admin()) {
                        $class->hooks();
                    }
                    break;

                case $class instanceof ExecuteHooks:
                    $class->hooks();
                    break;
            }
        }
    }

    public static function handleHooksPlugin()
    {
        switch (current_filter()) {
            case 'plugins_loaded':
                self::handleHooks();
                break;
            case 'activate_' . $slug . '/' . $slug . '.php':
                foreach (self::getContainer()->getServicesByTag('hooks') as $id => $tags) {
                    $class = self::getContainer()->get($id);
                    if ($class instanceof ActivationHook) {
                        $class->activation();
                    }
                }
                break;
            case 'deactivate_' . $slug . '/' . $slug . '.php':
                $class = self::getContainer()->get($id);
                if ($class instanceof DeactivationHook) {
                    $class->activation();
                }
                break;
        }
    }

    protected static function buildContainerSkypress(){
        self::getClasses(self::$data['root'] . '/src/Services', 'services', 'Services\\');
        self::getClasses(self::$data['root'] . '/src/Actions', 'actions', 'Actions\\');
    }

    /**
     * @static
     * @param string $path
     * @param string $type
     * @param string $namespace
     * @return void
     */
    public static function getClasses($path, $type, $namespace = '')
    {
        if(!file_exists($path)){
            return;
        }

        $files      = array_diff(scandir($path), [ '..', '.' ]);
        foreach ($files as $filename) {
            $pathCheck = $path . '/' . $filename;
            if (is_dir($pathCheck)) {
                self::getClasses($pathCheck, $type, $namespace . $filename . '\\');
                continue;
            }

            $data = '\\' . self::$data['namespace'] . '\\' . $namespace . str_replace('.php', '', $filename);

            switch ($type) {
                case 'services':
                    self::getContainerSkypress()->setService($data);
                    break;
                case 'actions':
                    self::getContainerSkypress()->setAction($data);
                    break;
            }
        }
    }

    /**
     * Build module custom post type.
     */
    protected static function buildCustomPostType()
    {
        self::getContainer()->set('LoaderCustomPostType', '\Skypress\CustomPostType\Configuration\Loader', [
            self::getContainer()->get('LoaderConfiguration'),
        ]);
        self::getContainer()->getBuilder()->getDefinition('LoaderCustomPostType')->setShared(false);

        self::getContainer()->set('RegisterPostType', '\Skypress\CustomPostType\Hooks\RegisterPostType', [
            self::getContainer()->get('LoaderCustomPostType'),
        ]);

        // @TODO : Too symfony related
        self::getContainer()->getBuilder()->getDefinition('RegisterPostType')
            ->addTag('hooks');
    }

    /**
     * Build module taxonomy.
     */
    protected static function buildTaxonomy()
    {
        self::getContainer()->set('LoaderTaxonomy', '\Skypress\Taxonomy\Configuration\Loader', [
            self::getContainer()->get('LoaderConfiguration'),
        ]);

        self::getContainer()->getBuilder()->getDefinition('LoaderTaxonomy')->setShared(false);

        self::getContainer()->set('RegisterTaxonomy', '\Skypress\Taxonomy\Hooks\RegisterTaxonomy', [
            self::getContainer()->get('LoaderTaxonomy'),
        ]);

        // @TODO : Too symfony related
        self::getContainer()->getBuilder()->getDefinition('RegisterTaxonomy')
            ->addTag('hooks');
    }

    /**
     * Build module headless.
     */
    protected static function buildHeadlessModule()
    {
        self::getContainer()->set('ApiMenu', '\Skypress\Headless\Hooks\Api\Menu', [
            \Skypress\Headless\Settings::getBaseEndpoint(),
        ]);

        // @TODO : Too symfony related
        self::getContainer()->getBuilder()->getDefinition('ApiMenu')
            ->addTag('hooks');
    }

    /**
     * Build module menu.
     */
    protected static function buildMenu()
    {
        self::getContainer()->set('LoaderMenu', '\Skypress\Menu\Configuration\Loader', [
            self::getContainer()->get('LoaderConfiguration'),
        ]);

        self::getContainer()->getBuilder()->getDefinition('LoaderMenu')->setShared(false);

        self::getContainer()->set('RegisterMenu', '\Skypress\Menu\Hooks\RegisterMenu', [
            self::getContainer()->get('LoaderMenu'),
        ]);

        // @TODO : Too symfony related
        self::getContainer()->getBuilder()->getDefinition('RegisterMenu')
            ->addTag('hooks');
    }

    /**
     * Build module menu.
     */
    protected static function buildThemeModule()
    {
        self::getContainer()->set('LoaderTheme', '\Skypress\Theme\Configuration\Loader', [
            self::getContainer()->get('LoaderConfiguration'),
        ]);

        self::getContainer()->getBuilder()->getDefinition('LoaderTheme')->setShared(false);

        self::getContainer()->set('EnqueueCss', '\Skypress\Theme\Hooks\EnqueueCss', [
            self::getContainer()->get('LoaderTheme'),
        ]);

        // @TODO : Too symfony related
        self::getContainer()->getBuilder()->getDefinition('EnqueueCss')
            ->addTag('hooks');
    }

    /**
     * Build Skypress Container.
     */
    protected static function buildContainer(){

        self::getContainer()->set('LoaderConfiguration', 'Skypress\Core\Configuration\Loader' , [
            'rootDirectory' => self::$data['root']
        ]);
        self::getContainer()->getBuilder()->getDefinition('LoaderConfiguration')->setShared(false);

        if (isset(self::$options['custom-post-type']) && true === self::$options['custom-post-type']) {
            self::buildCustomPostType();
        }

        if (isset(self::$options['taxonomy']) && true === self::$options['taxonomy']) {
            self::buildTaxonomy();
        }

        if (isset(self::$options['menu']) && true === self::$options['menu']) {
            self::buildMenu();
        }

        if (isset(self::$options['headless']) && true === self::$options['headless']) {
            self::buildHeadlessModule();
        }

        if (isset(self::$options['theme']) && true === self::$options['theme']) {
            self::buildThemeModule();
        }
    }

    /**
     * @return Kernel
     */
    public static function execute($type = KernelTypeExecution::DEFAULT_EXEC, $data, $options = [])
    {
        self::$options = array_merge(self::$options, $options);
        self::$data = array_merge(self::$data, $data);
        
        self::buildContainer();

        if(isset(self::$data['namespace'], self::$data['root']) && self::$data['namespace'] !== null && self::$data['root'] !== null){
            self::buildContainerSkypress();
        }
        
        if (KernelTypeExecution::DEFAULT_EXEC === $type) {
            self::handleHooks();
            return;
        }

        if (KernelTypeExecution::PLUGIN === $type && isset($data['file']) && null !== $data['file']) {
            
            add_action('plugins_loaded', [__CLASS__, 'handleHooksPlugin']);
            register_activation_hook($data['file'], [__CLASS__, 'handleHooksPlugin']);
            register_deactivation_hook($data['file'], [__CLASS__, 'handleHooksPlugin']);
        }
    }
}
