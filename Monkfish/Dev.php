<?php
    /**
     * Dev class
     *
     * Development tools and other functions
     */
    namespace Monkfish;

    class Dev {

        /**
         * ini_set('display_errors',1);
         * ini_set('display_startup_errors',1);
         * error_reporting(-1);
         */

        /**
         * Development mode
         * @access private
         * @var boolean
         * @see __construct
         */
        private $mode = FALSE;

        /**
         * Dev class constructor
         * @access public
         * @param boolean $mode Is the developpement mode active ?
         */
        public function __construct($mode = FALSE) {
            $this->mode = $mode;
        }

        /**
         * Returns the HTML code to display grid on a page
         * @param array $options The JS options
         * @return void
         */
        public function getDevGrid($options = []) {
            if ($this->mode):
            ?>
                <script>
                    /*!
                     * Monkfish's grid dev tools v1.0
                     *
                     * @author Fabien TAVERNIER <contact@monkfish.fr>
                     */
                    var monkGrid = function(opts) {

                        var self = this;

                        /**
                        * Grid instance
                        * @var Object
                        */
                        var grid = {};

                        /**
                        * Grid default options
                        * @var Object
                        */
                        var defaults = {
                            baselinesDisplay: true,
                            baseFontSize: 10,
                            baselineGridHeight: 24,

                            guidesDisplay: true,
                            guideColor: 'rgb(255, 195, 0)',
                            guideInnerColor: 'rgba(255, 255, 255, 0.9)',
                            guideBorder: 'rgba(255, 195, 0, 0.6)',
                            guideOpacity: '0.6',

                            switchIsOpen: false,
                            switchColor: '#4db94d',
                            switchColorActive: '#e86161'
                        };

                        /**
                        * Grid options
                        * @var Object
                        */
                        var options = {};

                        /**
                        * Width of the window
                        * @var Integer
                        */
                        var windowWidth = Math.max(document.body.scrollWidth, window.innerWidth);

                        /**
                        * Height of the columns
                        * @var Integer
                        */
                        var largerHeight = Math.max(document.body.scrollHeight, window.innerHeight);

                        /**
                        * Grid tools container
                        * @var HTMLDivElement
                        */
                        var gridTools = document.createElement('div');
                        gridTools.id = 'grid_tools';

                        /**
                        * Switch div
                        * @var HTMLDivElement
                        */
                        var switchDiv = document.createElement('div');
                        switchDiv.id = 'grid_switch';
                        // switchDiv.innerHTML = '\u2261';
                        switchDiv.innerHTML = '<button class="btn-hamburger"><span>toggle menu</span></button>';

                        /**
                        * Baseline container
                        * @var HTMLDivElement
                        */
                        var baselineWrapper = document.createElement('div');
                        baselineWrapper.id = 'grid_baseline_wrapper';

                        /**
                        * Columns wrapper
                        * @var HTMLDivElement
                        */
                        var columnsWrapper = document.createElement('div');
                        columnsWrapper.classList.add('grid-columns-wrapper');

                        /**
                        * Columns container
                        * @var HTMLDivElement
                        */
                        var columnsContainer = document.createElement('div');
                        columnsContainer.classList.add('grid-columns-container');

                        /**
                        * Number of columns
                        * @var Integer
                        */
                        var nbCols = 16;

                        /**
                        * Baseline styles
                        * @var String
                        */
                        var styles = '';

                        /**
                        * Instantiates a grid object
                        * @param void
                        * @function
                        */
                        self.init = function() {
                            self.createSwitch();

                            if (options.baselinesDisplay) {
                                self.createBaselines();
                            }

                            if (options.guidesDisplay) {
                                self.createGuides();
                            }

                            styles = '\
                                html{height:100%;position:relative;}\
                                #grid_switch{background-color: '+options.switchColorActive+';bottom: 0;cursor: pointer;font-size: 0;height: 96px;position: fixed;right: 0;text-indent: -9999px;transition: background 0.3s;width: 96px;z-index: 10;}\
                                #grid_switch span {background: none;display: block;height: 8px;left: 18px;position: absolute;right: 18px;top: 44px;transition: background 0s 0.3s;}\
                                #grid_switch span::before, #grid_switch span::after {background-color: #fff;content: "";display: block;height: 8px;left: 0;position: absolute;width: 100%;transition-duration: 0.3s, 0.3s;transition-delay: 0s, 0.3s;}\
                                #grid_switch span::before {top: 0;transform: rotate(45deg);transition-property: top, transform;}\
                                #grid_switch span::after {bottom: 0;transform: rotate(-45deg);transition-property: bottom, transform;}\
                                .grid-hidden #grid_switch{background-color: '+options.switchColor+';}\
                                .grid-hidden #grid_switch span {background: #fff;}\
                                .grid-hidden #grid_switch span::before {top: -20px;transform: rotate(0deg);}\
                                .grid-hidden #grid_switch span::after {bottom: -20px;transform: rotate(0deg);}\
                                .grid-hidden #grid_switch span::before, .grid-hidden #grid_switch span::after {transition-delay: 0.3s, 0s;}\
                                .grid-hidden #grid_baseline_wrapper{opacity: 0;}\
                                .grid-hidden #grid_baseline_wrapper{display:none;}\
                                #grid_baseline_wrapper{opacity:'+options.guideOpacity+';position:absolute;left:0;top:0;z-index:8000;width:100%;height:'+largerHeight+'px;transition:opacity 0.235s ease-out;overflow-y:hidden;pointer-events: none;}\
                                .grid-baseline{border-bottom:1px dotted '+options.guideColor+';height:'+options.baselineGridHeight+'px;box-sizing:border-box;}\
                                .grid-columns-wrapper{position:fixed;top:0;bottom:0;left:0; right:0;pointer-events: none;}\
                                .grid-hidden .grid-columns-wrapper{display:none;}\
                                .grid-columns-wrapper div{height:100%;}\
                                @media all and (max-width:767px){.grid-columns-wrapper{display:none;}}\
                                @media all and (min-width:768px){.grid-columns-container{padding:0 3.33333%;}.grid-columns-container > div{border:solid #FFF;border-width:0 1px;float:left;height:100%;}.grid-columns-container > div > span{border:solid '+options.guideBorder+';border-width:0 0.75em;display:inline-block;height:100%;width:100%;}}\
                                @media screen and (min-width:1280px){.grid-columns-container{padding:0 5.55555%;}}\
                            ';

                            self.appendCSS();
                            self.addEvents();

                            document.body.appendChild(gridTools);

                            if (options.switchIsOpen) {
                                switchDiv.click();
                            }
                        };

                        /**
                        * Create the switch element
                        * @param void
                        * @function
                        */
                        self.createSwitch = function() {
                            document.body.classList.add('grid-hidden');
                            document.body.classList.add('grid-animated');
                            gridTools.appendChild(switchDiv);
                        };

                        /**
                        * Set the baseline grid
                        * @param void
                        * @function
                        */
                        self.createBaselines = function() {
                            if (!document.body.classList.contains('grid-hidden')) {
                                var baselines = Math.floor(largerHeight / parseInt(options.baselineGridHeight));

                                baselineWrapper.innerHTML = '';

                                for (var i = 0; i < baselines; i++) {
                                    var baselineDiv = document.createElement('div');
                                    baselineDiv.classList.add('grid-baseline');

                                    baselineWrapper.appendChild(baselineDiv);
                                }

                                baselineWrapper.style.height = largerHeight;
                                gridTools.appendChild(baselineWrapper);
                            }
                        };

                        /**
                        * Create the guides
                        * @param void
                        * @function
                        */
                        self.createGuides = function() {
                            windowWidth = Math.max(document.body.scrollWidth, window.innerWidth);
                            nbCols = (windowWidth > 1023) ? 16 : 4;

                            for (var i = 1; i <= nbCols; i++) {
                                var div = document.createElement('div');
                                div.classList.add('col-s-1');
                                div.classList.add('col-1');
                                div.innerHTML = '<span></span>';

                                columnsContainer.appendChild(div);
                            }

                            columnsWrapper.appendChild(columnsContainer);
                            gridTools.appendChild(columnsWrapper);
                        };

                        /**
                        * Append CSS
                        * @param void
                        * @function
                        */
                        self.appendCSS = function() {
                            if(document.createStyleSheet) {
                                document.createStyleSheet(styles);
                            }
                            else {
                                var css = document.createElement('style');
                                css.setAttribute('type','text/css');
                                css.appendChild(document.createTextNode(styles));
                                document.getElementsByTagName('head')[0].appendChild(css);
                            }
                        };

                        /**
                        * Add events to elements
                        * @param void
                        * @function
                        */
                        self.addEvents = function() {
                            window.addEventListener('resize', _onResize);
                            switchDiv.addEventListener('click', _toggleSwitch);
                        };

                        /**
                        * Callback function fired when the user clicks on the switch grid button
                        * @param Object event
                        * @see addEvents
                        */
                        var _toggleSwitch = function(event) {
                            if (document.body.classList.contains('grid-hidden')) {
                                document.body.classList.remove('grid-hidden');

                                if (options.baselinesDisplay) {
                                    self.createBaselines();
                                }

                                setTimeout(function () { document.body.classList.remove('grid-animated'); }, 20);
                            }
                            else {
                                document.body.classList.add('grid-animated');
                                setTimeout(function () { document.body.classList.add('grid-hidden'); }, 300);
                            }
                        };

                        /**
                        * Callback function fired when the user clicks on the switch grid button
                        * @param Object event
                        * @see addEvents
                        */
                        var _onResize = function(event) {
                            while (columnsContainer.firstChild) {
                                columnsContainer.removeChild(columnsContainer.firstChild);
                            }

                            if (options.guidesDisplay) {
                                self.createGuides();
                            }
                        };


                        /**
                         * Merge javaScript objects
                         * @param Boolean (true) for a deep merge or the first object to merge
                         * @param Object The (n) objects to merge
                         * @link https://gomakethings.com/vanilla-javascript-version-of-jquery-extend/
                         */
                        self.extendOpts = function () {
                            // Variables
                            var extended = {},
                                deep = false,
                                i = 0,
                                length = arguments.length;

                            // Check if a deep merge
                            if (Object.prototype.toString.call(arguments[0]) === '[object Boolean]') {
                                deep = arguments[0];
                                i++;
                            }

                            // Merge the object into the extended object
                            var merge = function (obj) {
                                for (var prop in obj) {
                                    if (Object.prototype.hasOwnProperty.call(obj, prop)) {
                                        // If deep merge and property is an object, merge properties
                                        if (deep && Object.prototype.toString.call(obj[prop]) === '[object Object]') {
                                            extended[prop] = self.extendOpts(true, extended[prop], obj[prop]);
                                        }
                                        else {
                                            extended[prop] = obj[prop];
                                        }
                                    }
                                }
                            };

                            // Loop through each object and conduct a merge
                            for ( ; i < length; i++) {
                                var obj = arguments[i];
                                merge(obj);
                            }

                            return extended;
                        };

                        options = self.extendOpts(true, defaults, opts);
                        self.init();

                        return self;

                    };

                    var monkGrid = new monkGrid(<?= json_encode($options) ?>);
                </script>
            <?php
            endif;
        }

        /**
         * Debug tool to print a variable
         * @param mixed $var The variable to print
         * @param int $line The line where this function is call
         * @param path $file The file where this function is call
         * @param bool $return Return or print the variable
         * @return HTML
         */
        public function monkDump($var, $line = '?', $file ='unknown', $return = FALSE) {
            $styles = [
                'div' => [
                    'background' => '#222',
                    'background' => 'linear-gradient(#222, #181818)',
                    'border' => '1px solid #0b0b0b',
                    'color' =>  '#222',
                    'margin' => '24px',
                    'padding' =>  '24px',
                    'width' => '960px',
                ],
                'b' => [
                    'display' => 'inline-block',
                    'color' => '#fff',
                    'font-size' => '16px',
                    'font-weight' => '400',
                    'line-height' => '24px',
                    'margin-bottom' => '12px',
                    'text-indent' => '12px',
                    'width' => '100%',
                ],
                'span' => [
                    'color' => '#fedc7a',
                ],
                'pre' => [
                    'background' => '#fff',
                    'border-radius' => '3px',
                    'color' =>  '#222',
                    'padding' =>  '24px',
                    'width' => '912px',
                    'white-space' => 'pre-wrap',
                ],
            ];

            $x = '<div style="' . $this->buildCSS($styles['div']) . '">';
            $x .= '<b style="' . $this->buildCSS($styles['b']) . '">Line <span style="' . $this->buildCSS($styles['span']) . '">' . $line . '</span> - file: <span style="' . $this->buildCSS($styles['span']) . '">' . $file . '</span></b><br />';
            $x .= '<pre style="' . $this->buildCSS($styles['pre']) . '">';
            $x .= print_r($var, 1);
            $x .= '</pre>';
            $x .= '</div>';

            if($return) {
                return $x;
            }
            else {
                echo $x;
            }
        }

        /**
         * Convert an array on style to a CSS string
         * @param array $style The attributes and values
         * @return string The CSS formated style
         */
        private function buildCSS($style) {
            $css = '';

            foreach ($style as $attr => $value) {
                $css .= $attr . ': ' . $value . '; ';
            }

            return $css;
        }

    }