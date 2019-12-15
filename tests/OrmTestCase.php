<?php


namespace Pfilsx\tests;


use DateTime;
use Doctrine\ORM\EntityManager;
use Pfilsx\tests\app\Entity\Node;
use Pfilsx\tests\app\Entity\NodeAssoc;
use Twig\TemplateWrapper;

class OrmTestCase extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var TemplateWrapper
     */
    protected $template;


    protected function setUp(): void
    {
        parent::setUp();

        $this->createEntityManager();

        $this->template = $this->serviceContainer->getTwig()->load('test_template.html.twig');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function createEntityManager()
    {
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->createTestEntities();
        return $this->em;
    }


    public function getEntityManager()
    {
        return $this->em ?? ($this->em = $this->createEntityManager());
    }

    /**
     * @throws \Exception
     */
    public function createTestEntities()
    {
        $this->em->persist($nodeAssoc = (new NodeAssoc())
            ->setId(1)
            ->setName('test assoc')
        );
        $node1 = (new Node())
            ->setId(1)
            ->setContent('foobar')
            ->setUser('joe')
            ->setCreatedAt(new DateTime('2010-04-24 17:15:23'))
            ->setParentId('');
        $this->em->persist($node1);
        $node2 = (new Node())
            ->setId(2)
            ->setContent('I like it!')
            ->setUser('toto')
            ->setCreatedAt(new DateTime('2010-04-26 12:14:20'))
            ->setParentId('1')
            ->setMainNode($node1);
        $this->em->persist($node2);
        $node3 = (new Node())
            ->setId(3)
            ->setContent('I like it!')
            ->setUser('toto')
            ->setCreatedAt(new DateTime('2010-04-27 12:14:20'))
            ->setParentId('1')
            ->setMainNode($node1);
        $this->em->persist($node3);
        $node4 = (new Node())
            ->setId(4)
            ->setContent('Hello bob')
            ->setUser('toto')
            ->setCreatedAt(new DateTime('2010-04-28 12:14:20'))
            ->setParentId('2');
        $this->em->persist($node4);
        $node5 = (new Node())
            ->setId(5)
            ->setContent('I like it!')
            ->setUser('toto')
            ->setCreatedAt(new DateTime('2010-04-29 12:14:20'));
        $this->em->persist($node5);
        $node6 = (new Node())
            ->setId(6)
            ->setContent('Hello robert')
            ->setUser('foouser')
            ->setCreatedAt(new DateTime('2010-04-26 12:14:20'));
        $this->em->persist($node6);
        $node7 = (new Node())
            ->setId(7)
            ->setContent('I like it!')
            ->setUser('fös')
            ->setCreatedAt(new DateTime('2010-04-17 12:14:20'));
        $this->em->persist($node7);
        $this->em->persist($node8 = (new Node())
            ->setId(8)
            ->setContent('Hello foo')
            ->setUser('foouser')
            ->setCreatedAt(new DateTime('2010-04-18 12:14:20'))
        );
        $this->em->persist($node9 = (new Node())
            ->setId(9)
            ->setContent('I fös it!')
            ->setUser('foo')
            ->setCreatedAt(new DateTime('2010-04-19 12:14:20'))
        );
        $this->em->persist($node10 = (new Node())
            ->setId(10)
            ->setContent('I like it!')
            ->setUser('bar')
            ->setCreatedAt(new DateTime('2010-04-20 12:14:20'))
        );
        $this->em->persist($node11 = (new Node())
            ->setId(11)
            ->setContent('I like it!')
            ->setUser('toto')
            ->setCreatedAt(new DateTime('2010-04-21 12:14:20'))
            ->setMainNode($node1)
            ->setAssoc($nodeAssoc)
        );
        $this->em->flush();
    }
}
