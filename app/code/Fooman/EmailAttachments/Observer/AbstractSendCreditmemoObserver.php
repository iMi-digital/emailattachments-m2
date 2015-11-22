<?php

namespace Fooman\EmailAttachments\Observer;

class AbstractSendCreditmemoObserver extends AbstractObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/creditmemo/attachpdf';

    protected $pdfRenderer;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory,
        \Fooman\EmailAttachments\Model\Api\PdfRendererInterface $pdfRenderer
    ) {
        parent::__construct($scopeConfig, $attachmentFactory);
        $this->pdfRenderer = $pdfRenderer;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        /**
         * @var $creditmemo \Magento\Sales\Api\Data\CreditmemoInterface
         */
        $creditmemo = $observer->getCreditmemo();
        if ($this->scopeConfig->getValue(
            static::XML_PATH_ATTACH_PDF,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $creditmemo->getStoreId()
        )
        ) {
            $this->attachPdf(
                $this->pdfRenderer->getPdfAsString([$creditmemo]),
                $this->pdfRenderer->getFileName(__('Credit Memo ' . $creditmemo->getIncrementId())),
                $observer
            );
        }
    }
}
