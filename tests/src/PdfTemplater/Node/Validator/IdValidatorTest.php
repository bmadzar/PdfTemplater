<?php
declare(strict_types=1);

namespace PdfTemplater\Node\Validator;

use PdfTemplater\Node\Basic\Node;
use PHPUnit\Framework\TestCase;

class IdValidatorTest extends TestCase
{
    public function testOneNode()
    {
        $validator = new IdValidator();
        $node = new Node('test');

        $this->assertTrue($validator->validate($node));
    }

    public function testNoDuplicates1()
    {
        $validator = new IdValidator();

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

    public function testNoDuplicates2()
    {
        $validator = new IdValidator();

        $node = new Node('test');
        $node->setId('node');

        for ($i = 0; $i < 3; ++$i) {
            $child = new Node('test');
            $child->setId('child' . $i);
            $child->setParent($node);
            for ($j = 0; $j < 3; ++$j) {
                $gchild = new Node('test');
                $gchild->setId('gchild' . $i . $j);
                $gchild->setParent($child);
            }
            unset($j, $gchild);
        }
        unset($i, $child);

        $this->assertTrue($validator->validate($node));
    }

    public function testNoDuplicates3()
    {
        $validator = new IdValidator();

        $node1 = new Node('test');
        $node1->setId('node');

        $node2 = new Node('test');
        $node2->setId('node');

        $this->assertTrue($validator->validate($node1));
        $this->assertTrue($validator->validate($node2));
    }

    public function testDuplicates1()
    {
        $validator = new IdValidator();

        $node = new Node('test');
        $node->setId('child1');

        for ($i = 0; $i < 3; ++$i) {
            $child = new Node('test');
            $child->setId('child' . $i);
            $child->setParent($node);
            for ($j = 0; $j < 3; ++$j) {
                $gchild = new Node('test');
                $gchild->setId('gchild' . $i . $j);
                $gchild->setParent($child);
            }
            unset($j, $gchild);
        }
        unset($i, $child);

        $this->assertFalse($validator->validate($node));
    }


    public function testDuplicates2()
    {
        $validator = new IdValidator();

        $node = new Node('test');
        $node->setId('node');

        for ($i = 0; $i < 3; ++$i) {
            $child = new Node('test');
            $child->setId('child' . $i);
            $child->setParent($node);
            for ($j = 0; $j < 3; ++$j) {
                $gchild = new Node('test');
                $gchild->setId('gchild' . $j);
                $gchild->setParent($child);
            }
            unset($j, $gchild);
        }
        unset($i, $child);

        $this->assertFalse($validator->validate($node));
    }
}
