<?php

/**
 * Panada Html Library.
 * 
 * @package	Libraries
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Harry Sudana <harrysudana@gmail.com>
 * @since	Version 0.1
 */

namespace Libraries;

class Html {
    //private $uri;
    
    public static function chars($value, $double_encode = TRUE) {
        $config = \Resources\Config::website();
        return htmlspecialchars((string) $value, ENT_QUOTES, $config['charset'], $double_encode);
    }

    public static function style($file, array $attributes = NULL, $index = FALSE) {
        if (strpos($file, '://') === FALSE) {
            // Add the base URL
            $config = \Resources\Config::website();
            $file = $config['base_url'] . $file;
        }

        // Set the stylesheet link
        $attributes['href'] = $file;

        // Set the stylesheet rel
        $attributes['rel'] = 'stylesheet';

        // Set the stylesheet type
        $attributes['type'] = 'text/css';

        return '<link' . Html::attributes($attributes) . ' />';
    }

    public static function attributes(array $attributes = NULL) {
        if (empty($attributes))
            return '';

        /*
          $sorted = array();
          foreach (HTML::$attribute_order as $key) {
          if (isset($attributes[$key])) {
          // Add the attribute to the sorted list
          $sorted[$key] = $attributes[$key];
          }
          }

          // Combine the sorted attributes
          $attributes = $sorted + $attributes;
         * 
         */

        $compiled = '';
        foreach ($attributes as $key => $value) {
            if ($value === NULL) {
                // Skip attributes that have NULL values
                continue;
            }

            if (is_int($key)) {
                // Assume non-associative keys are mirrored attributes
                $key = $value;
            }

            // Add the attribute value
            $compiled .= ' ' . $key . '="' . Html::chars($value) . '"';
        }

        return $compiled;
    }

    public static function script($file, array $attributes = NULL, $index = FALSE) {
        if (strpos($file, '://') === FALSE) {
            // Add the base URL
            $config = \Resources\Config::website();
            $file = $config['base_url'] . $file;
        }

        // Set the script link
        $attributes['src'] = $file;

        // Set the script type
        $attributes['type'] = 'text/javascript';

        return '<script' . Html::attributes($attributes) . '></script>';
    }

}