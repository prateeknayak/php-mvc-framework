<?php
use Lp\Framework\Core\Router as Router;

Router::map("GET", "/lp","indexController","index");
Router::map("GET","/lp/store/{id}/","indexController","index");