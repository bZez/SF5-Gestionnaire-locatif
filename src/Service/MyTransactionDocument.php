<?php
namespace App\Service;

use Globalis\Universign\Request\TransactionDocument;

class MyTransactionDocument extends TransactionDocument
{
    public function setContent($content)
    {
        if (!isset($this->attributes['content'])) {
            $this->attributes['content'] = $content;
        }
        return $this;
    }
    public function setPath($path)
    {
        if (!isset($this->attributes['name'])) {
            $this->attributes['name'] = basename($path);
        }
        return $this;
    }
}
