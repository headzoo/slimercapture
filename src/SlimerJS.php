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
     * @var
     */
    protected $profilePath;

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
     * @param string $profilePath
     */
    public function __construct($slimerjsPath = 'slimerjs', $firefoxPath = '/usr/bin/firefox', $profilePath = '/tmp')
    {
        $this->setSlimerJSPath($slimerjsPath);
        $this->setFirefoxPath($firefoxPath);
        $this->setProfilePath($profilePath);
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
     * @param string $profilePath
     *
     * @return $this
     */
    public function setProfilePath($profilePath)
    {
        $this->profilePath = $profilePath;
        return $this;
    }

    /**
     * @param string $url
     * @param string $imageFile
     * @param int $width
     *
     * @return array
     * @throws \Exception
     */
    public function capture($url, $imageFile, $width = 1024)
    {
        $scriptPath = realpath(__DIR__ . '/commands/capture.js');

        return $this->exec(sprintf(
            '%s %s --url=%s --image=%s --width=%s 2>&1',
            $this->getBaseCommand(),
            escapeshellarg($scriptPath),
            escapeshellarg($url),
            escapeshellarg($imageFile),
            escapeshellarg($width)
        ));
    }

    /**
     * @param string $url
     * @param string $imageFile
     * @param int $width
     *
     * @return array
     * @throws \Exception
     */
    public function screenshot($url, $imageFile, $width = 1024)
    {
        $scriptPath = realpath(__DIR__ . '/commands/screenshot.js');

        return $this->exec(sprintf(
            '%s %s --url=%s --image=%s --width=%s 2>&1',
            $this->getBaseCommand(),
            escapeshellarg($scriptPath),
            escapeshellarg($url),
            escapeshellarg($imageFile),
            escapeshellarg($width)
        ));
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

    /**
     * @param string $cmd
     *
     * @return array
     * @throws \Exception
     */
    protected function exec($cmd)
    {
        $this->lastCommand    = $cmd;
        $this->lastOutput     = [];
        $this->lastReturnCode = 0;

        exec($this->lastCommand, $this->lastOutput, $this->lastReturnCode);
        if ($this->lastReturnCode !== 0) {
            throw new \Exception($this->lastCommand . "\n" . join("\n", $this->lastOutput));
        }

        return $this->lastOutput;
    }

    /**
     * @return string
     */
    protected function getBaseCommand()
    {
        return sprintf(
            'SLIMERJSLAUNCHER=%s HOME=%s %s -headless',
            escapeshellarg($this->firefoxPath),
            escapeshellarg($this->profilePath),
            escapeshellcmd($this->slimerjsPath)
        );
    }
}
