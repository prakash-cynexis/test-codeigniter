<?php

namespace MYClasses\Providers;

class EmailProvider
{
    private $CI;
    private $to;
    private $html;
    private $subject;
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
            log_activity($this->to, 'email not send');
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
        $this->messageLines[] = $line;
        return $this;
    }

    private function toArray()
    {
        $array = [
            'to' => $this->to,
            'subject' => $this->subject,
            'body' => $this->messageLines ? $this->messageLines : $this->html,
        ];
        return $array;
    }

    private function emailConfig()
    {
        return $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls',
            'smtp_user' => 'user_name',
            'smtp_pass' => 'password',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE,
        ];
    }
}