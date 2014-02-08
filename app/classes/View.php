<?php

    /**
     * Manages passing variables to templates and rendering them
     * @version 1.0
     * @author Vitaliy Orlov
     * @url: http://www.orlov.cv.ua
     **/

    class View
    {
        private $vars = array();
        private $template = null;

        protected $params = array(
          'baseDir' => './'
        );

        public function __construct($params=array())
        {
            $this->params = array_merge($this->params, $params);
        }

        public function __set($var, $val)
        {
            $this->set($var, $val);
        }

        public function __get($var)
        {
            return isset($this->vars[$var]) ? $this->vars[$var] : null;
        }

        public function set($vars, $val=null)
        {
            if (!is_array($vars)) $vars = array($vars=>$val);

            foreach ($vars as $var=>$val)
            {
                $this->vars[$var] = $val;
            }

            return $this;
        }

        public function bind($vars, &$val=null)
        {
            if (!is_array($vars)) $vars = array($vars=>&$val);

            foreach ($vars as $var=>&$val)
            {
                $this->vars[$var] = &$val;
            }
            return $this;
        }

        public function add($vars, $val=null)
        {
            if (!is_array($vars)) $vars = array($vars=>$val);

            foreach ($vars as $var=>$val)
            {
                if (!isset($this->vars[$var])) $this->vars[$var] = '';
                $this->vars[$var] .= $val;
            }

            return $this;
        }

        public function template($template)
        {
            $this->template = $template;
            return $this;
        }

        public function render($template=null, $output=FALSE)
        {
            if (is_null($template)) throw new Exception('Undefined tempalte');

            $view_filepath = $this->params['baseDir'].strtolower(trim($template)).'.php';
            if (!is_readable($view_filepath)) throw new Exception('View '.$template.' not found');

            $old_err_reporting_level = error_reporting();
            error_reporting($old_err_reporting_level & ~E_NOTICE);
            ob_start();
            extract($this->vars);
            include $view_filepath;
            $ret = ob_get_clean();
            error_reporting($old_err_reporting_level);
            if ($output) echo $ret; else return $ret;
        }

        public function __toString()
        {
             try {
                return $this->render($this->template);
             } catch (Exception $e) {
                trigger_error($e->getMessage());
             }
        }
    }