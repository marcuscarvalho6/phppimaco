<?php
namespace Proner\PhpPimaco;

use Proner\PhpPimaco\Tags\P;

class Tag
{
    private $content;

    private $width;
    private $height;
    private $border;
    private $ln;
    private $size;
    private $padding;
    private $align;

    function __construct($content = null)
    {
        $this->p = new \ArrayObject();

        if( $content !== null ){
            $p = new P($content);
            $this->p->append($p);
        }
    }

    public function loadConfig($template, $path)
    {
        $json = file_get_contents($path . $template);
        $std = json_decode($json);

        $this->width = $std->tag->width;
        $this->height = $std->tag->height;

        if( empty($this->border) ){
            $this->border = $std->tag->border;
        }

        if( empty($this->padding) ){
            $this->padding = 0;
        }

        $this->ln = $std->tag->ln;
        $this->align = $std->tag->align;
    }

    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function setPadding($padding)
    {
        $this->padding = $padding;
        return $this;
    }

    public function setBorder($border)
    {
        $this->border = $border;
        return $this;
    }

    public function addP(P $p)
    {
        $this->p->append($p);
        return $p;
    }

    public function p($content)
    {
        $p = new P($content);
        $this->p->append($p);
        return $p;
    }

    private function getP()
    {
        return $this->p->getArrayCopy();
    }

    public function render($side = null)
    {
        $this->content = "";

        if( !empty($side) ){
            $style[] = "float: {$side}";
        }
        if( !empty($this->width) ){
            $style[] = "width: {$this->width}mm";
        }
        if( !empty($this->height) ){
            $style[] = "height: {$this->height}mm";
        }
        if( !empty($this->border) ){
            $style[] = "border: {$this->border}mm solid black";
        }
        if( !empty($this->size) ){
            $style[] = "font-size: {$this->size}mm";
        }

        $ps = $this->getP();
        foreach($ps as $p){
            $this->content .= $p->render();
        }

        if( !empty($style) ){
            $this->content = "<div style='".implode(";",$style).";'><div style='padding: {$this->padding}mm;'>{$this->content}</div></div>";
        }else{
            $this->content = "<div><div style='padding: {$this->padding}mm;'>{$this->content}</div></div>";
        }

        return $this->content;
    }
}