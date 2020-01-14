<?php

namespace PdfTemplater\Node\Basic;

use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{

    public function testRemoveChild()
    {
        $node = new Node('test');
        $child = new Node('test');

        $this->assertFalse($node->hasChild($child));

        $node->addChild($child);

        $this->assertTrue($node->hasChild($child));
        $this->assertTrue($child->hasParent());

        $node->removeChild($child);

        $this->assertFalse($node->hasChild($child));
        $this->assertFalse($child->hasParent());
    }

    public function testGetChildById()
    {
        $node = new Node('test');
        $child1 = new Node('test');
        $child2 = new Node('test');
        $gchild1 = new Node('test');
        $gchild2 = new Node('test');
        $gchild3 = new Node('test');

        $node->addChild($child1);
        $node->addChild($child2);

        $child1->addChild($gchild1);
        $child1->addChild($gchild2);

        $child2->addChild($gchild3);

        $this->assertSame($node, $node->getChildById($node->getId()));
        $this->assertSame($child1, $node->getChildById($child1->getId()));
        $this->assertSame($child2, $node->getChildById($child2->getId()));
        $this->assertSame($gchild1, $node->getChildById($gchild1->getId()));
        $this->assertSame($gchild2, $node->getChildById($gchild2->getId()));
        $this->assertSame($gchild3, $node->getChildById($gchild3->getId()));
        $this->assertNull($node->getChildById('_'));
        $this->assertNull($node->getChildById(''));
    }

    public function testGetType()
    {
        $node = new Node('test');

        $this->assertSame('test', $node->getType());

        $node->setType('test2');

        $this->assertSame('test2', $node->getType());
    }

    public function testGetAttributes()
    {
        $node = new Node('test');

        $node->setAttribute('attribute1', 'value1');
        $node->setAttribute('attribute2', 'value2');

        $attrs = $node->getAttributes();

        $this->assertArrayHasKey('attribute1', $attrs);
        $this->assertArrayHasKey('attribute2', $attrs);

        $this->assertSame('value1', $attrs['attribute1']);
        $this->assertSame('value2', $attrs['attribute2']);

    }

    public function testAddChild()
    {
        $node = new Node('test');
        $child = new Node('test');

        $this->assertFalse($node->hasChild($child));
        $this->assertFalse($child->hasParent());
        $this->assertFalse($node->hasParent());
        $this->assertFalse($child->hasChildren());

        $node->addChild($child);

        $this->assertTrue($node->hasChild($child));
        $this->assertSame($node, $child->getParent());
        $this->assertFalse($node->hasParent());
        $this->assertFalse($child->hasChildren());
    }

    public function testSetParent()
    {
        $node = new Node('test');
        $child = new Node('test');

        $this->assertFalse($node->hasChild($child));
        $this->assertFalse($child->hasParent());
        $this->assertFalse($node->hasParent());
        $this->assertFalse($child->hasChildren());

        $child->setParent($node);

        $this->assertTrue($node->hasChild($child));
        $this->assertSame($node, $child->getParent());
        $this->assertFalse($node->hasParent());
        $this->assertFalse($child->hasChildren());

        $child->setParent(null);

        $this->assertFalse($node->hasChild($child));
        $this->assertNull($child->getParent());
        $this->assertFalse($node->hasParent());
        $this->assertFalse($child->hasChildren());
    }

    public function testHasParent()
    {
        $node = new Node('test');
        $child = new Node('test');

        $this->assertFalse($child->hasParent());

        $child->setParent($node);

        $this->assertTrue($child->hasParent());
    }

    public function testGetId()
    {
        $node = new Node('test');

        $this->assertNotEmpty($node->getId());

        $node->setId('test');

        $this->assertSame('test', $node->getId());
    }

    public function testSetChildren()
    {
        $node = new Node('test');

        $child1 = new Node('test');
        $child2 = new Node('test');
        $child3 = new Node('test');

        $node->setChildren([$child1, $child2]);

        $this->assertTrue($node->hasChild($child1));
        $this->assertTrue($node->hasChild($child2));
        $this->assertFalse($node->hasChild($child3));

        $node->setChildren([$child1, $child3]);

        $this->assertTrue($node->hasChild($child1));
        $this->assertFalse($node->hasChild($child2));
        $this->assertTrue($node->hasChild($child3));

        $node->setChildren([]);

        $this->assertFalse($node->hasChildren());
    }

    public function testGetParent()
    {
        $node = new Node('test');
        $child = new Node('test');

        $this->assertNull($child->getParent());

        $child->setParent($node);

        $this->assertSame($node, $child->getParent());
    }

    public function testSetAttribute()
    {
        $node1 = new Node('test');

        $node1->setAttribute('attribute1', 'value1');

        $this->assertSame('value1', $node1->getAttribute('attribute1'));

        $node1->setAttribute('attribute1', 'value2');

        $this->assertSame('value2', $node1->getAttribute('attribute1'));

    }

    public function testSetAttributes()
    {
        $node1 = new Node('test');

        $node1->setAttributes(['attribute1' => 'value1', 'attribute2' => 'value2']);

        $this->assertSame('value1', $node1->getAttribute('attribute1'));
        $this->assertSame('value2', $node1->getAttribute('attribute2'));

        $node1->setAttributes(['attribute1' => 'value3']);

        $this->assertSame('value3', $node1->getAttribute('attribute1'));
        $this->assertSame('value2', $node1->getAttribute('attribute2'));
    }

    public function testResetAttributes()
    {
        $node1 = new Node('test');

        $node1->setAttributes(['attribute1' => 'value1', 'attribute2' => 'value2']);

        $this->assertSame('value1', $node1->getAttribute('attribute1'));
        $this->assertSame('value2', $node1->getAttribute('attribute2'));

        $node1->resetAttributes();

        $this->assertSame([], $node1->getAttributes());

        $node1->resetAttributes(['attribute1' => 'value1', 'attribute2' => 'value2']);

        $this->assertSame('value1', $node1->getAttribute('attribute1'));
        $this->assertSame('value2', $node1->getAttribute('attribute2'));
    }

    public function testGetChildren()
    {
        $node = new Node('test');

        $this->assertSame([], $node->getChildren());

        $child1 = new Node('test');
        $child2 = new Node('test');

        $node->setChildren([$child1, $child2]);

        $children = $node->getChildren();

        $this->assertContains($child1, $children);
        $this->assertContains($child2, $children);
    }

    public function testConstruct()
    {
        $node1 = new Node('type');

        $this->assertInstanceOf(Node::class, $node1);
        $this->assertSame('type', $node1->getType());

        $node2 = new Node('type', null, ['attribute1' => 'value1']);

        $this->assertInstanceOf(Node::class, $node2);
        $this->assertSame('type', $node2->getType());
        $this->assertSame('value1', $node2->getAttribute('attribute1'));
    }

    public function testHasChildren()
    {
        $node = new Node('test');

        $this->assertFalse($node->hasChildren());

        $child = new Node('test');
        $node->addChild($child);

        $this->assertFalse($child->hasChildren());
        $this->assertTrue($node->hasChildren());

        $node->removeChild($child);

        $this->assertFalse($node->hasChildren());
    }

    public function testSetId()
    {
        $node = new Node('test');

        $node->setId('test');

        $this->assertEquals('test', $node->getId());
    }

    public function testSetType()
    {
        $node = new Node('test1');

        $this->assertSame('test1', $node->getType());

        $node->setType('test2');

        $this->assertSame('test2', $node->getType());
    }

    public function testHasChild()
    {
        $node = new Node('test');

        $child1 = new Node('test');
        $child2 = new Node('test');

        $this->assertFalse($node->hasChild($child1));
        $this->assertFalse($node->hasChild($child2));

        $node->addChild($child1);

        $this->assertTrue($node->hasChild($child1));
        $this->assertFalse($node->hasChild($child2));

        $child1->addChild($child2);

        $this->assertTrue($node->hasChild($child1));
        $this->assertTrue($child1->hasChild($child2));
        $this->assertFalse($node->hasChild($child2));

        $node->removeChild($child1);

        $this->assertFalse($node->hasChild($child1));
        $this->assertFalse($node->hasChild($child2));
    }

    public function testGetAttribute()
    {
        $node1 = new Node('test');

        $this->assertNull($node1->getAttribute('attribute1'));

        $node1->setAttribute('attribute1', 'value1');

        $this->assertSame('value1', $node1->getAttribute('attribute1'));

        $node2 = new Node('test', null, ['attribute1' => 'value1']);

        $this->assertSame('value1', $node2->getAttribute('attribute1'));

    }
}
