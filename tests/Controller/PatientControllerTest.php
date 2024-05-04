<?php

namespace App\Test\Controller;

use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PatientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/patient/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Patient::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Patient index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'patient[name]' => 'Testing',
            'patient[lastName]' => 'Testing',
            'patient[birthday]' => 'Testing',
            'patient[adresse]' => 'Testing',
            'patient[phone]' => 'Testing',
            'patient[patientAnalyse]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Patient();
        $fixture->setName('My Title');
        $fixture->setLastName('My Title');
        $fixture->setBirthday('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setPhone('My Title');
        $fixture->setPatientAnalyse('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Patient');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Patient();
        $fixture->setName('Value');
        $fixture->setLastName('Value');
        $fixture->setBirthday('Value');
        $fixture->setAdresse('Value');
        $fixture->setPhone('Value');
        $fixture->setPatientAnalyse('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'patient[name]' => 'Something New',
            'patient[lastName]' => 'Something New',
            'patient[birthday]' => 'Something New',
            'patient[adresse]' => 'Something New',
            'patient[phone]' => 'Something New',
            'patient[patientAnalyse]' => 'Something New',
        ]);

        self::assertResponseRedirects('/patient/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getLastName());
        self::assertSame('Something New', $fixture[0]->getBirthday());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getPhone());
        self::assertSame('Something New', $fixture[0]->getPatientAnalyse());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Patient();
        $fixture->setName('Value');
        $fixture->setLastName('Value');
        $fixture->setBirthday('Value');
        $fixture->setAdresse('Value');
        $fixture->setPhone('Value');
        $fixture->setPatientAnalyse('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/patient/');
        self::assertSame(0, $this->repository->count([]));
    }
}
