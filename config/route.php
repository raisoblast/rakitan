<?php
return [
    ['GET', '/', 'Home#index', 'home'],
    ['GET|POST', '/[a:controller]/[a:action]?', '', 'default-route'],
    ['GET|POST', '/[a:module]/[a:controller]/[a:action]?', '', 'default-module-route'],
];
