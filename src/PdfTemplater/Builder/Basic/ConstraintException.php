<?php
declare(strict_types=1);

namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Builder\BuildException;

/**
 * Class ConstraintException
 *
 * This exception should be thrown if an impossible-to-resolve constraint is encountered.
 *
 * @package PdfTemplater\Builder\Basic
 */
class ConstraintException extends BuildException
{

}