<?php

namespace MYClasses\Providers;

class EmailTemplateProvider
{
    private $path = APPPATH . '/My_Classes/Resources/EmailTemplate/';
    protected $_openingTag = '{{';
    protected $_closingTag = '}}';
    protected $_emailValues;
    protected $_emailTemplate;

    /**
     * Email Template Parser Class.
     * @param string $emailTemplate HTML template string OR File path to a Email Template file.
     */
    public function __construct($emailTemplate)
    {
        $this->_setTemplate($this->path . $emailTemplate);
    }

    /**
     * Set Template File or String.
     * @param string $emailTemplate HTML template string OR File path to a Email Template file.
     */
    protected function _setTemplate($emailTemplate)
    {
        $this->_emailTemplate = false;
        // Template HTML is stored in a File
        if (file_exists($emailTemplate)) {
            $this->_emailTemplate = file_get_contents($emailTemplate);
            // Template HTML is stored in-line in the $emailTemplate property!
        }
    }

    /**
     * Set Variable name and values with an array.
     * @param array $array Array of key=>values.
     */
    public function setData(array $array)
    {
        foreach ($array as $key => $value) {
            $this->_emailValues[$key] = $value;
        }
    }

    /**
     * Returns the Parsed Email Template.
     * @return string HTML with any matching variables {{varName}} replaced with there values.
     */
    public function output()
    {
        $html = $this->_emailTemplate;
        if (!$html) {
            log_activity('Invalid Email Template. $templateName must be a FilePath.', 'Email template');
            return false;
        }

        foreach ($this->_emailValues as $key => $value) {
            if (isset($value) && $value != '') {
                $html = str_replace($this->_openingTag . $key . $this->_closingTag, $value, $html);
            }
        }
        return $html;
    }
}