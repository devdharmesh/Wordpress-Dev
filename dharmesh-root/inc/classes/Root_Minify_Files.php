<?php

/**
 * @package Dharmesh_Root
 */

if (!class_exists('Root_Minify_Files')) {
    class Root_Minify_Files
    {
        public function minify_css($source_path, $target_path)
        {
            // Read the contents of the source file
            $css = file_get_contents($source_path);

            // Minify the CSS
            $minified_css = preg_replace('/\s+/', ' ', $css); // Remove multiple spaces
            $minified_css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css); // Remove multiple spaces
            $minified_css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
            $minified_css = preg_replace('/\/\*.*?\*\//i', '', $minified_css); // Remove comments
            $minified_css = str_replace(': ', ':', $minified_css); // Remove spaces after colons
            $minified_css = str_replace('; ', ';', $minified_css); // Remove spaces after semicolons
            $minified_css = str_replace(' {', '{', $minified_css); // Remove spaces before opening brackets
            $minified_css = str_replace('{ ', '{', $minified_css); // Remove spaces after opening brackets
            $minified_css = str_replace('} ', '}', $minified_css); // Remove spaces after closing brackets
            $minified_css = str_replace(', ', ',', $minified_css); // Remove spaces after commas
            $minified_css = trim($minified_css);

            // Save the minified CSS to the target file
            file_put_contents($target_path, $minified_css);
        }

        public function minify_js($source_path, $target_path)
        {
            // Read the contents of the source file
            $js = file_get_contents($source_path);

            // Minify the JavaScript
            $minified_js = preg_replace('/\/\/[^\n\r]*/', '', $js); // Remove single-line comments
            $minified_js = preg_replace('/\/\*[\s\S]*?\*\//', '', $minified_js); // Remove multi-line comments
            $minified_js = preg_replace('/\s+/', ' ', $minified_js); // Remove multiple spaces
            $minified_js = str_replace('; ', ';', $minified_js); // Remove spaces after semicolons
            $minified_js = str_replace('{ ', '{', $minified_js); // Remove spaces after opening brackets
            $minified_js = str_replace('} ', '}', $minified_js); // Remove spaces after closing brackets
            $minified_js = str_replace(', ', ',', $minified_js); // Remove spaces after commas
            $minified_js = trim($minified_js);

            // Save the minified JavaScript to the target file
            file_put_contents($target_path, $minified_js);
        }

        public function minify_theme_files($files)
        {
            foreach ($files as $file) {
                $fileinfo = pathinfo( $file );
                $original_file = $file;
                $minified_file_url = trailingslashit( $fileinfo['dirname'] ) . $fileinfo['filename'] . '.min.' . $fileinfo['extension'];
                $original_mtime = filemtime($original_file);
                if (file_exists(str_replace(home_url(), ABSPATH, $minified_file_url))) {
                    $minified_mtime = filemtime(str_replace(home_url(), ABSPATH, $minified_file_url));
                    if ($original_mtime > $minified_mtime) {
                        if ($fileinfo['extension'] == 'css') {
                            $this->minify_css($original_file, $minified_file_url);
                        } else if ($fileinfo['extension'] == 'js') {
                            $this->minify_js($original_file, $minified_file_url);
                        }
                    } 
                } else {
                    if ($fileinfo['extension'] == 'css') {
                        $this->minify_css($original_file, $minified_file_url);
                    } else if ($fileinfo['extension'] == 'js') {
                        $this->minify_js($original_file, $minified_file_url);
                    }
                }
            }
        }
    }
}
