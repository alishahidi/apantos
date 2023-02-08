<?php
use PHPUnit\Framework\TestCase;
use System\Config\Config as ConfigSystem;
class ConfigTest extends TestCase
{
    /**
     * Get enviroment variable from .env file
     * @return void
     */
    public function test_it_check_success_get_enviroment_varible()
    {
        $this->assertEquals(ConfigSystem::get('DB_HOST'), '127.0.0.1');
    }

    /**
     * Get enviroment variable from config/app.php file
     * @return void
     */
    public function test_it_check_success_get_config_enviroment_varible()
    {
        $this->assertEquals(ConfigSystem::get('app.TOKEN'), 'env');
    }
}