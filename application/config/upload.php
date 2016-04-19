<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['upload_path'] = FCPATH.'uploads/';
$config['allowed_types'] = 'gif|jpg|png';
$config['max_size'] = 2048;//K
/*$config['min_width'] = 0;
$config['min_height'] = 0;
$config['max_width'] = 1024;
$config['max_height'] = 768;*/
$config['file_ext_tolower'] = TRUE;
$config['encrypt_name'] = TRUE;