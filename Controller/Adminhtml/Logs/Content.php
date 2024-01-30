<?php

namespace Adeelq\CoreLogFileReader\Controller\Adminhtml\Logs;

use Adeelq\CoreLogFileReader\Controller\Adminhtml\Logs;
use Adeelq\CoreModule\Controller\Adminhtml\AbstractIndex;
use Adeelq\CoreLogFileReader\Helper\Logs as LogsHelper;

class Content extends AbstractIndex
{
    /**
     * @inheritDoc
     */
    const ADMIN_RESOURCE = Logs::ADMIN_RESOURCE;

    /**
     * @inheritDoc
     */
    protected function getLabelTitle(): string
    {
        return sprintf('Log File Viewer - %s', $this->getRequest()->getParam(LogsHelper::FILE_PARAM));
    }
}
