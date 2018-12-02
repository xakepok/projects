<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewPayment extends HtmlView {
    protected $item, $form, $script, $id;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');

        $this->addToolbar();
        $this->setDocument();
        $this->__setTitle();

        parent::display($tpl);
    }

    protected function addToolbar() {
	    //JToolBarHelper::apply('payment.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('payment.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('payment.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }

    private function __setTitle()
    {
        if ($this->item->id != null)
        {
            $info = ProjectsHelper::getPaymentAdvInfo($this->item->scoreID);
            $exponent = ProjectsHelper::getExpTitle($info->title_ru_short, $info->title_ru_full, $info->title_en);
            $title = JText::sprintf('COM_PROJECTS_TITLE_PAYMENT_FOR_SCORE', $info->score, $info->contract, $exponent);
        }
        else
        {
            $title = JText::sprintf('COM_PROJECTS_TITLE_NEW_PAYMENT');
        }
        JToolbarHelper::title($title, '');
    }
}