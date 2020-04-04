<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Patient;
use App\Entity\Consultation;
use App\Repository\PatientRepository;
use App\Repository\ConsultationRepository;

use App\Form\PatientType;
use App\Form\ConsultationType;





class GestionController extends AbstractController
{
    /**
     * @Route("/gestion", name="gestion")
     */
    public function index(PatientRepository $repo)
    {
        $patients = $repo->findAll();

        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'GestionController',
            'patients' => $patients
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('gestion/home.html.twig');
    }

    /**
     * @Route("/gestion/new" ,name="gestion_create")
     * @Route("/gestion/{id}/edit", name="gestion_edit")
     */
    public function create(Patient $patient=null,Request $request)
    {
        if(!$patient){
            $patient = new Patient();
        }

        $form = $this->createForm(PatientType::class, $patient);
        
        $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
         {
             if(!$patient->getId()){
                $patient ->setCreatedAt(new \DateTime());
                $this->addFlash('success','Créer avec succès');

             }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patient);
            $entityManager->flush();
            return $this->redirectToRoute('patient_show',['id' => $patient->getId()]);
         }

         if($patient->getId() !==null )
        {
            $this->addFlash('danger','Modifer avec succès');
        }
          
        return $this->render('gestion/create.html.twig',[
            'formPatient'=> $form->createView(),
            'editMode' => $patient->getId() !== null
        ]);
    }

    /**
     * @Route("/patient/{id}",name="patient_show")
     */

    public function show(Patient $patient)
    {   
        return $this->render('gestion/show.html.twig',[
            'patient' => $patient]);
    }

    /**
     * @Route("/gestion/delete/{id}",name="gestion_delete")
     */

     public function delete(Patient $patients,PatientRepository $repo)
     {  
        $cons=$patients->getConsultations();
        
        foreach($cons as $key => $value)
        {
            if($value!==null)
            {
            $this->addFlash('infosupp','le patient a un rendez-vous');
            return $this->redirectToRoute('gestion');
            }
            
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($patients);
        $entityManager->flush();
        
        $patients = $repo->findAll();
        
        $this->addFlash('info','Supprimer avec succès');


        return $this->render('gestion/index.html.twig',['patients' => $patients]);
     }


     /**
      * @Route("/consultation", name="consultation_liste")
      */

      public function listConsultation(ConsultationRepository $repo)
      {
        $entityManager = $this->getDoctrine()->getManager();

        $repoConsultations = $entityManager->getRepository(Consultation::class);
        
        $totalConsultations = $repoConsultations->createQueryBuilder('c')
                            ->select('count(c.id)')
                            ->getQuery()
                            ->getSingleScalarResult();


        $totalConsultationsparjour = $repoConsultations->createQueryBuilder('c')
                            ->select('count(c.id)')
                            ->where('c.dateRdv = CURRENT_DATE()')
                            ->getQuery()
                            ->getSingleScalarResult();
        
    
                            // return new Response($totalConsultations);

        $consultat = $repo->findAll();

                            //dump($totalConsultations);   
                            //dump($totalConsultationsparjour);
                            //dump($consultat);

    return $this->render('gestion/listeConsultation.html.twig', ['controller_name' => 'GestionController',
                                'consultations' => $consultat, 
                                'totals'=>$totalConsultations
                                ]);
      }

      /**
       * @Route("/consultation/jour", name="consultation_jour")
       */
      
      public function consultationJour()
      {

        $entityManager = $this->getDoctrine()->getManager();

        $repoConsultations = $entityManager->getRepository(Consultation::class);
        

        $totalConsultationsparjournee = $repoConsultations->createQueryBuilder('c')
                            ->select('p','c')
                            ->innerJoin('App\Entity\Patient','p',\Doctrine\ORM\Query\Expr\Join::WITH,'c.patients=p')     
                            ->where('c.dateRdv = CURRENT_DATE()')
                            ->getQuery()
                            ->getScalarResult();



       // dump($totalConsultationsparjournee);        

        $totalConsultationsparjour = $repoConsultations->createQueryBuilder('c')
                            ->select('count(c.id)')
                            ->where('c.dateRdv = CURRENT_DATE()')
                            ->getQuery()
                            ->getSingleScalarResult();
                    
        return $this->render('gestion/consultationJour.html.twig', ['controller_name' => 'GestionController',
                            'consultationjour' => $totalConsultationsparjournee, 
                            'totalJournee'=>$totalConsultationsparjour
                            ]);
      }


     /**
      * @Route("/consultation/{id}",name="consultation_show")
      */
      
     public function showConsultation($id,Request $request,Patient $patient)
     {          
        
        $patient = $this->getDoctrine()
                ->getRepository(Patient::class)
                ->find($id);       

            $consultation = new Consultation();
        

        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
         {
             $consultation ->setCreatedAt(new \DateTime());

             $consultation ->setPatients($patient);
             
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($consultation);
             
             $entityManager->flush();

             $this->addFlash('success','Créer avec succès');
            //  return new Response('Saved ');

             return $this->redirectToRoute('consultation_show',['id' => $patient->getId()]);

         }


  
        //dump($patient);
        return $this->render('gestion/showConsultation.html.twig',[
            'patient' => $patient,'formconsultation'=> $form->createView()]);
     }


     /**
      * @Route("/consultation/delete/{id}",name="consultation_delete")
      */
     public function deleteConsultation(Consultation $consultation,PatientRepository $repo)
     {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($consultation);
        $entityManager->flush();

        $this->addFlash('dangercons','Supprimer avec succès');

        return $this->redirectToRoute('gestion');
     }
     




}
