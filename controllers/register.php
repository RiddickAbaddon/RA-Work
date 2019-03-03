<?php

$this->code = $_GET["code"];
$this->profile_id = $_GET["id"];
$this->add_js_var('code', "'" . $this->code . "'");
$this->add_js_var('profile_id', "'" . $this->profile_id . "'");