<?php

namespace MYClasses\Providers;

class EmailProvider implements NotifyInterface
{
    private $CI;
    private $defaultSubject = 'Password Reset.';
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
        $this->CI->load->library('email');
    }

    public function send(array $userInfo)
    {
        //TODO email functionality pending


        dd($this->toArray(), false);
        dd($userInfo);
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

}