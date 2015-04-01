WPU Thumbnails
======

Centralized way to add Thumbnails sizes to WordPress. Generate thumbnail only for a specific post type, hide it from admin gallery, ...

How to install :
---

* Put this folder to your wp-content/plugins/ folder.
* Activate the plugin in "Plugins" admin section.

How to add sizes :
---

Put the code below in your theme's functions.php file. Add new tabs to your convenance.

```php
add_filter('wpu_thumbnails_sizes', 'set_wpu_thumbnails_sizes');
function set_wpu_thumbnails_sizes($sizes) {
    $sizes['test'] = array(
        'w' => 10,
        'h' => 10,
        'crop' => true, // Boolean (not required : Default to false)
        'name' => 'Test', // String (not required : Default to ID)
        'post_type' => array('page'), // Array (not required : Default to array('any'))
        'display_gallery_insert' => false, // Boolean (not required : Default to true)
    );
    return $sizes;
}
```