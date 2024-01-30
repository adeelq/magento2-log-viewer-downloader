<?php

namespace Adeelq\CoreLogFileReader\Controller\Adminhtml\Logs;

use Adeelq\CoreLogFileReader\Controller\Adminhtml\Logs;
use Adeelq\CoreModule\Controller\Adminhtml\AbstractIndex;

class Index extends AbstractIndex
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
        return 'Log Files';
    }
}
