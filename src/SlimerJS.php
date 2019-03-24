<?php
namespace Headzoo\SlimerCapture;

/**
 * Class SlimerJS
 */
class SlimerJS
{
    /**
     * @var string
     */
    protected $slimerjsPath;

    /**
     * @var string
     */
    protected $firefoxPath;

    /**
     * @var string
     */
    protected $lastCommand;

    /**
     * @var array
     */
    protected $lastOutput;

    /**
     * @var int
     */
    protected $lastReturnCode;

    /**
     * Constructor
     *
     * @param string $slimerjsPath
     * @param string $firefoxPath
     */
    public function __construct($slimerjsPath = 'slimerjs', $firefoxPath = '/usr/bin/firefox')
    {
        $this->setSlimerJSPath($slimerjsPath);
        $this->setFirefoxPath($firefoxPath);
    }

    /**
     * @param string $slimerjsPath
     *
     * @return SlimerJS
     */
    public function setSlimerJSPath($slimerjsPath)
    {
        $this->slimerjsPath = $slimerjsPath;
        return $this;
    }

    /**
     * @param string $firefoxPath
     *
     * @return $this
     */
    public function setFirefoxPath($firefoxPath)
    {
        $this->firefoxPath = $firefoxPath;
        return $this;
    }

    /**
     * @param string $url
     * @param string $imageFile
     * @param int $width
     *
     * @return bool
     * @throws \Exception
     */
    public function capture($url, $imageFile, $width = 1024)
    {
        $url        = escapeshellarg($url);
        $width      = escapeshellarg($width);
        $imageFile  = escapeshellarg($imageFile);
        $scriptPath = escapeshellarg(realpath(__DIR__ . '/commands/capture.js'));
        $this->lastCommand = sprintf(
            'SLIMERJSLAUNCHER=%s %s -headless %s %s %s %s 2>&1',
            $this->firefoxPath,
            escapeshellcmd($this->slimerjsPath),
            $scriptPath,
            $url,
            $imageFile,
            $width
        );

        $this->lastOutput     = [];
        $this->lastReturnCode = 0;
        exec($this->lastCommand, $this->lastOutput, $this->lastReturnCode);
        if ($this->lastReturnCode !== 0) {
            throw new \Exception($this->lastReturnCode);
        }

        return true;
    }

    /**
     * @return string
     */
    public function getLastCommand()
    {
        return $this->lastCommand;
    }

    /**
     * @return array
     */
    public function getLastOutput()
    {
        return $this->lastOutput;
    }

    /**
     * @return int
     */
    public function getLastReturnCode()
    {
        return $this->lastReturnCode;
    }
}
