<?php

namespace MYClasses\Providers;

trait Layouts
{
    private $_title;
    private $_layout;
    private $_layout_folder;
    private $_is_front_layout = 'layout/web';
    private $_is_backend_layout = 'layout/backend';

    final public function adminLayout($title = null)
    {
        $this->set_layout('backend');
        if ($title) $this->setTitle($title);
        return $this;
    }

    final public function webLayout($title = null)
    {
        $this->set_layout('web');
        if ($title) $this->setTitle($title);
        return $this;
    }

    final public function content(array $contents)
    {
        $this->CI_GetInstance();
        foreach ($contents as $key => $content) {
            $this->CI->data[$key] = $content;
        }
        return $this;
    }

    final public function setJS($files)
    {
        $this->CI_GetInstance();
        if (!is_array($files) && is_string($files)) $files = [$files];
        foreach ($files as $index => $file) {
            $file = assetUrl($this->_layout_folder . "/js/" . $file);
            $this->CI->java_script[] = "<script type=\"text/javascript\" src=\"$file\"></script>";
        }
        return $this;
    }

    final public function setCSS($files)
    {
        $this->CI_GetInstance();
        if (!is_array($files) && is_string($files)) $files = [$files];
        foreach ($files as $index => $file) {
            $file = assetUrl($this->_layout_folder . "/css/" . $file);
            $this->CI->style_sheet[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$file\">";
        }
        return $this;
    }

    final public function setMeta(array $data)
    {
        $this->CI_GetInstance();
        foreach ($data as $index => $item) {
            $this->CI->meta_data[] = "<meta name=\"$index\" content=\"$item\">";
        }
        return $this;
    }

    final public function publish($page)
    {
        $this->CI_GetInstance();
        $this->CI->data['current_user'] = getCurrentUser();
        $this->CI->data['title'] = (!empty($this->_title)) ? $this->_title : variableToStr($page);
        $pageLoad = $this->CI->load->view('layout/' . $this->_layout_folder . '/pages/' . $page, $this->CI->data, true);
        $this->loadView($pageLoad);
    }

    private function loadView($content)
    {
        $this->CI_GetInstance();
        $view_data = ['content' => $content];
        $this->CI->load->view($this->_layout . '/layout.php', $view_data);
    }

    private function setTitle($title)
    {
        $this->_title = $title;
    }

    private function set_layout($layout)
    {
        $this->_layout_folder = $layout;
        if (strtolower($layout) === 'web') $layout = $this->_is_front_layout;
        if (strtolower($layout) === 'backend') $layout = $this->_is_backend_layout;
        $this->_layout = $layout;
    }
}