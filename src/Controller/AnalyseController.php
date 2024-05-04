<?php

namespace App\Controller;

use App\Entity\Analyse;
use App\Entity\Patient;
use App\Form\AnalyseType;
use App\Repository\AnalyseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/analyse')]
class AnalyseController extends AbstractController
{
    #[Route('/', name: 'app_analyse_index', methods: ['GET'])]
    public function index(AnalyseRepository $analyseRepository): Response
    {
        return $this->render('analyse/index.html.twig', [
            'analyses' => $analyseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_analyse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

         if ($this->isGranted('ROLE_TECH')) {
        throw new AccessDeniedException('Access Denied.'); // Deny access if the user is not a technician
    }
        $analyse = new Analyse();
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             // Add the patient to the analysis
            $patientIds = $form->get('patients')->getData();
            foreach ($patientIds as $patientId) {
            $patient = $entityManager->getRepository(Patient::class)->find($patientId);
            if ($patient) {
                $analyse->addPatient($patient);
            } else {
                // Handle case where patient with given ID is not found
                 throw new \InvalidArgumentException(sprintf('Patient with ID %s not found', $patientId));
            }
              }



            $entityManager->persist($analyse);
            $entityManager->flush();

            return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('analyse/new.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }

    #[Route('/new/{patientId}', name: 'app_analyse_new_for_patient', methods: ['GET', 'POST'])]
    public function newById(Request $request, EntityManagerInterface $entityManager, int $patientId): Response
    {
         if ($this->isGranted('ROLE_TECH')) {
        throw new AccessDeniedException('Access Denied.'); // Deny access if the user is not a technician
    }
       
         // Retrieve the patient entity based on the patientId
    $patient = $entityManager->getRepository(Patient::class)->find($patientId);

    // Check if the patient exists
    if (!$patient) {
        throw $this->createNotFoundException('Patient not found');
    }

    $analyse = new Analyse();
    $analyse->addPatient($patient); // Associate the patient with the new analysis

        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($analyse);
            $entityManager->flush();

            return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('analyse/new.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_analyse_show', methods: ['GET'])]
    public function show(Analyse $analyse): Response
    {
        return $this->render('analyse/show.html.twig', [
            'analyse' => $analyse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_analyse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('analyse/edit.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_analyse_delete', methods: ['POST'])]
    public function delete(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {

         if ($this->isGranted('ROLE_TECH')) {
        throw new AccessDeniedException('Access Denied.'); // Deny access if the user is not a technician
    }
        if ($this->isCsrfTokenValid('delete'.$analyse->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($analyse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
    }
}
