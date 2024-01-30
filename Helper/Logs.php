<?php

namespace Adeelq\CoreLogFileReader\Helper;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Intl\DateTimeFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Throwable;

class Logs
{
    const LOG_FILE = 'log';
    const FILE_PARAM = 'file';

    /**
     * @var File
     */
    private File $fileIo;

    /**
     * @var Timezone
     */
    private Timezone $timezoneConverter;

    /**
     * @var DateTimeFactory
     */
    private DateTimeFactory $dateTimeFactory;

    /**
     * @var UrlInterface
     */
    private UrlInterface $backendUrl;

    /**
     * @var Filesystem\Directory\ReadInterface
     */
    private Filesystem\Directory\ReadInterface $varDir;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @param UrlInterface $backendUrl
     * @param File $fileIo
     * @param Filesystem $filesystem
     * @param Timezone $timezoneConverter
     * @param DateTimeFactory $dateTimeFactory
     * @param RequestInterface $request
     */
    public function __construct(
        UrlInterface $backendUrl,
        File $fileIo,
        Filesystem $filesystem,
        Timezone $timezoneConverter,
        DateTimeFactory $dateTimeFactory,
        RequestInterface $request
    ) {
        $this->backendUrl = $backendUrl;
        $this->fileIo = $fileIo;
        $this->timezoneConverter = $timezoneConverter;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->request = $request;
        $this->varDir = $filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
    }

    /**
     * @return array
     */
    public function getLogFiles(): array
    {
        $logFiles = [];
        if ($this->varDir->isDirectory(self::LOG_FILE)) {
            foreach ($this->varDir->read(self::LOG_FILE) as $path) {
                $compiledFile = $this->getCompiledFileInfo($path);
                if (empty($compiledFile)) {
                    continue;
                }
                $logFiles[] = $compiledFile;
            }
        }
        return $logFiles;
    }

    /**
     * @return string
     */
    public function getLogFileContent(): string
    {
        try {
            $filePath = self::LOG_FILE . DIRECTORY_SEPARATOR . $this->request->getParam(self::FILE_PARAM);
            if (! $this->varDir->isFile($filePath)) {
                return __('Log file does not exist');
            }
            return $this->varDir->readFile($filePath);
        } catch (Throwable) {
            return __('Unable to read file');
        }
    }

    /**
     * @param string $path
     *
     * @return array
     */
    private function getCompiledFileInfo(string $path): array
    {
        $pathInfo = $this->fileIo->getPathInfo($path);
        if ($pathInfo['extension'] === 'log') {
            $stats = $this->varDir->stat($path);
            return array_merge(
                [
                    'base_name' => $pathInfo['basename'],
                    'file_size' => round($stats['size'] / 1024 / 1024, 2) . ' MB',
                    'action_view' => $this->backendUrl->getUrl(
                        'adeelq_logs/logs/content',
                        [self::FILE_PARAM => $pathInfo['basename']]
                    ),
                    'action_download' => $this->backendUrl->getUrl(
                        'adeelq_logs/logs/download',
                        [self::FILE_PARAM => $pathInfo['basename']]
                    )
                ],
                $this->convertTimestampsToDatetime(
                    [
                        'last_accessed' => $stats['atime'],
                        'last_modified' => $stats['mtime']
                    ]
                )
            );
        }
        return [];
    }

    /**
     * @param array $timestamps
     *
     * @return array
     */
    private function convertTimestampsToDatetime(array $timestamps): array
    {
        try {
            $configTimeZone = $this->timezoneConverter->getConfigTimezone();
            $storeDateTime = $this->dateTimeFactory->create('now', new \DateTimeZone($configTimeZone));
            foreach ($timestamps as &$timestamp) {
                $timestamp = $storeDateTime->setTimestamp($timestamp)->format('F d Y H:i:s');
            }
            return $timestamps;
        } catch (Throwable) {
            return $timestamps;
        }
    }
}
