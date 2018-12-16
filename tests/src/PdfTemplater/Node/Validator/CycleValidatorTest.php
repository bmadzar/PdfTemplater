<?php

namespace PdfTemplater\Node\Validator;

use PdfTemplater\Node\Basic\Node;
use PHPUnit\Framework\TestCase;

class CycleValidatorTest extends TestCase
{

    public function testOneNode()
    {
        $validator = new CycleValidator();
        $node = new Node('test');

        $this->assertTrue($validator->validate($node));
    }

    public function testNoCycles1()
    {
        $validator = new CycleValidator();

        $node = new Node('test');

        for ($i = 0; $i < 3; ++$i) {
            $child = new Node('test');
            $child->setParent($node);
            for ($j = 0; $j < 3; ++$j) {
                $gchild = new Node('test');
                $gchild->setParent($child);
            }
            unset($j, $gchild);
        }
        unset($i, $child);

        $this->assertTrue($validator->validate($node));
    }

    public function testCycles1()
    {
        $validator = new CycleValidator();

        $node = new Node('test');
        $child = new Node('test');

        $node->setParent($child);
        $child->setParent($node);

        $this->assertFalse($validator->validate($node));
    }

    public function testCycles2()
    {
        $validator = new CycleValidator();

        $node = new Node('test');
        $child = new Node('test');
        $gchild = new Node('test');

        $node->setParent($gchild);
        $child->setParent($node);
        $gchild->setParent($child);

        $this->assertFalse($validator->validate($node));
    }
}
