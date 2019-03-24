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
        $command    = sprintf(
            '%s -headless %s %s %s %s 2>&1',
            escapeshellcmd($this->slimerjsPath),
            $scriptPath,
            $url,
            $imageFile,
            $width
        );

        exec($command, $output, $returnCode);
        if ($returnCode !== 0) {
            throw new \Exception($returnCode);
        }

        return true;
    }
}
