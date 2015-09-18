<?php
use Lp\Framework\Core\Request\Router as Router;

Router::map("GET", "/","indexController","blah");
Router::map("GET","/store/{id}/","indexController","index");



Router::map("GET","/deal/{id}/","dealController","deal");