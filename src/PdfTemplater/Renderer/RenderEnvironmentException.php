<?php
declare(strict_types=1);

namespace PdfTemplater\Renderer;

/**
 * Class RenderEnvironmentException
 *
 * This exception is thrown if the rendering environment is not suitable for the Renderer in use.
 * Should be thrown by the constructor.
 *
 * @package PdfTemplater\Renderer
 */
class RenderEnvironmentException extends \RuntimeException
{

}