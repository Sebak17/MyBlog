<?php

namespace App\Controller\System;

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
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move( $this->getParameter('images_directory'), $newFilename );
                } catch (FileException $e) {}

                $response['url'] = "/uploads/images/" . $newFilename;

                return new JsonResponse($response);
            }

        }

        $response['error']['message'] = "Błąd podczas dodawania zdjęcia!";

        return new JsonResponse($response);
    }

}
