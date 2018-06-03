<?php
    /**
     * Tools class
     *
     * Some needed functions and helpers
     */
    namespace Monkfish;

    class Tools {

        /**
         * Sanitize database inputs
         *
         * @link http://css-tricks.com/snippets/php/sanitize-database-inputs/
         *
         * @access public
         * @static
         * @param mixed $input The input to sanitize
         * @param string $isHTML Is the input is HTML (default: FALSE)
         * @return mixed The sanitized input
         */
        public static function sanitize($input, $isHTML = FALSE) {
            if (is_array($input)) {
                foreach ($input as $var => $val) {
                    $output[$var] = self::sanitize($val);
                }
            }
            else {
                if (get_magic_quotes_gpc()) {
                    $input = stripslashes($input);
                }

                if (!$isHTML) {
                    $input  = self::cleanInput($input);
                }

                $output = trim($input);
            }

            return $output;
        }

        /**
         * Strip tags ans multi-line comments
         * @access public
         * @static
         * @param string $input The input to clear
         * @return string The cleared input
         *
         * @see sanitize
         */
        public static function cleanInput($input) {
            $search = array(
                '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
                '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
            );

            return preg_replace($search, '', $input);
        }

        /**
         * Return an encoded character
         * @access public
         * @static
         * @param string $char The character
         * @param integer $force The encoding method
         * @return string The encoded character
         */
        public static function encode_char($char, $force = 0) {
            $x = rand(1,3);

            if(($force == 0 && $x == 1) || $force == 1) {
                $encoded = '%' . bin2hex($char);
            }
            elseif(($force == 0 && $x == 2) || $force == 2) {
                $encoded = '&#' . ord($char) . ';';
            }
            else {
                $encoded = $char;
            }

            return $encoded;
        }

        /**
         * Return an encode string
         * @access public
         * @static
         * @param string $str The string
         * @param boolean $force Force an encoding method ?
         * @return string The encoded string
         */
        public static function encode_string($str, $force = FALSE) {
            $encoded = '';
            $arr = str_split($str, 1);

            foreach($arr as $char) {
                if (in_array($char, ['@', ':', '.'])) {
                    $encoded .= self::encode_char($char, 2);
                }
                else {
                    $x = $force ? rand(2,3) : rand(1,3);
                    $encoded .= self::encode_char($char, $x);
                }
            }

            return $encoded;
        }

        /**
         * Encoded Mailto Link
         *
         * Create a spam-protected mailto link
         * @access public
         * @static
         * @param string The email address
         * @param string The link title
         * @param mixed Any attributes
         * @return string The encoded link
         */
        public static function encode_mailto($email, $title = NULL, $attributes = NULL) {
            $encoded = '';
            $title = !$title ? $email : $title;

            $attr = '';
            if ($attributes) {
                foreach ($attributes as $name => $value) {
                    $attr .= ' ' . $name . '="' . $value . '"';
                }
            }

            return '<a href="' . self::encode_string('mailto:', TRUE) . self::encode_string($email, TRUE) . '"' . $attr . '>' . self::encode_string($title, TRUE) . '</a>';
        }

    }
