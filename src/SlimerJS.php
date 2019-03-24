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
     */
    public function __construct($slimerjsPath = 'slimerjs')
    {
        $this->setSlimerJSPath($slimerjsPath);
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
            'SLIMERJSLAUNCHER=/var/www/slimerjs-1.0.0/firefox/firefox %s -headless %s %s %s %s 2>&1',
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
