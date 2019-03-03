<?php
$config = require_once $this->get_config_path();
$this->project_name = $config["project_name"];
$this->footer_text = $config["footer_text"];
$this->footer2_text = $config["footer2_text"];