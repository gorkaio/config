<?php

namespace Tests\Gorka\Config;

use Gorka\Config\Config;
use Gorka\Config\Exception\ConfigNotFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use Symfony\Component\Yaml\Yaml;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var VfsStreamFile
     */
    private $configFile;

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    protected function setUp()
    {
        $this->root = vfsStream::setup('tests');
    }

    protected function tearDown()
    {
        $this->root = null;
    }

    /**
     * method: constructor
     * when : called with valid config file
     * should : load config
     **/
    public function testConstructorCalledWithValidConfigFileLoadConfig()
    {
        $rawConfig = [
            'mongo' => [
                'default' => [
                    'user' => 'username',
                    'password' => 'p4ssw0rd'
                ],
                'numbers' => [1,1,2,3,5,8]
            ],
            'title' => 'Some funny title'
        ];
        $configFile = $this->buildConfigFile($rawConfig);
        $sut = new Config($configFile->url());
        $this->assertEquals($rawConfig, $sut->getAll());
    }

    /**
     * method: constructor
     * when : called with nonexisting file
     * should : throw exception
     * @expectedException \Gorka\Config\Exception\ConfigNotFoundException
     **/
    public function testConstructorCalledWithNonexistingFileThrowException()
    {
        new Config('none.yml');
    }

    /**
     * method: constructor
     * when : called with invalid config file
     * should : throw exception
     * @expectedException \Gorka\Config\Exception\InvalidConfigException
     **/
    public function testConstructorCalledWithInvalidConfigFileThrowException()
    {
        $configFile = vfsStream::newFile('config.yml')->setContent('kjhkjh')->at($this->root);
        $sut = new Config($configFile->url());
    }

    /**
     * method: get
     * when : called with existing config param
     * should : return such param
     **/
    public function testGetCalledWithExistingConfigParamReturnSuchParam()
    {
        $rawConfig = [
            'a' => 5,
            'b' => [
                'c' => 23
            ]
        ];
        $sut = new Config($this->buildConfigFile($rawConfig)->url());
        $this->assertEquals(23, $sut->get('b.c'));
    }

    /**
     * method: get
     * when : called with non existing param
     * should : return default value if given or null otherwise
     **/
    public function testGetCalledWithNonExistingParamReturnDefaultValueIfGivenOrNullOtherwise()
    {
        $rawConfig = [
            'a' => 5,
            'b' => [
                'c' => 23
            ]
        ];
        $sut = new Config($this->buildConfigFile($rawConfig)->url());
        $this->assertEquals(42, $sut->get('d.e', 42));
        $this->assertEquals(null, $sut->get('d.e'));
    }

    /**
     * method: has
     * when : called
     * should : return whether given param exists
     **/
    public function testHasCalledWithExistingParamReturnTrue()
    {
        $rawConfig = [
            'a' => 5,
            'b' => [
                'c' => 23
            ]
        ];
        $sut = new Config($this->buildConfigFile($rawConfig)->url());
        $this->assertTrue($sut->has('b.c'));
        $this->assertFalse($sut->has('d'));
    }

    /**
     * @param $rawConfig
     * @return \org\bovigo\vfs\vfsStreamContent
     */
    private function buildConfigFile(array $rawConfig)
    {
        $yamlConfig = Yaml::dump($rawConfig);
        $configFile = vfsStream::newFile('config.yml')->setContent($yamlConfig)->at($this->root);
        return $configFile;
    }
}
