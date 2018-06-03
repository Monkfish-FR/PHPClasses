<?php
    /**
     * Autoloader class
     *
     * Attempts to load classes called by their names (PSR-0)
     */
    class Autoloader {

        /**
         * Register a function with the spl provided __autoload queue
         * @access public
         * @static
         * @param void
         */
        public static function register() {
            spl_autoload_register([__CLASS__, 'loadClass']);
        }

        /**
         * Autoload a class called by its name
         * @access public
         * @static
         * @param string $class_name The name of the class to load
         * @throws MonkException if the file doesn't exist
         */
        public static function loadClass($class_name) {
            $class = ltrim($class_name, '\\');
            $file_name  = '';
            $namespace = '';
            if ($last_pos = strrpos($class, '\\')) {
                $namespace = substr($class, 0, $last_pos);
                $class = substr($class, $last_pos + 1);
                $file_name  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $file_name .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

            if (file_exists(__DIR__ . '/' . $file_name)) {
                require_once $file_name;
            }
            else {
                $e = 'The requested class <b>' . $class_name . '</b> could not be found. Please check your code.';
                throw new \Monkfish\MonkException($e, __CLASS__);
            }
        }

    }