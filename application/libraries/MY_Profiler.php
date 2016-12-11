<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Profiler extends CI_Profiler
{
    protected $_available_sections = array(
        'benchmarks',
        'get',
        'memory_usage',
        'post',
        'uri_string',
        'controller_info',
        'queries',
        'http_headers',
        'session_data',
        'config',
        'payload'
    );
    protected $ext_action;
    protected $ext_method;

    public function __construct()
    {
        parent::__construct();
        log_message('debug', __CLASS__ . " Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Compile payload Data
     *
     * @return    string
     */
    protected function _compile_payload()
    {
        $output = "\n\n"
            . '<fieldset id="ci_profiler_get" style="border:1px solid #cd6e00;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
            . "\n"
            . '<legend style="color:#cd6e00;">&nbsp;&nbsp;' . $this->CI->lang->line('profiler_payload_data') . "&nbsp;&nbsp;</legend>\n";
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, 1);
        if (!$data) {
            $output .= '<div style="color:#cd6e00;font-weight:normal;padding:4px 0 4px 0;">' . $this->CI->lang->line('profiler_no_payload') . '</div>';
        } else {
            $output .= "\n\n<table style=\"width:100%;border:none;\">\n";

            if(isset($data['action']))
            {
                $this->ext_action = $data['action'];
            }

            if(isset($data['method']))
            {
                $this->ext_method = $data['method'];
            }

            foreach ($data as $key => $val) {
                is_int($key) OR $key = "'" . htmlspecialchars($key, ENT_QUOTES, config_item('charset')) . "'";
                $val = (is_array($val) OR is_object($val))
                    ? '<pre>' . htmlspecialchars(var_export($val, TRUE), ENT_QUOTES, config_item('charset'))
                    : htmlspecialchars($val, ENT_QUOTES, config_item('charset'));

                $output .= '<tr><td style="width:50%;color:#000;background-color:#ddd;padding:5px;">&#36;payload['
                    . $key . ']&nbsp;&nbsp; </td><td style="width:50%;padding:5px;color:#cd6e00;font-weight:normal;background-color:#ddd;">'
                    . $val . "</td></tr>\n";
            }

            $output .= "</table>\n";
        }

        return $output . '</fieldset>';
    }

    // --------------------------------------------------------------------

    /**
     * write profile data to database for analysis
     * @param $output
     */
    public function logProfileToDB($output)
    {
        if($this->ext_action == 'ExtProfile')
        {
            return;
        }

        $totalTime = $this->CI->benchmark->elapsed_time('total_execution_time_start',
            'total_execution_time_end');
        $route_class = $this->CI->router->class;
        $route_method = $this->CI->router->method;

        $data = array(
            'class' => $route_class,
            'method' => $route_method,
            'total_time' => $totalTime,
            'memory' => memory_get_usage(),
            'ext_action' => $this->ext_action,
            'ext_method' => $this->ext_method,
            'html' => $output,
            'ip' => $this->CI->input->ip_address(),
            'create_at' => date('Y-m-d H:i:s')
        );
        if(!isset($this->CI->db))
        {
            return;
        }
        $this->CI->db->set($data)->insert('ci_profile');
    }
    // --------------------------------------------------------------------

    /**
     * Run the Profiler
     *
     * @return    string
     */
    public function run()
    {
        $output = parent::run();
        $this->logProfileToDB($output);

        if ($this->CI->input->is_ajax_request()) {
            return '';
        }

        return $output;
    }

}