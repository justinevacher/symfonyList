<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ListController extends Controller
{

    /**
     * @Route("/", name="lists")
     * @Template()
     */
    public function listsAction()
    {
        //TODO : petite phrase si aucune liste de créé et insiter à en créer une
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:List');
        $lists = $repo->findAll();
        return ['lists'=>$lists];

    }

    /**
     * @Route("/lists/{id}", name="tasks")
     * @Template()
     */
    public function tachesAction($id)
    {
        //TODO : petite phrase si aucune liste de créé et insiter à en créer une
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:Liste');
        $taches = $repo->findBy(['']);
        return ['taches'=>$taches];

    }



}
