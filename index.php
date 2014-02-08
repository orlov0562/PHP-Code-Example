<?php

    /**
     * Code Example Application
     * @version 1.0
     * @author Vitaliy Orlov
     * @url: http://www.orlov.cv.ua
     **/


    $appConf = array(
        'classes'=>array(
            'baseDir'=>dirname(__FILE__).'/app/classes/',
        ),
        'views'=>array(
            'baseDir'=>dirname(__FILE__).'/app/views/',
        ),
    );

    spl_autoload_register(function($class) use ($appConf){

        $classPath = $appConf['classes']['baseDir']
                    .$class.'.php';

        if (is_readable($classPath)) include($classPath);
        else throw new Exception('Class '.$class.' not found');

    });

    $_v = function() use ($appConf) { return new View($appConf['views']); };

    $view = $_v()->template('base/index')
            ->set('header', $_v()->template('base/header'))
            ->set('body', $_v()->template('base/body'))
            ->set('footer', $_v()->template('base/footer'))
    ;

    $view->body->content = $_v()->template('example')
                           ->set('app_source', highlight_file(__FILE__, TRUE))
                           ->set('view_class_source', highlight_file(
                                    $appConf['classes']['baseDir'].get_class($view).'.php',
                                    TRUE
                           ))
    ;

    echo $view;

