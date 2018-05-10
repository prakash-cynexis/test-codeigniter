<?php

namespace MYClasses\Providers;

class EmailProvider implements NotifyInterface
{
    private $CI;
    private $defaultSubject = 'Password Reset.';
    private $to;
    private $html;
    private $subject;
    private $actionUrl;
    private $actionText;
    private $link;
    private $messageLines;
    protected $_openingTag = '{{';
    protected $_closingTag = '}}';

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function send()
    {
        $this->CI->load->library('encrypt');
        $this->CI->load->library('email');

        //$this->CI->email->initialize($this->emailConfig());
        $this->CI->email->set_newline("\r\n");

        $this->CI->email->from(COMPANY_EMAIL);
        $this->CI->email->reply_to(COMPANY_EMAIL, COMPANY_NAME);
        $this->CI->email->to($this->to);

        $this->CI->email->subject($this->subject);
        if ($this->html) $this->CI->email->mailtype = 'html';
        $this->CI->email->message($this->toArray()['body']);
        $emailSent = $this->CI->email->send();
        //exit($this->CI->email->print_debugger());
        if (!$emailSent) {
            log_activity('Email Not send:- ' . $this->to, 'email');
            return false;
        }
        return true;
    }

    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function html($body)
    {
        return $this->html = $body;
    }

    public function line($line)
    {
        return $this->with($line);
    }

    public function action($text, $url)
    {
        $this->actionText[] = $text;
        $this->actionUrl[] = $url;
        $this->link[] = anchor($url, $text);
        return $this;
    }

    protected function replaceTextToAction($message)
    {
        $newBody = [];
        foreach ($message['body'] as $i => $line) {
            if (!empty($this->actionText[$i])) $line = str_replace($this->_openingTag . $this->actionText[$i] . $this->_closingTag, $this->getLink()[$i], $line);
            $newBody[] = $line;
        }
        $message['body'] = $newBody;
        return $message;
    }

    private function with($line)
    {
        $this->messageLines[] = $this->formatLine($line);
        return $this;
    }

    private function toArray()
    {
        $array = [
            'to' => $this->to,
            'subject' => is_null($this->subject) ? $this->defaultSubject : $this->subject,
            'body' => !$this->getMessageLines() ? $this->getHtml() : $this->getMessageLines(),
        ];

        if ($this->getMessageLines()) $array = $this->replaceTextToAction($array);
        return $array;
    }

    protected function formatLine($line)
    {
        if (is_array($line)) {
            return implode(' ', array_map('trim', $line));
        }

        $formattedLine = trim(implode(' ', array_map('trim', preg_split('/\\r\\n|\\r|\\n/', $line))));
        return $formattedLine;
    }

    private function getHtml()
    {
        return $this->html;
    }

    private function getLink()
    {
        return $this->link;
    }

    private function getMessageLines()
    {
        return $this->messageLines;
    }

    private function emailConfig()
    {
        return $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls',
            'smtp_user' => 'prakash.cynexis@gmail.com',
            'smtp_pass' => 'asdf!@#123',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE,
        ];
    }
}