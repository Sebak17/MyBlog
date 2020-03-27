<?php

namespace App\Controller\System;

use App\Entity\Article;
use App\Entity\ImageFile;
use App\Form\ArticleFormType;
use App\Form\ImageUploadFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/systemPanel", name="systemPanel_")
 * @IsGranted("ROLE_ADMIN")
 */
class PanelSystemController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/uploadPhoto", name="uploadPhoto", methods={"POST"})
     */
    public function uploadPhoto(Request $request, SluggerInterface $slugger)
    {
        $response = array();

        $form = $this->createForm(ImageUploadFormType::class);
        $form->submit($request->files->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('upload')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = md5($safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension()) .'.'. $imageFile->guessExtension();

                try {
                    $imageFile->move( $this->getParameter('images_directory'), $newFilename );

                    $entityManager = $this->getDoctrine()->getManager();

                    // $obj = new ImageFile();
                    // $obj->setHash($newFilename);

                    // $entityManager->persist($obj);
                    // $entityManager->flush();

                } catch (FileException $e) {}

                $response['success'] = true;

                $response['name'] = $newFilename;
                $response['url'] = "/uploads/images/" . $newFilename;

                return new JsonResponse($response);
            }

        }

        $response['error']['message'] = "Błąd podczas dodawania zdjęcia!";

        return new JsonResponse($response);
    }

    /**
     * @Route("/articleAdd", name="articleAdd", methods={"POST"})
     */
    public function articleAdd(Request $request, SluggerInterface $slugger)
    {
        $response = array();

        if (!$this->isCsrfTokenValid('articleAdd', $request->request->get('_token'))) {
            
            $response['error'] = "The CSRF token is invalid. Please try to refresh page.";

            $response['success'] = false;
            return new JsonResponse($response);
        }

        $article = new Article();

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $article = $form->getData();
            
            $article->setCreatedAt(new \DateTime());
            $article->setUpdatedAt(new \DateTime());

            $entityManager->persist($article);
            $entityManager->flush();


            $response['url'] = $this->generateUrl('panel_articles');

            $response['success'] = true;
            return new JsonResponse($response);
        }


        if(count($form->getErrors(true)) > 0 )
            $response['error'] = $form->getErrors(true)->current()->getMessage();
        else 
            $response['error'] = "Wystąpił błąd podczas dodawania artykułu!";

        $response['success'] = false;
        return new JsonResponse($response);
    }


    /**
     * @Route("/articleEdit", name="articleEdit", methods={"POST"})
     */
    public function articleEdit(Request $request, SluggerInterface $slugger)
    {
        $response = array();

        if (!$this->isCsrfTokenValid('articleEdit', $request->request->get('_token'))) {
            
            $response['error'] = "The CSRF token is invalid. Please try to refresh page.";

            $response['success'] = false;
            return new JsonResponse($response);
        }

        $article = $this->getDoctrine()->getRepository(Article::class)->find($request->request->get("id"));

        if($article == null) {
            $response['error'] = "Artykuł nie istnieje!";

            $response['success'] = false;
            return new JsonResponse($response);
        }
        

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $article = $form->getData();
            
            $article->setUpdatedAt(new \DateTime());

            $entityManager->merge($article);
            $entityManager->flush();


            $response['success'] = true;
            return new JsonResponse($response);
        }


        if(count($form->getErrors(true)) > 0 )
            $response['error'] = $form->getErrors(true)->current()->getMessage();
        else 
            $response['error'] = "Wystąpił błąd podczas zapisywania artykułu!";

        $response['success'] = false;
        return new JsonResponse($response);
    }

}
