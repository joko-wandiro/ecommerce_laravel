<?php

if (!function_exists('settings')) {

    /**
     * Get settings by key
     * 
     * @param string $key
     * @return string
     */
    function settings($key)
    {
        return isset($GLOBALS['settings'][$key]) ? $GLOBALS['settings'][$key] : "";
    }

}

if (!function_exists('url_category')) {

    /**
     * Get category url
     * 
     * @param string $key
     * @return string
     */
    function url_category($category_name)
    {
        return url('category/' . to_url_component($category_name));
    }

}

if (!function_exists('url_product')) {

    /**
     * Get category url
     * 
     * @param string $key
     * @return string
     */
    function url_product($product_name)
    {
        return url('product/' . to_url_component($product_name));
    }

}

if (!function_exists('to_number')) {

    /**
     * Change thousand format to number
     * 
     * @param string $thousand_format
     * @return int
     */
    function to_number($thousand_format = 0)
    {
        return str_replace(".", "", $thousand_format);
    }

}

if (!function_exists('getThousandFormat')) {

    /**
     * Get thousand format of number
     * 
     * @param int $number
     * 
     * @return string
     */
    function getThousandFormat($number = 0)
    {
        return number_format($number, 0, config('app.decimal_separator'), config('app.thousand_separator'));
    }

}

if (!function_exists('categoryImagePath')) {

    function categoryImagePath($filename = null)
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR;
        $path = (!$filename) ? $path : $path . $filename;
        return public_path($path);
    }

}

if (!function_exists('categoryImageTemporaryPath')) {

    function categoryImageTemporaryPath($filename = null)
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
        $path = (!$filename) ? $path : $path . $filename;
        return public_path($path);
    }

}

if (!function_exists('categoryImageUrl')) {

    function categoryImageUrl($filename = null)
    {
        return asset('uploads/categories/' . $filename);
    }

}

if (!function_exists('productImagePath')) {

    function productImagePath($filename = null)
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR;
        $path = (!$filename) ? $path : $path . $filename;
        return public_path($path);
    }

}

if (!function_exists('productImageTemporaryPath')) {

    function productImageTemporaryPath($filename = null)
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
        $path = (!$filename) ? $path : $path . $filename;
        return public_path($path);
    }

}

if (!function_exists('imagesTemporaryPath')) {

    function imagesTemporaryPath($filename = null)
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
        $path = (!$filename) ? $path : $path . $filename;
        return public_path($path);
    }

}

if (!function_exists('imagesPath')) {

    function imagesPath($filename = null)
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR;
        $path = (!$filename) ? $path : $path . $filename;
        return public_path($path);
    }

}

if (!function_exists('image_url')) {

    function image_url($filename = null)
    {
        return url('uploads/' . $filename);
    }

}

if (!function_exists('productImageUrl')) {

    function productImageUrl($filename = null)
    {
        return asset('uploads/products/' . $filename);
    }

}

if (!function_exists('getUniqueFilename')) {

    /**
     * Get unique filename
     *
     * @return string
     */
    function getUniqueFilename()
    {
        return time() . str_random(8);
    }

}
if (!function_exists('getFormInputError')) {

    /**
     * Get form input error
     *
     * @return string
     */
    function getFormInputError($column)
    {
        ob_start();
        $errors = request()->session()->get('errors');
        if ($errors) {
            $errorMessages = $errors->getMessages();
            $labelError = '<label class="error">%1$s</label>';
            // Display errors
            if (isset($errorMessages[$column])) {
                foreach ($errorMessages[$column] as $errorMessage) {
                    echo sprintf($labelError, e($errorMessage));
                }
            }
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}
// Old functions
if (!function_exists('to_url_component')) {

    /**
     * Url component
     * 
     * @param string $value
     * 
     * @return string
     */
    function to_url_component($value)
    {
        return str_replace(" ", "-", strtolower($value));
    }

}

if (!function_exists('jwt_parameters')) {

    /**
     * Get jwt parameters
     * 
     * @return StdClass
     */
    function jwt_parameters()
    {
        $token = request()->bearerToken();
        $parameters = Firebase\JWT\JWT::decode($token, config('auth.jwt_key'), array('HS256'));
        return $parameters;
    }

}
