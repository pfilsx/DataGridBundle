<?php


namespace Pfilsx\tests;


use DateTime;
use Doctrine\ORM\EntityManager;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\tests\TestEntities\Node;
use Pfilsx\tests\TestEntities\NodeAssoc;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class OrmTestCase extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var DataGridServiceContainer
     */
    protected $serviceContainer;


    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $application->run(new ArrayInput(array(
            'doctrine:schema:drop',
            '--force' => true
        )));
        $application->run(new ArrayInput(array(
            'doctrine:schema:create'
        )));


        /** @noinspection PhpParamsInspection */
        $this->serviceContainer = new DataGridServiceContainer(
            $kernel->getContainer()->get('doctrine'),
            $kernel->getContainer()->get('router'),
            $kernel->getContainer()->get('twig'),
            $kernel->getContainer()->get('request_stack'),
            $kernel->getContainer()->get('translator')
        );

        $this->createEntityManager();
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
