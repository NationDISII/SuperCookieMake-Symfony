<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Cookie;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCookieData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /** @var  Container */
    protected $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $VectorXHD */
        $VectorXHD = $this->getReference("VectorXHD-user");


        $cookie_VectorXHD = new Cookie();
        $cookie_VectorXHD->setName("Best cookies");
        $cookie_VectorXHD->setDescription("Yayy cookie !");
        $cookie_VectorXHD->setOwner($VectorXHD);

        $manager->persist($cookie_VectorXHD);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}