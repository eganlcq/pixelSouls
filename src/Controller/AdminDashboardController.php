<?php

namespace App\Controller;

use App\Service\Stats;
use App\Repository\FighterRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ObjectManager $manager, Stats $statsService, FighterRepository $repo)
    {
        $stats = $statsService->getStats();
        $bestCharacters = $statsService->getCharactersStats('DESC');
        $worstCharacters = $statsService->getCharactersStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestCharacters' => $bestCharacters,
            'worstCharacters' => $worstCharacters
        ]);
    }
}
