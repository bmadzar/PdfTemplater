<?php
declare(strict_types=1);


namespace PdfTemplater\Renderer\Tcpdf;

/**
 * Class Tcpdf
 *
 * Sets/resets some TCPDF default values to more useful ones.
 *
 * @package PdfTemplater\Renderer\Tcpdf
 */
class Tcpdf extends \TCPDF
{
    public function __construct()
    {
        parent::__construct('P', 'pt', [0.0, 0.0], true, 'UTF-8', false, false);

        $this->setCellMargins(0.0, 0.0, 0.0, 0.0);
        $this->setCellPaddings(0.0, 0.0, 0.0, 0.0);
        $this->SetAutoPageBreak(false);
        $this->SetMargins(0.0, 0.0, 0.0, true);
        $this->SetCompression(true);

        $this->tcpdflink = false;
    }
}