<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ContactsController extends Controller
{
    /**
     * @Route("/admin/contacts", name="contacts_list")
     * @return Response
     */
    public function listAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $contacts = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->findAllOrderedByLastName();

        return $this->render('@App/contacts/list.html.twig', ['contacts' => $contacts]);
    }

    /**
     * @Route("/admin/contact/{id}", name="contact_detail", requirements={"id" = "\d+"})
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $contact = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->findOneById($id);

        if ($contact === null) {
            throw $this->createNotFoundException('The contact does not exist');
        }

        return $this->render('@App/contacts/detail.html.twig', ['contact' => $contact]);
    }

    /**
     * @Route("/admin/contact/add", name="contact_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();
            if($pictureFile) {
                // create unique file name, independent from uploaded filename
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid(). '.' . $pictureFile->guessExtension();

                $pictureFile->move(
                    $this->getParameter('uploads_directory'), // from config.yml
                    $newFilename
                );
                $contact->setPicture($newFilename);
            }

            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($contact);
            $dbManager->flush();

            $this->addFlash(
                'success',
                'Contact was successfully added!'
            );

            return $this->redirectToRoute('contacts_list');
        }

        return $this->render('@App/contacts/add.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Add',
            'buttonAction' => 'Save new contact'
        ));
    }

    /**
     * @Route("/admin/contact/edit/{id}", name="contact_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $contact = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->findOneById($id);

        if ($contact === null) {
            $this->addFlash(
                'error',
                'Contact not found'
            );
            return $this->redirectToRoute('contacts_list');
        }

        // from https://symfony.com/doc/3.4/controller/upload_file.html
        // When creating a form to edit an already persisted item, the file form type still expects a File instance. As the persisted entity now contains only the relative file path, you first have to concatenate the configured upload path with the stored filename and create a new File class:
        try{
            $contact->setPicture(
                new File($this->getParameter('uploads_directory') . '/' . $contact->getPicture())
            );
        } catch (FileNotFoundException $exception) {}
        
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->flush();

            $this->addFlash(
                'success',
                'Contact was successfully edited!'
            );

            return $this->redirectToRoute('contacts_list');
        }

        return $this->render('@App/contacts/add.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Edit',
            'buttonAction' => 'Save contact'
        ));
    }

    /**
     * @Route("/admin/contact/remove/{id}", name="contact_remove", requirements={"id" = "\d+"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removeAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $contact = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->findOneById($id);

        if ($contact === null) {
            throw $this->createNotFoundException('The requested contact does not exist');
        }

        // remove file from system
        $pictureFileName = $contact->getPicture();
        $picturePath = $this->getParameter('uploads_directory') . '/' . $pictureFileName;
        $fileSystem = new FileSystem();
        $fileSystem->remove([$path]);

        $dbManager = $this->getDoctrine()->getManager();
        $dbManager->remove($contact);
        $dbManager->flush();

        $this->addFlash(
            'info',
            'Contact was successfully deleted!'
        );

        return $this->redirectToRoute('contacts_list');
    }

}
