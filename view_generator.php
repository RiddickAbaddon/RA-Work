<?php
abstract class View_generator_API {
    const CONFIG = __DIR__ . '/config.php';
    
    protected function get_config_path() {
        return self::CONFIG;
    }
    private $css_list = array(
        "css/main.css"
    );
    private $js_list = array(
        "js/utils.js"
    );
    private $js_list_head = array(
        "js/jQuery.js"
    );
    private $js_vars = array();
    protected $buffered_content;
    private $title = '';

    protected function get_css_list() {
        return $this->css_list;
    }
    protected function get_js_list() {
        return $this->css_list;
    }
    protected function add_css($path) {
        array_push($this->css_list, $path);
    }
    protected function add_js($path, $head = false) {
        if($head) {
            array_push($this->js_list_head, $path);
        } else {
            array_push($this->js_list, $path);
        }
    }
    protected function add_js_var($name, $value) {
        array_push($this->js_vars, ["name" => $name, "value" => $value]);
    }
    protected function set_title($title) {
        $this->title = $title;
    }
    public function load_view($name, $title) {
        $this->title = $title;
        $path = __DIR__ . '/views/' . $name . '.php';
        $this->buffer_content($path);
        echo '
        <!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="utf-8">
            <title>' . $this->title . '</title>
            <meta name="robots" content="noindex">
            <meta name="robots" content="nofollow">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        ';

        if($this->css_list) {
            foreach($this->css_list as $css) {
                echo '
                <link rel="stylesheet" href="' . $css . '">
                ';
            }
        }
        if($this->js_vars) {
            echo '<script type="text/javascript">' . "\r\n";
            foreach($this->js_vars as $var) {
                echo 'var ' . $var["name"] . ' = ' . $var["value"] . ';' . "\r\n";
            }
            echo '</script>';
        }
        if($this->js_list_head) {
            foreach($this->js_list_head as $js) {
                echo '
                <script type="text/javascript" src="' . $js . '"></script>
                ';
            }
        }

        echo '
        </head>
        <body>
            <div id="popup-area"></div>
        ';

        echo $this->buffered_content;
        $this->buffer_content = NULL;
        if($this->js_list) {
            foreach($this->js_list as $js) {
                echo '
                <script type="text/javascript" src="' . $js . '"></script>
                ';
            }
        }

        echo '
        </body>
        ';
    }
}
class View_generator extends View_generator_API {
    private function add_controller($name) {
        if(!@include __DIR__ . '/controllers/' . $name . '.php') {
            add_log('[Error] Błąd wczytywania kontrollera: ' . $name);
            die('Błąd wczytywania kontrolera');
        }
    }
    public function buffer_content($path) {
        ob_start();
        include $path;
        $this->buffered_content = ob_get_contents();
        ob_end_clean();
    }
}
$view = new View_generator();