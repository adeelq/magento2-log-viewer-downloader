<?php

namespace Adeelq\CoreLogFileReader\Controller\Adminhtml\Logs;

use Adeelq\CoreLogFileReader\Controller\Adminhtml\Logs;
use Adeelq\CoreLogFileReader\Helper\Logs as LogsHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Exception;

class Download extends Action
{
    const ADMIN_RESOURCE = Logs::ADMIN_RESOURCE;

    /**
     * @var FileFactory
     */
    protected FileFactory $fileFactory;

    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     */
    public function __construct(Context $context, FileFactory $fileFactory)
    {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
    }

    /**
     * @return ResultInterface|ResponseInterface
     *
     * @throws Exception
     */
    public function execute(): ResultInterface|ResponseInterface
    {
        $file = $this->getRequest()->getParam(LogsHelper::FILE_PARAM);
        return $this->fileFactory->create(
            $file,
            [
                'type' => 'filename',
                'value' => LogsHelper::LOG_FILE . DIRECTORY_SEPARATOR . $file
            ],
            DirectoryList::VAR_DIR
        );
    }
}
