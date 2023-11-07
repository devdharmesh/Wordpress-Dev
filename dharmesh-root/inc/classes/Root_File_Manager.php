<?php

/**
 * @package Dharmesh_Root
 */
if (!class_exists('Root_File_Manager')) {
    class Root_File_Manager
    {
        public function create_file($filename, $content = null)
        {
            try {
                if (!file_exists($filename)) {
                    $handle = @fopen($filename, 'w');
                    if ($handle) {
                        fwrite($handle, $content);
                        fclose($handle);
                        return true;
                    } else {
                        throw new Exception('Error file not create: ' . $filename);
                    }
                }
            } catch (Exception  $e) {
                echo $e->getMessage();
            }
            return false;
        }

        public function create_directory($dirname)
        {
            if (!is_dir($dirname)) {
                mkdir($dirname, 0755, true);
                return true;
            }
            return false;
        }
    }
}
