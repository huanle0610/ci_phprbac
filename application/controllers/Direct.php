<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Version 17-09-2010

/**
 * Based on http://www.sencha.com/forum/showthread.php?79211-Ext.Direct-for-CodeIgniter/
 */

/**
 * Direct controller
 */
class Direct extends CI_Controller
{
    /**
     * Constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->add_package_path(APPPATH . 'third_party/ext/');


        $this->load->config('extjs');

        $this->load->library('DirectBase');

        $this->load->driver('cache', array('adapter' => 'file'));

        $this->load->library('ext_direct_api');
        $this->load->library('ext_direct_cache_provider',
            array('filePath' => $this->config->item('direct_cache_path') . '/' . $this->config->item('direct_cache_name')));
    }

    /**
     * Index
     * @access public
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Ext Direct API
     * @param bool $output default TRUE
     */
    public function api($output = TRUE)
    {
        $this->ext_direct_api->setRouterUrl(site_url('direct/router'));
        $this->ext_direct_api->setCacheProvider($this->ext_direct_cache_provider);
        //	$this->ext_direct_api->setNamespace('Ext.app');
        $this->ext_direct_api->setDescriptor('Ext.app.REMOTING_API');
        $this->ext_direct_api->setDefaults(array(
            'autoInclude' => TRUE,
            'basePath' => 'ext/direct'
        ));
        // prefix is for classname not also filename
        $this->ext_direct_api->add(
            array(
                'Echo' => array('prefix' => 'Class_'),
                'Exception' => array('prefix' => 'Class_'),
                'Time',
                'File',

                'Actor',
                'Rbac',
                'Database',
                'Navigation',
                'Kaleidoscope',
                'ExtProfile',

                'TestAction',
                'Profile'
            )
        );

        if ($output) $this->ext_direct_api->output();
    }

    public function router()
    {
        $enable_profile = $this->config->item('enable_route_profile');
        if( $enable_profile )
        {
            $sections = array(
                'benchmarks' => true,
                'config' => false,
                'controller_info' => true,
                'get' => true,
                'http_headers' => false,
                'memory_usage' => true,
                'post' => true,
                'queries' => true,
                'payload' => true,
                'uri_string' => false,
                'session_data' => false
            );
            $this->output->set_profiler_sections($sections);
            $this->output->enable_profiler(TRUE);
        }

        if( $enable_profile )
        {
            $this->benchmark->mark('ext_route_start');
            $this->benchmark->mark('ext_direct_get_state_start');
        }

        $state = $this->getExtDirectStateCache();
        if (!$state)
        {
            $this->api(FALSE);
            $this->setExtDirectStateCache($this->ext_direct_api->getState());
        }
        else
        {
            $this->ext_direct_api->setState($state);
        }

        if( $enable_profile )
        {
            $this->benchmark->mark('ext_direct_get_state_end');
        }

        $this->load->library('ext_direct_router',
            array('api' => $this->ext_direct_api));

        $this->benchmark->mark('ext_direct_route_process_start');
        $this->ext_direct_router->dispatch();
        $this->ext_direct_router->getResponse(TRUE);

        if( $enable_profile )
        {
            $this->benchmark->mark('ext_direct_route_process_end');
            $this->benchmark->mark('ext_route_end');
        }
    }

    protected function getExtDirectStateCache()
    {
        return $this->cache->file->get($this->config->item('direct_state_cache'));
    }

    protected function setExtDirectStateCache($api_state)
    {
        $this->cache->file->save(
            $this->config->item('direct_state_cache'),
            $api_state,
            86400
        );
    }
}

/* End of file Direct.php */
/* Location: ./application/controllers/Direct.php */