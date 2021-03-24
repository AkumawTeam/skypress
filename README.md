# Skypress

## Getting started

```sh
composer require akumawteam/skypress
```

### Use a plugin

```php
<?php

/*
Plugin Name: Example Skypress
Description: Example Skypress
Author: Thomas Deneulin
Domain Path: /languages/
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use Skypress\Core\Kernel;

Kernel::execute('plugin', ['file' => __FILE__, 'slug' => 'example-skypress'], [
    'custom-post-type' => true,
]);
```

### Add a custom post type

Create a folder `mu-plugins/skypress/custom-post-types/` folder.

Create a ".json" file that you call as you wish (eg. `movie.json` => `mu-plugins/skypress/custom-post-types/movie.json` ) :


```json
{
    "key" : "movies",
    "params" : {
        "public"             : true,
        "publicly_queryable" : true,
        "show_ui"            : true,
        "show_in_menu"       : true,
        "query_var"          : true,
        "labels" : {
            "name": "Movie"
        },
        "supports" : [
            "title", "editor","author"
        ]
    }
}
```


### Add a taxonomy

You need to activate the Taxonomy module to be loaded by the kernel :

```php 
Kernel::execute('plugin', ['file' => __FILE__, 'slug' => 'example-skypress'], [
    'taxonomy' => true,
]);
```

Create a folder `mu-plugins/skypress/taxonomies/` folder.

Create a ".json" file that you call as you wish (eg. `countries.json` => `mu-plugins/skypress/taxonomies/countries.json` ) :


```json
{
    "key" : "countries",
      "params": {
        "public": true,
        "publicly_queryable" :true,
        "hierarchical" : true
    },
    "post_types" : ["post"]
}
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