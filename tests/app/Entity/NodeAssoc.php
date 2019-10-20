<?php


namespace Pfilsx\tests\app\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class NodeAssoc
{
    protected $id;
    protected $name;
    /**
     * @var ArrayCollection $offerList
     *
     */
    protected $nodeList;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addNodeList(Node $node)
    {
        $this->nodeList[] = $node;
        return $this;
    }

    public function removeNodeList(Node $node)
    {
        $this->nodeList->removeElement($node);
        return $this;
    }

    public function getNodeList()
    {
        return $this->nodeList;
    }
}
