# Skypress Menu

## Getting started

```sh
composer require akumawteam/skypress-menu
```

### Add a menu

You need to activate the Menu module to be loaded by the kernel :

```php 
Kernel::execute('plugin', ['file' => __FILE__, 'slug' => 'example-skypress'], [
    'menu' => true,
]);
```

Create a folder `mu-plugins/skypress/menus/` folder.

Create a ".json" file that you call as you wish (eg. `header.json` => `mu-plugins/skypress/menus/header.json` ) :


```json
{
    "location" : "header",
    "description" : "Header"
}
```