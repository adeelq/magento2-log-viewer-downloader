<?php

namespace Adeelq\CoreLogFileReader\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Logs extends Action
{
    /**
     * @inheritDoc
     */
    const ADMIN_RESOURCE = 'Adeelq_CoreLogFileReader::CoreLogFileReader';
}
