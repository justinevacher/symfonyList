<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        
       
        return [];
        
    }

    /**
     * @Route("/shows", name="shows")
     * @Template()
     */
    public function showsAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:TVShow');
        $series = $repo->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $series, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        // parameters to template
        return [
                'shows' => $pagination
        ];
    }

    /**
     * @Route("/show/{id}", name="show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:TVShow');

        return [
            'show' => $repo->find($id)
        ];        
    }

    /**
     * @Route("/calendar", name="calendar")
     * @Template()
     */
    public function calendarAction()
    {
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:Episode');

        $query = $repo->createQueryBuilder('e')
            ->innerJoin('e.season', 's')
            ->addSelect('s')
            ->innerJoin('s.show', 'tv')
            ->addSelect('tv')
            ->where('e.date >= CURRENT_DATE()')
            ->orderBy('e.date', 'ASC')
            ->getQuery();
        
         $episodes = $query->getResult();
        return [
            'episodes'=> $episodes
        ];
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
        return [];
    }
    
    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction(Request $request)
    {   
      
        $search = $request->get('search');
        /*découpement des mots dans un tableau*/
        $words = str_word_count($search,1);
        if($search && count($words)!=0){
            $em = $this->get('doctrine')->getManager();
            $repo = $em->getRepository('AppBundle:TVShow');
            $query = $repo->createQueryBuilder('s')
                    ->where('s.name LIKE :key')
                     ->setParameter(':key','%'.$words[0].'%');

            for($word=1;$word<count($words);$word++){
                $query = $query->andWhere('s.name LIKE :key'.$word)
                     ->setParameter(':key'.$word,'%'.$words[$word].'%');

            }
                       
            $query = $query->orderBy('s.name', 'ASC')
                    ->getQuery();
            $shows = $query->getResult();
           
            
            return [
                'shows'=> $shows,
                'search' => $search
            
            ];
        }
         return $this->redirect($_SERVER['HTTP_REFERER']);
    }

}
