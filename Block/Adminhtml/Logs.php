<?php

namespace Adeelq\CoreLogFileReader\Block\Adminhtml;

use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Escaper;
use Adeelq\CoreLogFileReader\Helper\Logs as LogsHelper;

class Logs extends Container
{
    /**
     * @var Escaper
     */
    public Escaper $escaper;

    /**
     * @var LogsHelper
     */
    private LogsHelper $logsHelper;

    /**
     * @param Context $context
     * @param Escaper $escaper
     * @param LogsHelper $logsHelper
     * @param array $data
     */
    public function __construct(Context $context, Escaper $escaper, LogsHelper $logsHelper, array $data = [])
    {
        parent::__construct($context, $data);
        $this->escaper = $escaper;
        $this->logsHelper = $logsHelper;
    }

    /**
     * @return LogsHelper
     */
    public function getLogHelper(): LogsHelper
    {
        return $this->logsHelper;
    }
}
