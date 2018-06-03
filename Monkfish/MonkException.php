<?php
  /**
   * MONKException class
   *
   * Exception class for the namespace, used internally
   */
  namespace Monkfish;

  class MonkException extends \Exception {

    private $className;
    private $classLine;

    public function __construct($message, $class_name, $class_line = NULL, $display = TRUE, $code = 0, Exception $previous = NULL) {
        $this->className = $class_name;
        $this->classLine = !is_null($class_line) ? '#' . $class_line : '';

        parent::__construct($message, $code, $previous);

        if ($display) {
            $this->display();
        }
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    private function getClassName($class_name) {
        $class_parts = explode('\\', $class_name);

        return end($class_parts);
    }

    public function display() {
        $html = '<style>
            #exception {
                background: #222;
                background: linear-gradient(#222, #181818);
                border: 1px solid #0b0b0b;
                left: 50%;
                margin: 48px;
                position: absolute;
                top: 33%;
                transform: translateX(-50%) translateY(-50%);
                width: 480px;
                z-index: 4000;
            }
            #exception h1 {
                color: #fff;
                font: normal 21px/24px Arial,sans-serif;
                margin: 24px;
                padding-left: 12px;
            }
            #exception h1::before {
                color: #fedc7a;
                content: "!";
                float: left;
                font: normal 89px/96px Arial,sans-serif;
                margin-right: 24px;
                margin-top: -18px;
            }
            #exception h1 small {
                color: #fedc7a;
                display: block;
                font: normal 16px/48px Arial,sans-serif;
            }
            .exception-content {
                background: #fff;
                border-radius: 3px;
                margin: 24px;
                padding: 24px;
            }
            #exception h2 {
                color: #ababab;
                font: normal 16px/24px monospace;
            }
            #exception p {
                color: #222;
                font: normal 13px/24px Arial,sans-serif;
                margin: 0;
            }
            #exception b { color: #e8bb60; }
        </style>
        <div id="exception">
            <h1>
                ' . strtoupper($this->getClassName($this->className)) . '
                <small>An error was encountered</small>
            </h1>
            <div class="exception-content">
                <h2>[' . $this->className . '.php' . $this->classLine . ']</h2>

                <p>' . $this->message . '</p>
            </div>
        </div>';

        die($html);
    }

  }