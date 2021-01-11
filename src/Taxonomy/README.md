# Skypress Taxonomy

## Getting started

```sh
composer require ormeecommunity/skypress-taxonomy
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
